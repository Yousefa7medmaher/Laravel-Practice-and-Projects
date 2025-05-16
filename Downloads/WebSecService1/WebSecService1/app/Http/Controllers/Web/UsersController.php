<?php
namespace App\Http\Controllers\Web;

use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Validation\Rules\Password;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use DB;
use Artisan;

use App\Http\Controllers\Controller;
use App\Models\User;

class UsersController extends Controller {

	use ValidatesRequests;

    public function list(Request $request) {
        if(!auth()->user()->hasRole('admin')) {
            abort(403, 'Unauthorized action.');
        }

        $query = User::select('*');
        if ($request->keywords) {
            $query->where("name", "like", "%$request->keywords%");
        }
        $users = $query->get();
        return view('users.list', compact('users'));
    }

	public function register(Request $request) {
        return view('users.register');
    }

    public function doRegister(Request $request) {
        $validated = $request->validate([
            'name' => ['required', 'string', 'min:5'],
            'email' => ['required', 'email', 'unique:users'],
            'password' => ['required', 'confirmed', Password::min(8)->numbers()->letters()->mixedCase()->symbols()],
        ]);

        $user = new User();
        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->password = bcrypt($validated['password']);
        $user->save();

        // Assign customer role by default
        $user->assignRole('customer');

        // Log the user in
        Auth::login($user);

        // Send verification email
        $user->sendEmailVerificationNotification();

        return redirect()->route('verification.notice')
            ->with('success', 'Registration successful! Please check your email for verification link.');
    }

    public function login(Request $request) {
        return view('users.login');
    }

    public function doLogin(Request $request) {
        if(!Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            return redirect()->back()
                ->withInput($request->input())
                ->withErrors('Invalid login information.');
        }

        $user = User::where('email', $request->email)->first();
        Auth::setUser($user);

        return redirect('/')->with('success', 'Welcome back!');
    }

    public function doLogout(Request $request) {
        Auth::logout();
        return redirect('/')->with('success', 'You have been logged out.');
    }

    public function profile(Request $request, User $user = null) {
        $user = $user ?? auth()->user();

        // Only allow viewing if it's the user's own profile, if they're an admin, or if they're an employee viewing a customer
        if (auth()->id() != $user->id &&
            !auth()->user()->hasRole('admin') &&
            !(auth()->user()->hasRole('employee') && $user->hasRole('customer'))) {
            abort(403, 'Unauthorized action.');
        }

        $permissions = [];
        foreach($user->getAllPermissions() as $permission) {
            $permissions[] = $permission;
        }

        return view('users.profile', compact('user', 'permissions'));
    }

    public function edit(Request $request, User $user = null) {
        $user = $user ?? auth()->user();

        // Only allow editing if it's the user's own profile, if they're an admin, or if they're an employee editing a customer
        if (auth()->id() != $user->id &&
            !auth()->user()->hasRole('admin') &&
            !(auth()->user()->hasRole('employee') && $user->hasRole('customer'))) {
            abort(403, 'Unauthorized action.');
        }

        $roles = [];
        // Only show roles management to admins
        if (auth()->user()->hasRole('admin')) {
            foreach(Role::all() as $role) {
                $role->taken = $user->hasRole($role->name);
                $roles[] = $role;
            }
        }

        $permissions = [];
        // Only show permissions management to admins
        if (auth()->user()->hasRole('admin')) {
            $directPermissionsIds = $user->permissions()->pluck('id')->toArray();
            foreach(Permission::all() as $permission) {
                $permission->taken = in_array($permission->id, $directPermissionsIds);
                $permissions[] = $permission;
            }
        }

        return view('users.edit', compact('user', 'roles', 'permissions'));
    }

    public function save(Request $request, User $user = null) {
        $user = $user ?? auth()->user();

        // Only allow saving if it's the user's own profile, if they're an admin, or if they're an employee modifying a customer
        if (auth()->id() != $user->id &&
            !auth()->user()->hasRole('admin') &&
            !(auth()->user()->hasRole('employee') && $user->hasRole('customer'))) {
            abort(403, 'Unauthorized action.');
        }

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $user->name = $data['name'];
        $user->email = $data['email'];

        // Handle profile image upload
        if ($request->hasFile('profile_image')) {
            // Delete old image if exists
            if ($user->profile_image && Storage::disk('public')->exists($user->profile_image)) {
                Storage::disk('public')->delete($user->profile_image);
            }

            // Store the new image
            $imagePath = $request->file('profile_image')->store('profile-images', 'public');
            $user->profile_image = $imagePath;
        }

        // Only admins can modify roles and permissions
        if (auth()->user()->hasRole('admin')) {
            $roles = $request->input('roles', []);
            $permissions = $request->input('permissions', []);
            $user->syncRoles($roles);
            $user->syncPermissions($permissions);
        }

        $user->save();

        return redirect()->route('users.profile', $user)->with('success', 'User updated successfully');
    }

