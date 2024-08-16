<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\Category;
use App\traits\ApiResponse;
use Illuminate\Http\Request;
use App\Models\PendingTeacher;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\AdminAithResource;
use Dotenv\Exception\ValidationException;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\StudentAuthResource;
use App\Http\Resources\TeacherAuthResource;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use PHPOpenSourceSaver\JWTAuth\JWTAuth as JWTAuthJWTAuth;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth as FacadesJWTAuth;

class AUTH_Users extends Controller
{
    use ApiResponse;
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
            return response()->json($validator->errors(), 422);
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
                'role' => 'teacher', // تأكد من إضافة حقل الدور هنا
            ]);
            return response()->json(['message' => 'Your account is awaiting approval'], 201);
        } 
        else {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'city' => $request->city,
                'password' => Hash::make($request->password),
                'approved' => false, // تعديل الحقل ليكون 0
                'role' => 'student',
            ]);
    
            $user->assignRole($request->role);
    
            Student::create([
                'user_id' => $user->id,
            ]);
            $token = JWTAuth::fromUser($user);
            $responseData = new StudentAuthResource($user);
    
            $data = [];
            $data['user'] = $responseData;
            $data['token'] = $token;
            return $this->rseponse($data, 'User registered successfully', 200);
        }
    }
    
    
    
    
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
    $adminUser = User::whereHas('roles', function ($query) {
        $query->where('name', 'admin');
    })->first();
    if ($adminUser && $request->email === $adminUser->email) {
        return response()->json(['error' => 'Admins are not allowed to log in here'], 403);
    }
        $credentials = $request->only('email', 'password');
        
        if (!$token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        
        $user = auth()->user();
    
 
        if ($user->hasRole('teacher') && !$user->approved) {
            return response()->json(['error' => 'Teacher registration is not yet approved'], 403);
        }
   $token = JWTAuth::fromUser($user);
   if ($user->hasRole('student')) {
    $responseData = new StudentAuthResource($user);
    $data['user'] = $responseData;
    $data['token'] = $token;
 return $this->rseponse( $data, 'You have been logged successfully', 200);
} 
elseif ($user->hasRole('teacher')){
    $user->load('teacher.category');
    $teacher = $user->teacher;

    $teacherArray = $teacher->toArray();
    unset($teacherArray['created_at'], $teacherArray['updated_at']);

    if ($teacher->category === null) {
        unset($teacherArray['category']);
    }

    return response()->json([
        'teacher' => $teacherArray,
        'token' => $token,
        'message' => 'You have been logged in successfully',
    ]);
}
 
}
 
    
    
    public function logout(Request $request)
    {
        try {
            JWTAuth::invalidate(JWTAuth::getToken());
            return response()->json(['message' => 'Successfully logged out'], 200);
        } catch (TokenInvalidException $e) {
            return response()->json(['message' => 'Failed to log out, invalid token'], 400);
        }
    }
    


public function profile()
{
    $user = Auth::user();
    if ($user->hasRole('admin')) {
        $responseData = new AdminAithResource($user);
    } elseif ($user->hasRole('student')) {
        $responseData = new StudentAuthResource($user);
    } 

    elseif ($user->hasRole('teacher')){
        $user->load('teacher.category');
        $teacher = $user->teacher;
    
        $teacherArray = $teacher->toArray();
        unset($teacherArray['created_at'], $teacherArray['updated_at']);
    
        if ($teacher->category === null) {
            unset($teacherArray['category']);
        }
    
        return response()->json([
            'teacher' => $teacherArray,
            'message' => 'Your profile data has been displayed successfully',
        ]);
    }
     else
    
    {
        return response()->json(['error' => 'Unauthorized'], 403);
    }
    return $this->rseponse($responseData , 'Your profile data has been displayed successfully', 200);

}
  


public function updateProfile(Request $request ,$id)
{
    $user = auth()->user();
    $validator = Validator::make($request->all(), [
        'name' => 'sometimes|string|max:255',
        'email' => 'sometimes|string|email|max:255|unique:users,email,' . $user->id,
        'password' => 'sometimes|string|min:8|confirmed',
        'city' => 'sometimes|string',
        'cv_path' => $user->role === 'teacher' ? 'sometimes|file|mimes:pdf|max:2048' : '',
    ]);

    if ($validator->fails()) {
        return response()->json($validator->errors(), 422);
    }
    if ($request->has('name')) {
        $user->name = $request->name;
    }
    if ($request->has('email')) {
        $user->email = $request->email;
    }
    if ($request->has('password')) {
        $user->password = Hash::make($request->password);
    }
    if ($request->has('city')) {
        $user->city = $request->city;
    }

    if ($user->role === 'teacher' && $request->hasFile('cv_path')) {
        if ($user->cv_path) {
            Storage::delete($user->cv_path);
        }
        
        $path = $request->file('cv_path')->store('cv_files');
        $user->cv_path = $path;
    }
    $user->save();
    if ($user->hasRole('student')) {
        $responseData = new StudentAuthResource($user);
    } elseif ($user->hasRole('teacher')) {
        $responseData = new TeacherAuthResource($user);
    }

    return $this->rseponse( $responseData, 'Your profile data has been updated successfully', 200);
}



