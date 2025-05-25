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

    public function ___construct()
    {
        $this->middleware('auth:web')->except('register');
    }



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

    public function showForgotForm(){
    return view('auth.forgot-password');
}

    public function sendResetLink(Request $request){
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

    public function showResetForm($token){
        try {
            $data = json_decode(Crypt::decryptString($token), true);
        } catch (\Exception $e) {
            return redirect()->route('password.request')->withErrors(['token' => 'Invalid or expired reset link.']);
        }

        return view('auth.reset-password', ['token' => $token, 'email' => $data['email']]);
    }

    public function resetPassword(Request $request){
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
############################################################################################################

public function list(Request $request)
{
    if (!auth()->user()->hasPermissionTo('view_users'))
        abort(401);

    $query = User::select('*');

    if (auth()->user()->hasRole('Manager')) {
        $query->whereDoesntHave('roles', function ($q) {
            $q->where('name', 'Admin');
        });
    }

    $query->when($request->keywords, function($q) use ($request) {
        $q->where(function($query) use ($request) {
            $query->where('name', 'like', "%{$request->keywords}%")
                  ->orWhere('email', 'like', "%{$request->keywords}%");
        });
    });

    $query->when($request->role, function($q) use ($request) {
        $q->whereHas('roles', function($query) use ($request) {
            $query->where('name', $request->role);
        });
    });

    $users = $query->paginate(10)->withQueryString();
    $roles = Role::all();

    return view('users.list', compact('users', 'roles'));
}



    public function createRoll(){
    $roles = Role::all();
    return view('users.create', compact('roles'));
}




    public function edit(Request $request, User $user = null){
        $user = $user ?? auth()->user();
        if(auth()->id()!=$user?->id) {
            if(!auth()->user()->hasPermissionTo('edit_users')) abort(401);
        }


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


    public function save(Request $request, User $user){
        if(auth()->id()!=$user->id) {
            if(!auth()->user()->hasPermissionTo('view_users')) abort(401);
        }

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'address' => ['nullable', 'string', 'max:1000'],
            'roles' => ['array'],
            'permissions' => ['array'],
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'address' => $request->address,
        ]);
        
        if (auth()->user()->hasAnyRole(['Admin', 'Manager'])) {
            $user->syncRoles($request->roles);
            $user->syncPermissions($request->permissions);
        }


        return redirect()->route('profile', ['user' => $user->id])->with('success', 'Profile updated successfully.');
    }

    public function delete(Request $request, User $user){
        if (!auth()->user()->hasPermissionTo('delete_users'))
            abort(401);
        $user->delete();

        return redirect()->route('users.list')->with('success', 'User deleted successfully.');
    }
##########################################################################################
    public function editPassword(Request $request, User $user = null){
        $user = $user ?? auth()->user();
        if (auth()->id() != $user?->id) {
            if (!auth()->user()->hasPermissionTo('change_password'))
                abort(401);
        }

        return view('users.edit_password', compact('user'));
    }

    public function savePassword(Request $request, User $user){
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

    public function profile(Request $request, User $user = null){
        $user = $user ?? auth()->user();
        if (auth()->id() != $user->id) {
            if (!auth()->user()->hasPermissionTo('view_users'))
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








}


