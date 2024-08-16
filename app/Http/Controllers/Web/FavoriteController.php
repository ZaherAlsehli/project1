<?php
namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Favorite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
    public function store(Request $request, $lesson_id)
    {

        // تحقق إذا كان المستخدم قد قام بالفعل بتفضيل الدرس
        $favorite = Favorite::where('user_id', $user->id)
                            ->where('lesson_id', $lesson_id)
                            ->first();

        if ($favorite) {
            // تحديث الحالة الحالية إذا كان المستخدم قد قام بالتفضيل مسبقاً
            $favorite->update([
                'favorite' => $request->favorite
            ]);
        } else {
            // إنشاء تفضيل جديد
            Favorite::create([
                'user_id' => $user->id,
                'lesson_id' => $lesson_id,
                'favorite' => $request->favorite
            ]);
        }

        return response()->json(['message' => 'Your preference has been saved.']);
    }
}
