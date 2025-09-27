<?php

namespace App\Http\Controllers;

use App\Models\SocialPost;
use App\Models\Hashtag;
use App\Models\CarouselText;
use App\Models\ImageBackgroundColor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class SocialPostController extends Controller
{
    /**
     * Display calendar view with posts.
     */
    public function calendar(Request $request)
    {
        $year = $request->get('year', now()->year);
        $month = $request->get('month', now()->month);
        
        // Criar data do primeiro dia do mês
        $currentDate = Carbon::createFromDate($year, $month, 1);
        
        // Buscar posts agendados do mês atual
        $scheduledPosts = SocialPost::forUser(Auth::id())
            ->whereYear('scheduled_date', $year)
            ->whereMonth('scheduled_date', $month)
            ->with(['hashtags', 'carouselTexts'])
            ->get();
        
        // Buscar posts sem data agendada criados no mês atual
        $unscheduledPosts = SocialPost::forUser(Auth::id())
            ->whereNull('scheduled_date')
            ->whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->with(['hashtags', 'carouselTexts'])
            ->get();
        
        // Combinar e agrupar todos os posts por data
        $allPosts = $scheduledPosts->concat($unscheduledPosts);
        
        $posts = $allPosts->groupBy(function($post) {
            // Usar scheduled_date se existir, senão usar created_at
            $date = $post->scheduled_date ? $post->scheduled_date : $post->created_at;
            return $date->format('Y-m-d');
        });
        
        // Estatísticas para cards de resumo
        $stats = [
            'total' => SocialPost::forUser(Auth::id())->count(),
            'agendados' => SocialPost::forUser(Auth::id())->whereNotNull('scheduled_date')->count(),
            'este_mes' => SocialPost::forUser(Auth::id())
                ->where(function($query) use ($year, $month) {
                    $query->where(function($q) use ($year, $month) {
                        $q->whereYear('scheduled_date', $year)
                          ->whereMonth('scheduled_date', $month);
                    })->orWhere(function($q) use ($year, $month) {
                        $q->whereNull('scheduled_date')
                          ->whereYear('created_at', $year)
                          ->whereMonth('created_at', $month);
                    });
                })
                ->count(),
        ];
        
        return view('social-posts.calendar', compact('posts', 'stats', 'currentDate'));
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = SocialPost::with(['hashtags', 'carouselTexts'])
                          ->forUser(Auth::id())
                          ->orderBy('created_at', 'desc');

        // Filtros
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('titulo', 'like', "%{$search}%")
                  ->orWhere('legenda', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->byStatus($request->status);
        }

        $socialPosts = $query->paginate(12);

        // Estatísticas para cards de resumo
        $stats = [
            'total' => SocialPost::forUser(Auth::id())->count(),
            'rascunhos' => SocialPost::forUser(Auth::id())->byStatus('rascunho')->count(),
            'publicados' => SocialPost::forUser(Auth::id())->byStatus('publicado')->count(),
            'arquivados' => SocialPost::forUser(Auth::id())->byStatus('arquivado')->count(),
        ];

        return view('social-posts.index', compact('socialPosts', 'stats'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $socialPost = new SocialPost();
        $statusOptions = SocialPost::getStatusOptions();
        
        // Pré-preencher data e hora se vieram da URL (calendário)
        $scheduledDate = $request->get('date');
        $scheduledTime = $request->get('time', '09:00');
        
        return view('social-posts.create', compact('socialPost', 'statusOptions', 'scheduledDate', 'scheduledTime'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Converter data brasileira para formato do banco
        $scheduledDate = $this->convertBrazilianDateToDatabase($request->scheduled_date);
        
        $validator = Validator::make(array_merge($request->all(), ['scheduled_date' => $scheduledDate]), [
            'titulo' => 'required|string|max:255',
            'legenda' => 'nullable|string',
            'texto_final' => 'nullable|string',
            'call_to_action_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'required|in:rascunho,publicado,arquivado',
            'hashtags' => 'nullable|string',
            'carousel_texts' => 'nullable|array',
            'carousel_texts.*' => 'nullable|string',
            'carousel_texts_combined' => 'nullable|string',
            'scheduled_date' => 'nullable|date',
            'scheduled_time' => 'nullable|date_format:H:i'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                           ->withErrors($validator)
                           ->withInput();
        }

        try {
            DB::beginTransaction();

            // Processar upload da imagem call-to-action
            $callToActionImagePath = null;
            if ($request->hasFile('call_to_action_image')) {
                $callToActionImagePath = $request->file('call_to_action_image')->store('call-to-action-images', 'public');
            }

            // Criar o post
            $socialPost = SocialPost::create([
                'titulo' => $request->titulo,
                'legenda' => $request->legenda,
                'texto_final' => $request->texto_final,
                'call_to_action_image' => $callToActionImagePath,
                'status' => $request->status,
                'scheduled_date' => $scheduledDate,
                'scheduled_time' => $request->scheduled_time,
                'user_id' => Auth::id()
            ]);

            // Processar hashtags
            if ($request->filled('hashtags')) {
                $this->processHashtags($socialPost, $request->hashtags);
            }

            // Processar textos do carrossel
            if ($request->filled('carousel_texts_combined')) {
                $this->processCarouselTexts($socialPost, $request->carousel_texts_combined);
            } elseif ($request->filled('carousel_texts')) {
                $this->processCarouselTexts($socialPost, $request->carousel_texts);
            }

            DB::commit();

            return redirect()->route('social-posts.index')
                           ->with('success', 'Post criado com sucesso!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                           ->with('error', 'Erro ao criar post: ' . $e->getMessage())
                           ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(SocialPost $socialPost)
    {
        // Verificar se o post pertence ao usuário
        if ($socialPost->user_id !== Auth::id()) {
            abort(403);
        }

        $socialPost->load(['hashtags', 'carouselTexts']);
        
        return view('social-posts.show', compact('socialPost'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SocialPost $socialPost)
    {
        // Verificar se o post pertence ao usuário
        if ($socialPost->user_id !== Auth::id()) {
            abort(403);
        }

        $socialPost->load(['hashtags', 'carouselTexts']);
        $statusOptions = SocialPost::getStatusOptions();
        
        return view('social-posts.edit', compact('socialPost', 'statusOptions'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SocialPost $socialPost)
    {
        // Verificar se o post pertence ao usuário
        if ($socialPost->user_id !== Auth::id()) {
            abort(403);
        }

        // Converter data brasileira para formato do banco
        $scheduledDate = $this->convertBrazilianDateToDatabase($request->scheduled_date);

        $validator = Validator::make(array_merge($request->all(), ['scheduled_date' => $scheduledDate]), [
            'titulo' => 'required|string|max:255',
            'legenda' => 'nullable|string',
            'texto_final' => 'nullable|string',
            'call_to_action_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'required|in:rascunho,publicado,arquivado',
            'hashtags' => 'nullable|string',
            'carousel_texts' => 'nullable|array',
            'carousel_texts.*' => 'nullable|string',
            'carousel_texts_combined' => 'nullable|string',
            'scheduled_date' => 'nullable|date',
            'scheduled_time' => 'nullable|date_format:H:i'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                           ->withErrors($validator)
                           ->withInput();
        }

        try {
            DB::beginTransaction();

            // Processar upload da imagem call-to-action
            $updateData = [
                'titulo' => $request->titulo,
                'legenda' => $request->legenda,
                'texto_final' => $request->texto_final,
                'status' => $request->status,
                'scheduled_date' => $scheduledDate,
                'scheduled_time' => $request->scheduled_time
            ];

            // Processar remoção de imagem call-to-action
            if ($request->has('remove_call_to_action_image') && $request->remove_call_to_action_image == '1') {
                if ($socialPost->call_to_action_image && \Storage::disk('public')->exists($socialPost->call_to_action_image)) {
                    \Storage::disk('public')->delete($socialPost->call_to_action_image);
                }
                $updateData['call_to_action_image'] = null;
            }
            // Processar upload de nova imagem call-to-action
            elseif ($request->hasFile('call_to_action_image')) {
                // Remover imagem antiga se existir
                if ($socialPost->call_to_action_image && \Storage::disk('public')->exists($socialPost->call_to_action_image)) {
                    \Storage::disk('public')->delete($socialPost->call_to_action_image);
                }
                // Salvar nova imagem
                $updateData['call_to_action_image'] = $request->file('call_to_action_image')->store('call-to-action-images', 'public');
            }

            // Atualizar o post
            $socialPost->update($updateData);

            // Remover hashtags antigas e decrementar contador
            foreach ($socialPost->hashtags as $hashtag) {
                $hashtag->decrementUsage();
            }
            $socialPost->hashtags()->detach();

            // Processar novas hashtags
            if ($request->filled('hashtags')) {
                $this->processHashtags($socialPost, $request->hashtags);
            }

            // Remover textos do carrossel antigos
            $socialPost->carouselTexts()->delete();

            // Processar novos textos do carrossel
            if ($request->filled('carousel_texts_combined')) {
                $this->processCarouselTexts($socialPost, $request->carousel_texts_combined);
            } elseif ($request->filled('carousel_texts')) {
                $this->processCarouselTexts($socialPost, $request->carousel_texts);
            }

            DB::commit();

            return redirect()->route('social-posts.index')
                           ->with('success', 'Post atualizado com sucesso!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                           ->with('error', 'Erro ao atualizar post: ' . $e->getMessage())
                           ->withInput();
        }
    }

    /**
     * Duplicate the specified resource.
     */
    public function duplicate(SocialPost $socialPost)
    {
        // Verificar se o post pertence ao usuário
        if ($socialPost->user_id !== Auth::id()) {
            abort(403);
        }

        try {
            DB::beginTransaction();

            // Criar uma cópia do post
            $newPost = $socialPost->replicate();
            $newPost->titulo = $socialPost->titulo . ' (Cópia)';
            $newPost->status = 'rascunho';
            $newPost->save();

            // Duplicar hashtags
            foreach ($socialPost->hashtags as $hashtag) {
                $hashtag->incrementUsage();
                $newPost->hashtags()->attach($hashtag->id);
            }

            // Duplicar textos do carrossel
            foreach ($socialPost->carouselTexts as $carouselText) {
                $newPost->carouselTexts()->create([
                    'texto' => $carouselText->texto,
                    'position' => $carouselText->position
                ]);
            }

            DB::commit();

            return redirect()->route('social-posts.edit', $newPost)
                           ->with('success', 'Post duplicado com sucesso!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                           ->with('error', 'Erro ao duplicar post: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SocialPost $socialPost)
    {
        // Verificar se o post pertence ao usuário
        if ($socialPost->user_id !== Auth::id()) {
            abort(403);
        }

        try {
            DB::beginTransaction();

            // Decrementar contador de hashtags
            foreach ($socialPost->hashtags as $hashtag) {
                $hashtag->decrementUsage();
            }

            $socialPost->delete();

            DB::commit();

            return redirect()->route('social-posts.index')
                           ->with('success', 'Post excluído com sucesso!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                           ->with('error', 'Erro ao excluir post: ' . $e->getMessage());
        }
    }

    /**
     * Processar hashtags
     */
    private function processHashtags(SocialPost $socialPost, $hashtagsString)
    {
        // Verificar se é o novo formato (string separada por vírgulas)
        if (is_string($hashtagsString)) {
            $hashtags = array_filter(array_map('trim', explode(',', $hashtagsString)));
        }
        // Manter compatibilidade com formato antigo (array)
        elseif (is_array($hashtagsString)) {
            $hashtags = $hashtagsString;
        }
        else {
            $hashtags = [];
        }
        
        $hashtags = array_slice($hashtags, 0, 30); // Máximo 30 hashtags

        $hashtagIds = [];
        foreach ($hashtags as $hashtagName) {
            $hashtagName = trim($hashtagName);
            if (!empty($hashtagName)) {
                // Remover # se existir
                $hashtagName = ltrim($hashtagName, '#');
                
                $hashtag = Hashtag::findOrCreateByName($hashtagName);
                $hashtag->incrementUsage();
                $hashtagIds[] = $hashtag->id;
            }
        }

        $socialPost->hashtags()->attach($hashtagIds);
    }

    /**
     * Processar textos do carrossel
     */
    private function processCarouselTexts(SocialPost $socialPost, $carouselTexts)
    {
        // Verificar se é o novo formato (campo único com divisores)
        if (is_string($carouselTexts)) {
            $slides = array_filter(array_map(function($slide) {
                // Remove \n do início e fim, e trim
                $cleaned = trim(preg_replace('/^\\n+|\\n+$/', '', trim($slide)));
                return $cleaned;
            }, explode('---', $carouselTexts)));
            
            foreach ($slides as $position => $texto) {
                // Verificar se o texto não é apenas \n ou está vazio
                if (!empty($texto) && $texto !== '\n' && trim($texto) !== '') {
                    $socialPost->carouselTexts()->create([
                        'texto' => $texto,
                        'position' => $position + 1
                    ]);
                }
            }
        }
        // Manter compatibilidade com formato antigo (array)
        elseif (is_array($carouselTexts) && !empty($carouselTexts)) {
            foreach ($carouselTexts as $position => $texto) {
                // Limpar \n do texto
                $cleanedTexto = trim(preg_replace('/^\\n+|\\n+$/', '', trim($texto)));
                
                if (!empty($cleanedTexto) && $cleanedTexto !== '\n' && $position >= 1 && $position <= 10) {
                    CarouselText::create([
                        'social_post_id' => $socialPost->id,
                        'position' => $position,
                        'texto' => $cleanedTexto
                    ]);
                }
            }
        }
    }

    /**
     * Show the image generation page for the specified resource.
     */
    public function generateImages(SocialPost $socialPost)
    {
        // Verificar se o post pertence ao usuário
        if ($socialPost->user_id !== Auth::id()) {
            abort(403);
        }

        $socialPost->load(['hashtags', 'carouselTexts', 'backgroundColors']);
        
        // Carregar cores específicas do post atual
        $postColors = ImageBackgroundColor::where('user_id', Auth::id())
            ->where('post_id', $socialPost->id)
            ->orderBy('created_at', 'desc')
            ->get();
        
        // Carregar cores padrão do usuário
        $defaultColors = ImageBackgroundColor::where('user_id', Auth::id())
            ->where('is_default', true)
            ->orderBy('created_at', 'desc')
            ->get();
        
        // Carregar cores globais do usuário (sem post específico)
        $globalColors = ImageBackgroundColor::where('user_id', Auth::id())
            ->whereNull('post_id')
            ->where('is_default', false)
            ->orderBy('created_at', 'desc')
            ->get();
        
        // Combinar todas as cores, priorizando: post específico > padrão > global
        $savedColors = $postColors->concat($defaultColors)->concat($globalColors)->unique('id');
        
        // Cor padrão principal
        $defaultColor = $defaultColors->first();
        
        // Cor específica do post (a mais recente)
        $postColor = $postColors->first();
        
        return view('social-posts.generate-images', compact('socialPost', 'savedColors', 'defaultColor', 'postColor', 'postColors', 'defaultColors', 'globalColors'));
    }

    /**
     * Save a new background color for the user
     */
    public function saveBackgroundColor(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'color_name' => 'nullable|string|max:100',
            'color_hex' => 'required|string|regex:/^#[0-9A-Fa-f]{6}$/',
            'post_id' => 'nullable|exists:social_posts,id',
            'is_default' => 'boolean'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            $isDefault = $request->is_default ?? false;
            $userId = Auth::id();
            $colorHex = $request->color_hex;
            $postId = $request->post_id;
            $colorName = $request->color_name ?? $this->generateColorName($colorHex);

            // Verificar se já existe uma cor com o mesmo hex para este usuário no mesmo post
            $existingColor = ImageBackgroundColor::where('user_id', $userId)
                ->where('color_hex', $colorHex)
                ->where('post_id', $postId)
                ->first();

            if ($existingColor) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'Esta cor já foi salva para este post.'
                ], 422);
            }

            // Se esta cor será a padrão, remover padrão das outras
            if ($isDefault) {
                ImageBackgroundColor::where('user_id', $userId)
                    ->where('is_default', true)
                    ->update(['is_default' => false]);
            }

            // Verificar se já existe uma entrada com is_default=false para evitar problemas
            // Esta verificação adicional garante que não teremos problemas de constraint
            $existingDefaultFalse = ImageBackgroundColor::where('user_id', $userId)
                ->where('is_default', false)
                ->exists();

            // Se não é padrão e já existe uma entrada não-padrão, permitir múltiplas entradas
            // A nova estrutura permite múltiplas cores não-padrão por usuário

            $color = ImageBackgroundColor::create([
                'user_id' => $userId,
                'post_id' => $postId,
                'color_name' => $colorName,
                'color_hex' => $colorHex,
                'is_default' => $isDefault
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'color' => $color,
                'message' => 'Cor salva com sucesso!'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Erro ao salvar cor: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Set a color as default for the user
     */
    public function setDefaultColor(Request $request, $colorId)
    {
        try {
            DB::beginTransaction();

            $color = ImageBackgroundColor::where('id', $colorId)
                ->where('user_id', Auth::id())
                ->firstOrFail();

            // Remover padrão das outras cores
            ImageBackgroundColor::where('user_id', Auth::id())
                ->where('is_default', true)
                ->update(['is_default' => false]);

            // Definir esta como padrão
            $color->update(['is_default' => true]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Cor padrão definida com sucesso!'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Erro ao definir cor padrão: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Set a color from color picker as default for the user
     */
    public function setDefaultColorFromPicker(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'color_hex' => 'required|string|regex:/^#[0-9A-Fa-f]{6}$/',
            'post_id' => 'nullable|exists:social_posts,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            $userId = Auth::id();
            $colorHex = $request->color_hex;
            $postId = $request->post_id;

            // Verificar se já existe uma cor com o mesmo hex para este usuário no mesmo post
            $existingColor = ImageBackgroundColor::where('user_id', $userId)
                ->where('color_hex', $colorHex)
                ->where('post_id', $postId)
                ->first();

            if ($existingColor) {
                // Se a cor já existe, apenas definir como padrão
                ImageBackgroundColor::where('user_id', $userId)
                    ->where('is_default', true)
                    ->update(['is_default' => false]);

                $existingColor->update(['is_default' => true]);
            } else {
                // Se a cor não existe, criar e definir como padrão
                $colorName = $this->generateColorName($colorHex);

                // Remover padrão das outras cores
                ImageBackgroundColor::where('user_id', $userId)
                    ->where('is_default', true)
                    ->update(['is_default' => false]);

                ImageBackgroundColor::create([
                    'user_id' => $userId,
                    'post_id' => $postId,
                    'color_name' => $colorName,
                    'color_hex' => $colorHex,
                    'is_default' => true
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Cor definida como padrão com sucesso!'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Erro ao definir cor padrão: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete a background color
     */
    public function deleteBackgroundColor($colorId)
    {
        try {
            $color = ImageBackgroundColor::where('id', $colorId)
                ->where('user_id', Auth::id())
                ->firstOrFail();

            $color->delete();

            return response()->json([
                'success' => true,
                'message' => 'Cor excluída com sucesso!'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao excluir cor: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Generate automatic color name based on hex value
     */
    private function generateColorName($hexColor)
    {
        // Remove # if present
        $hex = ltrim($hexColor, '#');
        
        // Convert hex to RGB
        $r = hexdec(substr($hex, 0, 2));
        $g = hexdec(substr($hex, 2, 2));
        $b = hexdec(substr($hex, 4, 2));
        
        // Basic color mapping based on RGB values
        $colorNames = [
            // Reds
            ['rgb' => [255, 0, 0], 'name' => 'Vermelho'],
            ['rgb' => [220, 20, 60], 'name' => 'Carmesim'],
            ['rgb' => [255, 69, 0], 'name' => 'Laranja Avermelhado'],
            ['rgb' => [255, 99, 71], 'name' => 'Tomate'],
            ['rgb' => [255, 160, 122], 'name' => 'Salmão Claro'],
            
            // Blues
            ['rgb' => [0, 0, 255], 'name' => 'Azul'],
            ['rgb' => [0, 191, 255], 'name' => 'Azul Céu'],
            ['rgb' => [30, 144, 255], 'name' => 'Azul Dodger'],
            ['rgb' => [70, 130, 180], 'name' => 'Azul Aço'],
            ['rgb' => [176, 196, 222], 'name' => 'Azul Aço Claro'],
            
            // Greens
            ['rgb' => [0, 255, 0], 'name' => 'Verde'],
            ['rgb' => [0, 128, 0], 'name' => 'Verde Escuro'],
            ['rgb' => [34, 139, 34], 'name' => 'Verde Floresta'],
            ['rgb' => [144, 238, 144], 'name' => 'Verde Claro'],
            ['rgb' => [152, 251, 152], 'name' => 'Verde Pálido'],
            
            // Yellows
            ['rgb' => [255, 255, 0], 'name' => 'Amarelo'],
            ['rgb' => [255, 215, 0], 'name' => 'Dourado'],
            ['rgb' => [255, 255, 224], 'name' => 'Amarelo Claro'],
            ['rgb' => [255, 250, 205], 'name' => 'Amarelo Limão'],
            
            // Purples
            ['rgb' => [128, 0, 128], 'name' => 'Roxo'],
            ['rgb' => [75, 0, 130], 'name' => 'Índigo'],
            ['rgb' => [138, 43, 226], 'name' => 'Violeta Azul'],
            ['rgb' => [221, 160, 221], 'name' => 'Ameixa'],
            
            // Others
            ['rgb' => [255, 255, 255], 'name' => 'Branco'],
            ['rgb' => [0, 0, 0], 'name' => 'Preto'],
            ['rgb' => [128, 128, 128], 'name' => 'Cinza'],
            ['rgb' => [192, 192, 192], 'name' => 'Prata'],
            ['rgb' => [255, 192, 203], 'name' => 'Rosa'],
            ['rgb' => [255, 165, 0], 'name' => 'Laranja'],
            ['rgb' => [165, 42, 42], 'name' => 'Marrom']
        ];
        
        $closestColor = 'Cor Personalizada';
        $minDistance = PHP_INT_MAX;
        
        foreach ($colorNames as $colorData) {
            $rgb = $colorData['rgb'];
            $name = $colorData['name'];
            
            $distance = sqrt(
                pow($r - $rgb[0], 2) + 
                pow($g - $rgb[1], 2) + 
                pow($b - $rgb[2], 2)
            );
            
            if ($distance < $minDistance) {
                $minDistance = $distance;
                $closestColor = $name;
            }
        }
        
        // If the distance is too large, use a generic name with hex
        if ($minDistance > 100) {
            return 'Cor ' . strtoupper($hex);
        }
        
        return $closestColor;
    }

    /**
     * Convert Brazilian date format (dd/mm/yyyy) to database format (yyyy-mm-dd)
     */
    private function convertBrazilianDateToDatabase($dateString)
    {
        if (empty($dateString)) {
            return null;
        }

        // Se já está no formato do banco (yyyy-mm-dd), retorna como está
        if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $dateString)) {
            return $dateString;
        }

        // Se está no formato brasileiro (dd/mm/yyyy), converte
        if (preg_match('/^(\d{2})\/(\d{2})\/(\d{4})$/', $dateString, $matches)) {
            $day = $matches[1];
            $month = $matches[2];
            $year = $matches[3];
            
            // Validar se a data é válida
            if (checkdate($month, $day, $year)) {
                return $year . '-' . $month . '-' . $day;
            }
        }

        return null;
    }
}