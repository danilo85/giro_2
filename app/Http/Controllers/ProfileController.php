<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Session;

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
            'avatar' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
        ]);
        
        $data = $request->only(['name', 'email']);
        
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
}