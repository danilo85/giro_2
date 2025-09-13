<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Session;
use App\Models\UserLogo;
use Illuminate\Support\Facades\DB;
use App\Helpers\FileUploadHelper;

class ProfileController extends Controller
{
    /**
     * Update the user's profile information.
     */
    public function update(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'cpf_cnpj' => ['nullable', 'string', 'max:18'],
            'telefone_whatsapp' => ['nullable', 'string', 'max:20'],
            'email_extra' => ['nullable', 'email', 'max:255'],
            'biografia' => ['nullable', 'string', 'max:5000'],
            'profissao' => ['nullable', 'string', 'max:100'],
            'avatar' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
        ]);
        
        $data = $request->only(['name', 'email', 'cpf_cnpj', 'telefone_whatsapp', 'email_extra', 'biografia', 'profissao']);
        
        // Handle avatar upload
        if ($request->hasFile('avatar')) {
            // Delete old avatar if exists
            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }
            
            $avatarPath = FileUploadHelper::storeFile($request->file('avatar'), 'avatars');
            $data['avatar'] = $avatarPath;
        }
        
        $user->update($data);
        
        return back()->with('success', 'Perfil atualizado com sucesso!');
    }
    
    /**
     * Update the user's password.
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);
        
        Auth::user()->update([
            'password' => Hash::make($request->password),
        ]);
        
        return back()->with('success', 'Senha atualizada com sucesso!');
    }
    
    /**
     * Update the user's avatar.
     */
    public function updateAvatar(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'avatar' => ['required', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
        ]);
        
        // Delete old avatar if exists
        if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
            Storage::disk('public')->delete($user->avatar);
        }
        
        $avatarPath = FileUploadHelper::storeFile($request->file('avatar'), 'avatars');
        
        $user->update([
            'avatar' => $avatarPath
        ]);
        
        return back()->with('success', 'Avatar atualizado com sucesso!');
    }

    /**
     * Delete the user's account.
     */
    public function delete(Request $request)
    {
        $user = Auth::user();
        
        // Validate the current password
        $request->validate([
            'password' => ['required', 'current_password'],
        ], [
            'password.required' => 'A senha é obrigatória para deletar a conta.',
            'password.current_password' => 'A senha informada está incorreta.',
        ]);
        
        // Delete user's avatar if exists
        if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
            Storage::disk('public')->delete($user->avatar);
        }
        
        // Logout the user
        Auth::logout();
        
        // Invalidate the session
        Session::invalidate();
        Session::regenerateToken();
        
        // Delete the user account
        $user->delete();
        
        return redirect()->route('login')->with('success', 'Sua conta foi deletada com sucesso.');
    }

    /**
     * Upload logo da empresa.
     */
    public function uploadLogo(Request $request, $type)
    {
        $request->validate([
            'logo' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);

        // Validar tipo
        if (!in_array($type, ['horizontal', 'vertical', 'icone'])) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tipo de logo inválido.'
                ], 400);
            }
            return back()->with('error', 'Tipo de logo inválido.');
        }

        try {
            DB::beginTransaction();

            $user = auth()->user();

            // Remove logo existente do mesmo tipo
            $logoExistente = $user->logos()->where('tipo', $type)->first();
            if ($logoExistente) {
                Storage::disk('public')->delete($logoExistente->caminho);
                $logoExistente->delete();
            }

            // Upload do novo logo
            $file = $request->file('logo');
            $filename = time() . '_' . $type . '_' . $file->getClientOriginalName();
            $path = FileUploadHelper::storeFile($file, 'logos', $filename);

            // Salva no banco
            $user->logos()->create([
                'tipo' => $type,
                'caminho' => $path,
                'nome_original' => $file->getClientOriginalName()
            ]);

            DB::commit();

            // Se for requisição AJAX, retorna JSON
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Logo enviado com sucesso!',
                    'logo_url' => asset('storage/' . $path)
                ]);
            }

            // Se for requisição normal, redireciona de volta
            return back()->with('success', 'Logo enviado com sucesso!');

        } catch (\Exception $e) {
            DB::rollBack();
            
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erro ao enviar logo: ' . $e->getMessage()
                ], 500);
            }
            
            return back()->with('error', 'Erro ao enviar logo: ' . $e->getMessage());
        }
    }
    
    /**
     * Upload assinatura digital.
     */
    public function uploadSignature(Request $request)
    {
        \Log::info('=== UPLOAD SIGNATURE INICIADO ===', [
            'user_id' => auth()->id(),
            'method' => $request->method(),
            'url' => $request->url(),
            'request_data' => $request->all(),
            'files' => $request->allFiles(),
            'has_file' => $request->hasFile('assinatura')
        ]);
        
        $user = Auth::user();
        
        $assinaturaConfig = config('upload.assinaturas');
        
        // Log da configuração
        \Log::info('Upload Signature - Configuração', [
            'config_exists' => !is_null($assinaturaConfig),
            'has_validation_rules' => isset($assinaturaConfig['validation_rules']),
            'config' => $assinaturaConfig
        ]);
        
        // Verificar se o arquivo foi enviado
        if (!$request->hasFile('assinatura')) {
            \Log::error('Upload Signature - Nenhum arquivo enviado');
            return back()->with('error', 'Nenhum arquivo foi enviado.');
        }
        
        // Verificar se assinaturaConfig existe e tem validation_rules
        if (!$assinaturaConfig || !isset($assinaturaConfig['validation_rules'])) {
            \Log::error('Upload Signature - Configuração não encontrada', [
                'config_exists' => !is_null($assinaturaConfig),
                'has_validation_rules' => isset($assinaturaConfig['validation_rules'])
            ]);
            return back()->with('error', 'Configuração de upload não encontrada.');
        }
        
        $request->validate([
            'assinatura' => $assinaturaConfig['validation_rules']
        ]);
        
        // Deletar assinatura existente
        if ($user->assinatura_digital && Storage::disk('public')->exists($user->assinatura_digital)) {
            Storage::disk('public')->delete($user->assinatura_digital);
        }
        
        // Store the signature file
        $signaturePath = FileUploadHelper::storeFile($request->file('assinatura'), 'assinaturas');
        
        \Log::info('Upload Signature - Sucesso', [
            'user_id' => $user->id,
            'path' => $signaturePath
        ]);
        
        $user->update([
            'assinatura_digital' => $signaturePath
        ]);
        
        // Verificar se é uma requisição AJAX
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Assinatura digital atualizada com sucesso!',
                'signature_url' => Storage::url($signaturePath)
            ]);
        }
        
        return back()->with('success', 'Assinatura digital atualizada com sucesso!');
    }
    
    /**
     * Deletar logo específico.
     */
    public function deleteLogo(Request $request, $type)
    {
        // Validar tipo
        if (!in_array($type, ['horizontal', 'vertical', 'icone'])) {
            return response()->json([
                'success' => false,
                'message' => 'Tipo de logo inválido.'
            ], 400);
        }

        try {
            $user = auth()->user();

            $logo = $user->logos()->where('tipo', $type)->first();

            if (!$logo) {
                return response()->json([
                    'success' => false,
                    'message' => 'Logo não encontrado.'
                ], 404);
            }

            // Remove arquivo do storage
            Storage::disk('public')->delete($logo->caminho);

            // Remove registro do banco
            $logo->delete();

            return response()->json([
                'success' => true,
                'message' => 'Logo removido com sucesso!'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao remover logo: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Deletar assinatura digital.
     */
    public function deleteSignature()
    {
        $user = Auth::user();
        
        if ($user->assinatura_digital) {
            if (Storage::disk('public')->exists($user->assinatura_digital)) {
                Storage::disk('public')->delete($user->assinatura_digital);
            }
            
            $user->update([
                'assinatura_digital' => null
            ]);
            
            return back()->with('success', 'Assinatura digital removida com sucesso!');
        }
        
        return back()->with('error', 'Nenhuma assinatura digital encontrada.');
    }

    /**
     * Update the user's social media information.
     */
    public function updateSocialMedia(Request $request)
    {
        $request->validate([
            'instagram' => 'nullable|url',
            'facebook' => 'nullable|url',
            'linkedin' => 'nullable|url',
            'twitter' => 'nullable|url',
            'youtube' => 'nullable|url',
            'tiktok' => 'nullable|url',
            'whatsapp' => 'nullable|url',
            'website' => 'nullable|url',
        ]);

        $user = auth()->user();
        
        // Mapear os campos do formulário para os campos do banco
        $socialMediaFields = [
            'instagram' => 'instagram_url',
            'facebook' => 'facebook_url',
            'linkedin' => 'linkedin_url',
            'twitter' => 'twitter_url',
            'youtube' => 'youtube_url',
            'tiktok' => 'tiktok_url',
            'whatsapp' => 'whatsapp_url',
            'website' => 'website_url',
        ];
        
        foreach ($socialMediaFields as $formField => $dbField) {
            if ($request->has($formField)) {
                $user->$dbField = $request->$formField;
            }
        }
        
        $user->save();

        // Verificar se é uma requisição AJAX
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Redes sociais atualizadas com sucesso!'
            ]);
        }

        return redirect()->back()->with('success', 'Redes sociais atualizadas com sucesso!');
    }
    
    /**
     * Delete a specific social media platform.
     */
    public function deleteSocialMedia(Request $request, $platform)
    {
        $user = Auth::user();
        
        $allowedPlatforms = [
            'facebook' => 'facebook_url',
            'instagram' => 'instagram_url',
            'twitter' => 'twitter_url',
            'linkedin' => 'linkedin_url',
            'youtube' => 'youtube_url',
            'tiktok' => 'tiktok_url',
            'whatsapp' => 'whatsapp_url',
            'website' => 'website_url'
        ];
        
        if (!array_key_exists($platform, $allowedPlatforms)) {
            return back()->with('error', 'Plataforma não reconhecida.');
        }
        
        $field = $allowedPlatforms[$platform];
        
        $user->update([$field => null]);
        
        $platformName = ucfirst($platform);
        if ($platform === 'website') {
            $platformName = 'Website';
        }
        
        return back()->with('success', $platformName . ' removido com sucesso!');
    }

    /**
     * Upload imagem de rodapé.
     */
    public function uploadRodapeImage(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'rodape_image' => ['required', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
        ]);
        
        // Deletar imagem existente
        if ($user->rodape_image && Storage::disk('public')->exists($user->rodape_image)) {
            Storage::disk('public')->delete($user->rodape_image);
        }
        
        // Store the rodape image file
        $rodapePath = FileUploadHelper::storeFile($request->file('rodape_image'), 'rodape');
        
        $user->update([
            'rodape_image' => $rodapePath
        ]);
        
        // Verificar se é uma requisição AJAX
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Imagem de rodapé atualizada com sucesso!',
                'image_url' => Storage::url($rodapePath)
            ]);
        }
        
        return back()->with('success', 'Imagem de rodapé atualizada com sucesso!');
    }

    /**
     * Upload imagem de QR code.
     */
    public function uploadQrcodeImage(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'qrcode_image' => ['required', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
        ]);
        
        // Deletar imagem existente
        if ($user->qrcode_image && Storage::disk('public')->exists($user->qrcode_image)) {
            Storage::disk('public')->delete($user->qrcode_image);
        }
        
        // Store the qrcode image file
        $qrcodePath = FileUploadHelper::storeFile($request->file('qrcode_image'), 'qrcode');
        
        $user->update([
            'qrcode_image' => $qrcodePath
        ]);
        
        // Verificar se é uma requisição AJAX
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Imagem de QR code atualizada com sucesso!',
                'image_url' => Storage::url($qrcodePath)
            ]);
        }
        
        return back()->with('success', 'Imagem de QR code atualizada com sucesso!');
    }

    /**
     * Deletar imagem de rodapé.
     */
    public function deleteRodapeImage()
    {
        $user = Auth::user();
        
        if ($user->rodape_image) {
            if (Storage::disk('public')->exists($user->rodape_image)) {
                Storage::disk('public')->delete($user->rodape_image);
            }
            
            $user->update([
                'rodape_image' => null
            ]);
            
            return back()->with('success', 'Imagem de rodapé removida com sucesso!');
        }
        
        return back()->with('error', 'Nenhuma imagem de rodapé encontrada.');
    }

    /**
     * Deletar imagem de QR code.
     */
    public function deleteQrcodeImage()
    {
        $user = Auth::user();
        
        if ($user->qrcode_image) {
            if (Storage::disk('public')->exists($user->qrcode_image)) {
                Storage::disk('public')->delete($user->qrcode_image);
            }
            
            $user->update([
                'qrcode_image' => null
            ]);
            
            return back()->with('success', 'Imagem de QR code removida com sucesso!');
        }
        
        return back()->with('error', 'Nenhuma imagem de QR code encontrada.');
    }
}