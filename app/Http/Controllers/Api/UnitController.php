<?php
// app/Http/Controllers/Api/UnitController.php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Unit;
use App\Models\Course;

class UnitController extends Controller
{


    public function index($courseId)
    {
        $units = Unit::where('course_id', $courseId)->get();
        return response()->json($units);
    }

    
    public function store(Request $request)
    {
        $request->validate([
            'unit_name' => 'required|string|max:255',
            'course_id' => 'required|exists:courses,id'
        ]);

        $unit = Unit::create([
            'unit_name' => $request->unit_name,
            'course_id' => $request->course_id
        ]);

        return response()->json(['message' => 'Unit created successfully', 'unit' => $unit], 201);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'unit_name' => 'required|string|max:255'
        ]);

        $unit = Unit::findOrFail($id);
        $unit->update($request->all());

        return response()->json(['message' => 'Unit updated successfully', 'unit' => $unit]);
    }

    public function destroy($id)
    {
        $unit = Unit::findOrFail($id);
        $unit->delete();

        return response()->json(['message' => 'Unit deleted successfully']);
    }



    public function show($id)
    {
        // جلب الوحدة مع الدروس والأسئلة المرتبطة بها
        $unit = Unit::with(['lessons', 'questions.answers'  ])->findOrFail($id);
    
        return response()->json([
            'unit' => $unit
        ]);
    }
}
