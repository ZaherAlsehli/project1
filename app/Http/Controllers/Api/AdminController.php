<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Notifications\Notifiable;


class AdminController extends Controller
{
   
    public function softDeleteUser($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return response()->json(['message' => 'User soft deleted successfully.']);
    }

    public function restoreUser($id)
    {
        $user = User::withTrashed()->findOrFail($id);
        $user->restore();

        return response()->json(['message' => 'User restored successfully.']);
    }

    public function forceDeleteUser($id)
    {
        $user = User::withTrashed()->findOrFail($id);
        $user->forceDelete();

        return response()->json(['message' => 'User permanently deleted successfully.']);
    }

       public function allTeachers()
       {
           $teachers = User::where('role', 'teacher')->get();
   
           return response()->json($teachers);
       }
   
       public function allStudents()
       {
           $students = User::where('role', 'student')->get();
   
           return response()->json($students);
       }

}
