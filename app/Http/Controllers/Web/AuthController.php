<?php
namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\Category;
use App\Models\PendingTeacher;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    // عرض نموذج التسجيل
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    // عرض نموذج تسجيل الدخول
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // تنفيذ التسجيل
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users|unique:pending_teachers',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:student,teacher',
            'city' => 'required|string',
            'Category_id' => 'required_if:role,teacher|exists:categories,id',
        ]);

        $validator->after(function ($validator) use ($request) {
            if ($request->role === 'student' && $request->has('Category_id')) {
                $validator->errors()->add('Category_id', 'Students are not allowed to select a category.');
            }
            if ($request->role === 'teacher') {
                if (!$request->hasFile('cv_path')) {
                    $validator->errors()->add('cv_path', 'The CV is required for teachers.');
                } elseif (!$request->file('cv_path')->isValid()) {
                    $validator->errors()->add('cv_path', 'The CV file is not valid.');
                }
            }
        });

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $cvPath = null;
        if ($request->role === 'teacher' && $request->hasFile('cv_path')) {
            $file = $request->file('cv_path');
            $cvPath = $file->store('cvs', 'public');
        }

        if ($request->role === 'teacher') {
            PendingTeacher::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'city' => $request->city,
                'cv_path' => $cvPath,
                'Category_id' => $request->Category_id,
                'role' => 'teacher',
            ]);
            return redirect()->route('home')->with('message', 'Your account is awaiting approval.');
        } else {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'city' => $request->city,
                'password' => Hash::make($request->password),
                'approved' => false,
                'role' => 'student',
            ]);

            $user->assignRole($request->role);

            Student::create([
                'user_id' => $user->id,
            ]);

            Auth::login($user); // تسجيل الدخول تلقائياً بعد التسجيل
            return redirect()->route('home')->with('message', 'User registered successfully.');
        }
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            if ($user->role === 'teacher' && !$user->approved) {
                Auth::logout();
                return redirect()->back()->with('error', 'Teacher registration is not yet approved.');
            }

            return redirect()->route('home')->with('message', 'You have been logged in successfully');
        }

        return redirect()->back()->with('error', 'Invalid credentials, please try again.');
    }

    // تنفيذ تسجيل الخروج
    public function logout()
    {
        Auth::logout();
        return redirect()->route('login')->with('message', 'You have been logged out successfully');
    }
}
