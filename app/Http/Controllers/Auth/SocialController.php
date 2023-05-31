<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Gravatar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class SocialController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')
            ->scopes([
                'email',
                'https://www.googleapis.com/auth/userinfo.profile',
                'https://www.googleapis.com/auth/gmail.readonly',
                'https://www.googleapis.com/auth/gmail.send',
            ])
            ->redirect();
    }

    public function handleGoogleCallback(Request $request)
    {
        /** @var \Laravel\Socialite\Two\User $user */
        $user = Socialite::driver('google')->user();

        $userModel = User::updateOrCreate([
            'email' => $user->getEmail(),
        ], [
            'openid' => $user->id,
            'name' => $user->getName(),
            'avatar' => $user->getAvatar() ?: Gravatar::get($user->getEmail()),
            'google_token' => $user->token,
            'password' => Hash::make(Str::random(32)), // Not used for anything
        ]);

        Auth::login($userModel);

        return redirect()->route('dashboard');
    }
}
