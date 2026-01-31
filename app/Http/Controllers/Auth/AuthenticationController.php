<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Laravel\Socialite\Facades\Socialite;

class AuthenticationController
{
    private $providers = ['google', 'github'];
    
    public function redirect(string $provider) {
        if (!in_array($provider, $this->providers)) return;

        return Socialite::driver($provider)->redirect();
    }

    public function callback(string $provider) {
        if (!in_array($provider, $this->providers)) return;

        $socialiteUser = Socialite::driver($provider)->user();

        $user = User::where('socialite_provider', $provider)->where('socialite_id', $socialiteUser->id)->first();

        // Else, check if a dummy user with email is there (as a sort of invite)
        if (!$user) {
            $user = User::where('email', $socialiteUser->email)->first();
        }

        if ($user) {
            $user->socialite_provider = $provider;
            $user->socialite_id = $socialiteUser->id;
            $user->name = $socialiteUser->name;
            $user->email = $socialiteUser->email;
            $user->socialite_token = $socialiteUser->token;
            $user->socialite_refresh_token = $socialiteUser->refreshToken;
            $user->save();       
    
            Auth::login($user); 
        }
        
        return redirect(env('FRONTEND_URL', 'http://localhost:5173/'));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        //return response()->noContent();
        return redirect(env('FRONTEND_URL', 'http://localhost:5173/'));
    }
    
}