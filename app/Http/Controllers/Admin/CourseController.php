<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $courses = \App\Models\ShortCourse::latest()->paginate(10);
        return view('admin.courses.index', compact('courses'));
    }

    public function create()
    {
        return view('admin.courses.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'category' => 'required',
            'course_name' => 'required',
            'code' => 'required|unique:short_courses',
            'fee' => 'required|numeric',
            'duration' => 'required',
            'description' => 'nullable',
        ]);

        $category = $request->category;
        if ($category === 'new_category') {
            $request->validate(['new_category' => 'required|string|max:255']);
            $category = $request->new_category;
        }

        \App\Models\ShortCourse::create([
            'category' => $category,
            'course_name' => $request->course_name,
            'code' => $request->code,
            'fee' => $request->fee,
            'duration' => $request->duration,
            'description' => $request->description,
            'status' => $request->has('status'),
        ]);

        return redirect()->route('admin.courses.index')->with('success', 'Course created successfully.');
    }

    public function show(string $id)
    {
        //
    }

    public function edit(string $id)
    {
        $course = \App\Models\ShortCourse::findOrFail($id);
        return view('admin.courses.edit', compact('course'));
    }

    public function update(Request $request, string $id)
    {
        $course = \App\Models\ShortCourse::findOrFail($id);

        $request->validate([
            'category' => 'required',
            'course_name' => 'required',
            'code' => 'required|unique:short_courses,code,' . $course->id,
            'fee' => 'required|numeric',
            'duration' => 'required',
            'description' => 'nullable',
        ]);

        $category = $request->category;
        if ($category === 'new_category') {
            $request->validate(['new_category' => 'required|string|max:255']);
            $category = $request->new_category;
        }

        $data = [
            'category' => $category,
            'course_name' => $request->course_name,
            'code' => $request->code,
            'fee' => $request->fee,
            'duration' => $request->duration,
            'description' => $request->description,
            'status' => $request->has('status'),
        ];

        $course->update($data);

        return redirect()->route('admin.courses.index')->with('success', 'Course updated successfully.');
    }

    public function destroy(string $id)
    {
        $course = \App\Models\ShortCourse::findOrFail($id);
        $course->delete();
        return redirect()->route('admin.courses.index')->with('success', 'Course deleted successfully.');
    }
}