    public function delete(Request $request, User $user) {
        // Only allow deletion if they're an admin, or if they're an employee deleting a customer
        if (!auth()->user()->hasRole('admin') &&
            !(auth()->user()->hasRole('employee') && $user->hasRole('customer'))) {
            abort(403, 'Unauthorized action.');
        }

        // Prevent deletion of admin users by employees
        if ($user->hasRole('admin') && !auth()->user()->hasRole('admin')) {
            abort(403, 'Unauthorized action.');
        }

        $user->delete();
        return redirect()->route('users.list')->with('success', 'User deleted successfully');
    }

    public function editPassword(Request $request, User $user = null) {
        $user = $user ?? auth()->user();

        // Only allow password editing if it's the user's own profile or if they're an admin
        if (auth()->id() != $user->id && !auth()->user()->hasRole('admin')) {
            abort(403, 'Unauthorized action.');
        }

        return view('users.edit_password', compact('user'));
    }

    public function savePassword(Request $request, User $user) {
        // If it's the user changing their own password
        if (auth()->id() == $user->id) {
            $this->validate($request, [
                'old_password' => ['required'],
                'password' => ['required', 'confirmed', Password::min(8)->numbers()->letters()->mixedCase()->symbols()],
            ]);

            if (!Auth::attempt(['email' => $user->email, 'password' => $request->old_password])) {
                Auth::logout();
                return redirect('/')->withErrors('Current password is incorrect.');
            }
        }
        // If it's an admin changing someone else's password
        elseif (!auth()->user()->hasRole('admin')) {
            abort(403, 'Unauthorized action.');
        }

        $user->password = bcrypt($request->password);
        $user->save();

        return redirect(route('users.profile', ['user' => $user->id]))
            ->with('success', 'Password updated successfully.');
    }

    public function manageUsers(Request $request) {
        if(!auth()->user()->hasAnyRole(['admin', 'employee'])) {
            abort(403, 'Unauthorized action.');
        }

        $query = User::select('*');

        // If user is an employee, only show customers
        if(auth()->user()->hasRole('employee')) {
            $query->role('customer');
        }

        if ($request->keywords) {
            $query->where("name", "like", "%$request->keywords%");
        }
        $users = $query->get();
        return view('users.manage', compact('users'));
    }

    public function manageCredit(Request $request, User $user) {
        try {
            if(!auth()->user()->hasAnyRole(['admin', 'employee'])) {
                abort(403, 'Unauthorized action.');
            }

            $request->validate([
                'action' => 'required|in:add,subtract,set',
                'amount' => 'required|numeric|min:0|max:999999999',
                'reason' => 'required|string|max:1000'
            ]);

            $amount = $request->amount;
            $currentCredit = $user->getCreditBalance();
            $success = false;

            DB::beginTransaction();
            try {
                switch($request->action) {
                    case 'add':
                        $success = $user->addCredit($amount);
                        $message = "Added {$amount} credits";
                        break;
                    case 'subtract':
                        if($currentCredit < $amount) {
                            throw new \Exception('Cannot subtract more credits than available.');
                        }
                        $success = $user->deductCredit($amount);
                        $message = "Subtracted {$amount} credits";
                        break;
                    case 'set':
                        if($amount > $currentCredit) {
                            $success = $user->addCredit($amount - $currentCredit);
                        } else {
                            $success = $user->deductCredit($currentCredit - $amount);
                        }
                        $message = "Set credits to {$amount}";
                        break;
                }

                if (!$success) {
                    throw new \Exception('Failed to update credit balance.');
                }

                // Save the credit transaction
                DB::table('credit_transactions')->insert([
                    'user_id' => $user->id,
                    'amount' => $request->action == 'subtract' ? -$amount : $amount,
                    'action' => $request->action,
                    'reason' => $request->reason,
                    'performed_by' => auth()->id(),
                    'created_at' => now(),
                    'updated_at' => now()
                ]);

                DB::commit();
                return redirect()->back()->with('success', "Credit updated successfully. {$message}.");
            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }
        } catch (\Illuminate\Session\TokenMismatchException $e) {
            return redirect()->back()
                ->with('error', 'Your session has expired. Please try again.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', $e->getMessage() ?: 'An error occurred while updating credit. Please try again.');
        }
    }
}