public function approveTeacher(Request $request, $id)
{
    $pendingTeacher = PendingTeacher::findOrFail($id);
 

// إنشاء المستخدم في جدول users
$user = User::create([
    'name' => $pendingTeacher->name,
    'email' => $pendingTeacher->email,
    'city' => $pendingTeacher->city,
    'role' => 'teacher',
    'password' => $pendingTeacher->password,
    'approved' => true,
]);

// تعيين دور المدرس للمستخدم
$user->assignRole('teacher');

// إنشاء مدرس جديد وربطه بالمستخدم والتصنيف
Teacher::create([
    'user_id' => $user->id,
    'cv_path' => $pendingTeacher->cv_path,
'Category_id' => $pendingTeacher->Category_id, 
]);

    // حذف السجل من جدول pending_teachers
    $pendingTeacher->delete();
    $this->reorderPendingTeachers();

    

    return response()->json(['message' => 'Teacher approved successfully.'], 200);
}

public function rejectTeacher(Request $request, $id)
{
    $pendingTeacher = PendingTeacher::findOrFail($id);
    $pendingTeacher->delete();
    $this->reorderPendingTeachers();


    return response()->json(['message' => 'Teacher rejected successfully.'], 200);
}


protected function reorderPendingTeachers()
{
    // تعطيل التحقق من القيود
    DB::statement('SET foreign_key_checks = 0');

    // الحصول على جميع السجلات وترتيبها حسب id
    $pendingTeachers = PendingTeacher::orderBy('id')->get();

    // إعادة تعيين القيم في عمود id
    foreach ($pendingTeachers as $index => $teacher) {
        DB::table('pending_teachers')->where('id', $teacher->id)->update(['id' => $index + 1]);
    }

    // إعادة تعيين AUTO_INCREMENT إلى القيمة القصوى الحالية + 1
    $maxId = $pendingTeachers->count();
    $nextId = $maxId + 1;
    DB::statement('ALTER TABLE pending_teachers AUTO_INCREMENT = ' . $nextId);

    // إعادة تفعيل التحقق من القيود
    DB::statement('SET foreign_key_checks = 1');
}





public function adminLogin(Request $request)
{
    $validator = Validator::make($request->all(), [
        'email' => 'required|email',
        'password' => 'required|string|min:6',
    ]);

    if ($validator->fails()) {
        return response()->json($validator->errors(), 422);
    }
    $credentials = $request->only('email', 'password');

    if (!$token = auth()->attempt($credentials)) {
        return response()->json(['error' => 'Unauthorized'], 401);
    }

    $user = auth()->user();

    if (!$user->hasRole('admin')) {
        return response()->json(['error' => 'Unauthorized'], 403);
    }
   $responseData = new AdminAithResource($user);

   $data = [];
    $data['user'] =  $responseData;
    $data['token'] = $token;

    return $this->rseponse( $data, 'Admin logged successfully ', 200);
}

public function updateAdminProfile(Request $request, $id)  {

$user = User::findOrFail($id);

$validator = Validator::make($request->all(), [
    'name' => 'sometimes|string|max:255',
    'email' => 'sometimes|string|email|max:255|unique:users,email,' . $user->id,
    'password' => 'sometimes|string|min:8|confirmed',
]);

if ($validator->fails()) {
    return response()->json($validator->errors(), 422);
}

if ($request->has('name')) {
    $user->name = $request->name;
}
if ($request->has('email')) {
    $user->email = $request->email;
}
if ($request->has('password')) {
    $user->password = Hash::make($request->password);
}
$user->save();
$responseData = new AdminAithResource($user);
 return $this->rseponse($responseData, 'Your profile data has been updated successfully', 200);
}
public function show($id)
    {
        // استرجاع المعلم مع بيانات الفئة المرتبطة به
        $teacher = Teacher::with('category')->find($id);

        // التحقق من وجود المعلم
        if (!$teacher) {
            return response()->json(['error' => 'Teacher not found'], 404);
        }

        // إعداد البيانات للإرجاع
        $data = [
            'id' => $teacher->id,
            'name' => $teacher->name,
            'email' => $teacher->email,
            'category' => [
                'id' => $teacher->category ? $teacher->category->id : null,
                'name' => $teacher->category ? $teacher->category->category_name : 'No Category',
            ]
        ];

        return response()->json($data);
    }

    public function printTeachers()
    {
        $teachers = Teacher::with('category', 'user')->get(); // جلب جميع المدرسين مع العلاقات
        return response()->json($teachers);
    }



    public function listPendingTeachers()
    {
        $pendingTeachers = PendingTeacher::all();
        return response()->json($pendingTeachers);
    }

    



}