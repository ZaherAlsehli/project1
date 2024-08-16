<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Lesson;
use App\Models\Unit;
use App\Models\Course;
use Illuminate\Http\Request;
use Vimeo\Vimeo;
use Illuminate\Support\Facades\Auth;

class LessonController extends Controller
{
    protected $vimeo;

    public function __construct()
    {
        $this->vimeo = new Vimeo(
'7f17afda52e5c3306ee3704a548cb283fd82fe0d'
,
'e4Z5d6bVC+GiV0vgcWeN1u3Ai8FYC5iue96ceODpD+mzhr6wsyTOAKl041+FuEuvOTb88z9Hg0f3UbcY5d6SNsX3uksIJGJjz+PEOIns++R1ujiFITCy1tr1m9sLgC8Lenv',
'498c76a3dd6388c2b07e725f32f41599'       
);

        
    }




    public function index($unit_id)
    {
        $unit = Unit::with(['lessons', 'questions'])->findOrFail($unit_id);

        return response()->json([
            'lessons' => $unit->lessons,
            'questions' => $unit->questions
        ], 200);
    }



    public function store(Request $request, $unit_id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'video' => 'required|file|mimetypes:video/mp4,video/quicktime|max:102400', // 100 MB max
        ]);

        try {
            $unit = Unit::findOrFail($unit_id);
            
            // Check if the authenticated user is the teacher of the course
            $course = Course::findOrFail($unit->course_id);
            if (auth()->user()->teacher->id  !== $course->teacher_id) {
                return response()->json(['error' => 'Unauthorized'], 403);
            }

            $file = $request->file('video');
            $uri = $this->vimeo->upload($file->getPathName(), [
                'name' => $request->input('title'),
                'description' => $request->input('description', ''),
            ]);
            
            $videoData = $this->vimeo->request($uri . '?fields=link');
            $link = $videoData['body']['link'];
            
            $lesson = Lesson::create([
                'unit_id' => $unit_id,
                'title' => $request->input('title'),
                'description' => $request->input('description'),
                'vimeo_uri' => $uri,
                'link' => $link,
            ]);

            return response()->json(['message' => 'Lesson created and video uploaded successfully', 'lesson' => $lesson], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        $lesson = Lesson::findOrFail($id);
        return response()->json($lesson, 200);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
            'video' => 'sometimes|file|mimetypes:video/mp4,video/quicktime|max:102400', // 100 MB max
        ]);

        try {
            $lesson = Lesson::findOrFail($id);

            // Check if the authenticated user is the teacher of the course
            $course = Course::findOrFail($lesson->unit->course_id);
            if (auth()->user()->teacher->id  !== $course->teacher_id) {
                return response()->json(['error' => 'Unauthorized'], 403);
            }

            if ($request->hasFile('video')) {
                $file = $request->file('video');
                $uri = $this->vimeo->upload($file->getPathName(), [
                    'name' => $request->input('title', $lesson->title),
                    'description' => $request->input('description', $lesson->description),
                ]);

                $videoData = $this->vimeo->request($uri . '?fields=link');
                $link = $videoData['body']['link'];

                $lesson->update([
                    'vimeo_uri' => $uri,
                    'link' => $link,
                ]);
            }

            $lesson->update($request->only('title', 'description'));

            return response()->json(['message' => 'Lesson updated successfully', 'lesson' => $lesson], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $lesson = Lesson::findOrFail($id);

            // Check if the authenticated user is the teacher of the course
            $course = Course::findOrFail($lesson->unit->course_id);
            if (auth()->user()->teacher->id  !== $course->teacher_id) {
                return response()->json(['error' => 'Unauthorized'], 403);
            }

            $this->vimeo->request($lesson->vimeo_uri, [], 'DELETE');
            $lesson->delete();

            return response()->json(['message' => 'Lesson and video deleted successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getUnitQuestions($unit_id)
    {
        $unit = Unit::with('questions')->findOrFail($unit_id);
        return response()->json($unit->questions, 200);
    }

    public function favorite(Request $request, $id)
{
    $user = Auth::user();
    $lesson = Lesson::findOrFail($id);

    $favoriteStatus = $request->input('favorite');

    $user->favorites()->syncWithoutDetaching([
        $lesson->id => ['favorite' => $favoriteStatus]
    ]);

    return response()->json([
        'message' => 'Favorite status updated',
        'favorite' => $favoriteStatus,
    ]);
}
}