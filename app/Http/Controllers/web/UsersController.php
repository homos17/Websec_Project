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

    public function profile(Request $request, User $user = null)
    {
        $user = $user ?? auth()->user();
        if (auth()->id() != $user->id) {
            if (!auth()->user()->hasPermissionTo('show_users'))
                abort(401);
        }

        $permissions = [];
        foreach ($user->permissions as $permission) {
            $permissions[] = $permission;
        }
        foreach ($user->roles as $role) {
            foreach ($role->permissions as $permission) {
                $permissions[] = $permission;
            }
        }

        return view('users.profile', compact('user', 'permissions'));
    }

    public function edit(Request $request, User $user = null)
    {
        $user = $user ?? auth()->user();


        $roles = [];
        foreach (Role::all() as $role) {
            $role->taken = ($user->hasRole($role->name));
            $roles[] = $role;
        }

        $permissions = [];
        $directPermissionsIds = $user->permissions()->pluck('id')->toArray();
        foreach (Permission::all() as $permission) {
            $permission->taken = in_array($permission->id, $directPermissionsIds);
            $permissions[] = $permission;
        }

        return view('users.edit', compact('user', 'roles', 'permissions'));
    }

    public function save(Request $request, User $user)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'roles' => ['array'],
            'permissions' => ['array'],
            'credit' => ['nullable', 'numeric', 'min:0']
        ]);

        $user->update([
            'name' => $request->name,
            'credit' => $request->credit ?? $user->credit,
        ]);

        if (auth()->user()->hasRole('Admin')) {
            $user->syncRoles($request->roles);
            $user->syncPermissions($request->permissions);
        }

        return redirect()->route('users')->with('success', 'User updated successfully.');
    }

    public function delete(Request $request, User $user)
    {
        if (!auth()->user()->hasPermissionTo('delete_users'))
            abort(401);
        $user->delete();
        
        return redirect()->route('users');
    }

    public function editPassword(Request $request, User $user = null)
    {
        $user = $user ?? auth()->user();
        if (auth()->id() != $user?->id) {
            if (!auth()->user()->hasPermissionTo('edit_users'))
                abort(401);
        }

        return view('users.edit_password', compact('user'));
    }

    public function savePassword(Request $request, User $user)
    {
        if (auth()->id() == $user?->id) {
            $this->validate($request, [
                'password' => ['required', 'confirmed', Password::min(8)->numbers()->letters()->mixedCase()->symbols()],
            ]);

            if (!Auth::attempt(['email' => $user->email, 'password' => $request->old_password])) {
                Auth::logout();
                return redirect('/');
            }
        } else if (!auth()->user()->hasPermissionTo('edit_users')) {
            abort(401);
        }

        $user->password = bcrypt($request->password);
        $user->save();

        return redirect(route('profile', ['user' => $user->id]));
    }


    public function showUsersByRole(Request $request)
    {
        if (!auth()->user()->hasPermissionTo('show_users'))
            abort(403);

        $role = $request->get('role');

        $query = User::query();

        if ($role) {
            $query->role($role);
        }

        $users = $query->get();

        return view('users.by_role', compact('users', 'role'));
    }

    public function showCredit()
    {
        $user = auth()->user();
        return view('users.credit', compact('user'));
    }

    public function create()
    {
        $roles = Role::all();
        return view('users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|confirmed|min:6',
            'roles' => 'required|array',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'credit' => 0, // default credit
        ]);

        $user->syncRoles($request->roles);

        return redirect()->route('users')->with('success', 'User created successfully.');
    }

    public function listCustomers()
    {
        $customers = User::role('Customer')->get();

        return view('users.list-customer', compact('customers'));
    }

    public function chargeCredit(Request $request, $id)
    {
        $validated = $request->validate([
            'credit' => 'required|numeric|min:0.01',
        ]);

        $customer = User::findOrFail($id);

        $customer->credit += $validated['credit'];
        $customer->save();

        return redirect()->route('customers.list')->with('success', 'Credit charged successfully.');
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
}


