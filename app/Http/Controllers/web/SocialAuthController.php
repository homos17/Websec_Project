<?php
namespace App\Http\Controllers\Web;
use App\Http\Controllers\Controller;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;


class SocialAuthController extends Controller
{
    public function redirectToFacebook()
    {
        return Socialite::driver('facebook')->redirect();
    }

    public function handleFacebookCallback()
    {
        try {
            $facebookUser = Socialite::driver('facebook')->stateless()->user();

            $user = User::firstOrCreate(
                ['email' => $facebookUser->getEmail()],
                [
                    'name' => $facebookUser->getName(),
                    'password' => bcrypt('facebook_dummy_password'), // or null
                ]
            );

            Auth::login($user);

            return redirect()->route('/'); // change this to your landing route

        } catch (\Exception $e) {
            return redirect()->route('login')->withErrors(['msg' => 'Failed to login with Facebook.']);
        }
    }

    public function redirectToGithub()
    {
        return Socialite::driver('github')->redirect();
    }

    public function handleGithubCallback()
    {
        try {
            $githubUser = Socialite::driver('github')->stateless()->user();

            $user = User::where('email', $githubUser->getEmail())->first();

            if (!$user) {
                $user = User::create([
                    'name' => $githubUser->getName() ?? $githubUser->getNickname(),
                    'email' => $githubUser->getEmail(),
                    'email_verified_at' => now(),
                    'password' => bcrypt(uniqid()),
                ]);
                $user->assignRole('customer');
            }

            Auth::login($user);

            return redirect('/')->with('success', 'Logged in successfully using GitHub!');
        } catch (\Exception $e) {
            return redirect('/login')->withErrors('Unable to login using GitHub.');
        }
    }

    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            $user = User::where('email', $googleUser->getEmail())->first();

            if (!$user) {
                $user = User::create([
                    'name' => $googleUser->getName(),
                    'email' => $googleUser->getEmail(),
                    'email_verified_at' => now(),
                    'password' => bcrypt(uniqid()),
                ]);
                $user->assignRole('customer');
            }

            Auth::login($user);
            return redirect('/');
        } catch (\Exception $e) {
            return redirect('/login')->withErrors('Unable to login using Google.');
        }
    }


}
