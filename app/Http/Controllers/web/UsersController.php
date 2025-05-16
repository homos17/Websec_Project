<?php

namespace App\Http\Controllers\Web;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Validation\Rules\Password;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Mail\VerificationEmail;
use App\Mail\PasswordResetEmail;

class UsersController extends Controller
{
    use ValidatesRequests;



    public function register(Request $request)
    {
        return view('users.register');
    }



    public function doRegister(Request $request)
    {
        try {
            $this->validate($request, [
                'name' => ['required', 'string', 'min:5'],
                'email' => ['required', 'email', 'unique:users'],
                'password' => ['required', 'confirmed', Password::min(8)->numbers()->letters()->mixedCase()->symbols()],
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->withInput($request->input())->withErrors('Invalid registration information.');
        }

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->assignRole('customer');
        $user->save();

        $title = "Verification Link";
        $token = Crypt::encryptString(json_encode(['id' => $user->id, 'email' => $user->email]));
        $link = url("/verify?token=$token");
        Mail::to($user->email)->send(new VerificationEmail($link, $user->name));

        return redirect('/');
    }


    public function login(Request $request)
    {
        return view('users.login');
    }

    public function doLogin(Request $request)
    {
        if (!Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            return redirect()->back()->withInput($request->input())->withErrors('Invalid login information.');
        }

        $user = User::where('email', $request->email)->first();

        if (!$user->email_verified_at) {
            Auth::logout();
            return redirect()->back()
                ->withInput($request->input())
                ->withErrors('Your email is not verified.');
        }

        Auth::setUser($user);

        return redirect('/');
    }


    public function doLogout(Request $request)
    {
        Auth::logout();
        return redirect('/');
    }


    public function verify(Request $request)
    {
        try {
            $decryptedData = json_decode(Crypt::decryptString($request->token), true);
        } catch (\Exception $e) {
            return redirect('/')->withErrors('Invalid or expired verification link.');
        }

        $user = User::find($decryptedData['id']);

        if (!$user) {
            return redirect('/')->withErrors('User not found.');
        }

        if (!$user->email_verified_at) {
            $user->email_verified_at = Carbon::now();
            $user->save();
        }

        return view('users.verified', compact('user'));
    }

    public function showForgotForm()
{
    return view('auth.forgot-password');
}

public function sendResetLink(Request $request)
{
    $request->validate([
        'email' => 'required|email|exists:users,email',
    ]);

    $user = User::where('email', $request->email)->first();

    $tokenData = [
        'id' => $user->id,
        'email' => $user->email,
        'timestamp' => now()->timestamp,
    ];

    $token = Crypt::encryptString(json_encode($tokenData));
    $resetLink = route('password.reset', ['token' => $token]);

    Mail::to($user->email)->send(new PasswordResetEmail($resetLink));

    return back()->with('success', 'We sent a password reset link to your email.');
}

public function showResetForm($token)
{
    try {
        $data = json_decode(Crypt::decryptString($token), true);
    } catch (\Exception $e) {
        return redirect()->route('password.request')->withErrors(['token' => 'Invalid or expired reset link.']);
    }

    return view('auth.reset-password', ['token' => $token, 'email' => $data['email']]);
}

public function resetPassword(Request $request)
{
    $request->validate([
        'token' => 'required',
        'password' => 'required|min:6|confirmed',
    ]);

    try {
        $data = json_decode(Crypt::decryptString($request->token), true);
    } catch (\Exception $e) {
        return back()->withErrors(['token' => 'Invalid or expired token.']);
    }

    $user = User::where('id', $data['id'])->where('email', $data['email'])->firstOrFail();
    $user->password = Hash::make($request->password);
    $user->save();

    return redirect()->route('login')->with('success', 'Your password has been reset successfully!');
}



}


