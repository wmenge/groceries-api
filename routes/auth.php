<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
 
// TODO: Move to controller, cleanup other auth mechanisms
Route::get('/auth/github/redirect', function () {
    return Socialite::driver('github')->redirect();
});
 
Route::get('/auth/github/callback', function () {
    
    $githubUser = Socialite::driver('github')->user();

    // TODO: Only create if there sis an empty user with email as a sort of invite
    $user = User::updateOrCreate([
        'github_id' => $githubUser->id,
    ], [
        'name' => $githubUser->name,
        'email' => $githubUser->email,
        'github_token' => $githubUser->token,
        'github_refresh_token' => $githubUser->refreshToken,
    ]);

    Auth::login($user);
 
    // TODO Get from env
    return redirect('http://localhost:5173/');
});