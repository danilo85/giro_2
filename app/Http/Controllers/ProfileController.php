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
            'avatar' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
        ]);
        
        $data = $request->only(['name', 'email', 'cpf_cnpj', 'telefone_whatsapp', 'email_extra', 'biografia']);
        
        // Handle avatar upload
        if ($request->hasFile('avatar')) {
            // Delete old avatar if exists
            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }
            
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
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
        
        $avatarPath = $request->file('avatar')->store('avatars', 'public');
        
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
    public function uploadLogo(Request $request)
    {
        \Log::info('=== UPLOAD LOGO INICIADO ===', [
            'user_id' => auth()->id(),
            'method' => $request->method(),
            'url' => $request->url(),
            'request_data' => $request->all(),
            'files' => $request->allFiles(),
            'has_files' => $request->hasFile('logo_horizontal') || $request->hasFile('logo_vertical') || $request->hasFile('logo_icone')
        ]);
        
        $user = Auth::user();
        
        $logoConfig = config('upload.logos');
        
        // Verificar se logoConfig existe e tem validation_rules
        if (!$logoConfig || !isset($logoConfig['validation_rules'])) {
            return back()->with('error', 'Configuração de upload não encontrada.');
        }
        
        // Verificar quais arquivos foram enviados
        $uploadedLogos = [];
        $logoTypes = ['horizontal', 'vertical', 'icone'];
        
        foreach ($logoTypes as $type) {
            $fieldName = 'logo_' . $type;
            if ($request->hasFile($fieldName)) {
                $uploadedLogos[$type] = $request->file($fieldName);
            }
        }
        
        if (empty($uploadedLogos)) {
            return back()->with('error', 'Nenhum arquivo foi enviado.');
        }
        
        // Validar cada arquivo enviado
        $validationRules = [];
        foreach ($uploadedLogos as $type => $file) {
            $fieldName = 'logo_' . $type;
            $validationRules[$fieldName] = $logoConfig['validation_rules'];
        }
        
        $request->validate($validationRules);
        
        $successMessages = [];
        
        DB::transaction(function () use ($request, $user, $uploadedLogos, &$successMessages) {
            foreach ($uploadedLogos as $type => $file) {
                // Deletar logo existente do mesmo tipo
                $existingLogo = $user->logos()->where('tipo', $type)->first();
                if ($existingLogo) {
                    if (Storage::disk('public')->exists($existingLogo->caminho)) {
                        Storage::disk('public')->delete($existingLogo->caminho);
                    }
                    $existingLogo->delete();
                }
                
                // Store the logo file
                $logoPath = $file->store('logos', 'public');
                
                UserLogo::create([
                    'user_id' => $user->id,
                    'tipo' => $type,
                    'caminho' => $logoPath,
                    'nome_original' => $file->getClientOriginalName(),
                ]);
                
                $successMessages[] = 'Logo ' . $type . ' atualizado';
            }
        });
        
        $message = implode(', ', $successMessages) . ' com sucesso!';
        return back()->with('success', $message);
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
            'has_file' => $request->hasFile('signature')
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
        $signaturePath = $request->file('assinatura')->store('assinaturas', 'public');
        
        \Log::info('Upload Signature - Sucesso', [
            'user_id' => $user->id,
            'path' => $signaturePath
        ]);
        
        $user->update([
            'assinatura_digital' => $signaturePath
        ]);
        
        return back()->with('success', 'Assinatura digital atualizada com sucesso!');
    }
    
    /**
     * Deletar logo específico.
     */
    public function deleteLogo(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'tipo' => ['required', 'in:horizontal,vertical,icone'],
        ]);
        
        $logo = $user->logos()->where('tipo', $request->tipo)->first();
        
        if ($logo) {
            if (Storage::disk('public')->exists($logo->caminho)) {
                Storage::disk('public')->delete($logo->caminho);
            }
            $logo->delete();
            
            return back()->with('success', 'Logo ' . $request->tipo . ' removido com sucesso!');
        }
        
        return back()->with('error', 'Logo não encontrado.');
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
        $user = Auth::user();
        
        // Validação dos URLs das redes sociais
        $request->validate([
            'facebook_url' => ['nullable', 'url', 'regex:/^https?:\/\/(www\.)?facebook\.com\/.*/'],
            'instagram_url' => ['nullable', 'url', 'regex:/^https?:\/\/(www\.)?instagram\.com\/.*/'],
            'twitter_url' => ['nullable', 'url', 'regex:/^https?:\/\/(www\.)?(twitter|x)\.com\/.*/'],
            'linkedin_url' => ['nullable', 'url', 'regex:/^https?:\/\/(www\.)?linkedin\.com\/.*/'],
            'youtube_url' => ['nullable', 'url', 'regex:/^https?:\/\/(www\.)?youtube\.com\/.*/'],
            'tiktok_url' => ['nullable', 'url', 'regex:/^https?:\/\/(www\.)?tiktok\.com\/.*/'],
            'whatsapp_url' => ['nullable', 'url', 'regex:/^https?:\/\/(wa\.me|api\.whatsapp\.com)\/.*/'],
            'website_url' => ['nullable', 'url'],
        ], [
            '*.url' => 'O campo deve ser uma URL válida.',
            'facebook_url.regex' => 'A URL do Facebook deve ser válida.',
            'instagram_url.regex' => 'A URL do Instagram deve ser válida.',
            'twitter_url.regex' => 'A URL do Twitter/X deve ser válida.',
            'linkedin_url.regex' => 'A URL do LinkedIn deve ser válida.',
            'youtube_url.regex' => 'A URL do YouTube deve ser válida.',
            'tiktok_url.regex' => 'A URL do TikTok deve ser válida.',
            'whatsapp_url.regex' => 'A URL do WhatsApp deve ser válida.',
        ]);
        
        $socialMediaData = $request->only([
            'facebook_url',
            'instagram_url', 
            'twitter_url',
            'linkedin_url',
            'youtube_url',
            'tiktok_url',
            'whatsapp_url',
            'website_url'
        ]);
        
        $user->update($socialMediaData);
        
        return back()->with('success', 'Redes sociais atualizadas com sucesso!');
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
}