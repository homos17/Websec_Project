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

    
}
