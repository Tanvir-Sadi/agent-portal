<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;
use App\Http\Resources\CourseResource;

class CourseController extends Controller
{
    /**
     * Create the controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->authorizeResource(Course::class, 'course');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return CourseResource::collection(Course::all());
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'=> 'required|string|max:255',
            'level'=> 'required|string|max:255',
            'link'=> 'required|string|max:255',
            'university_id'=> 'required|integer',
        ]);

        $course = Course::create([
            'name' => $request->name,
            'level' => $request->level,
            'link' => $request->link,
            'university_id' => $request->university_id,
        ]);
        return $course = Course::where('name', $course->name)->firstOrFail();
        // return new CourseResource($course);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Course  $course
     * @return \Illuminate\Http\Response
     */
    public function show(Course $course)
    {
        return new CourseResource($course);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Course  $course
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Course $course)
    {
        $request->validate([
            'name'=> 'string|max:255',
            'level'=> 'string|max:255',
            'link'=> 'string|max:255',
            'university_id'=> 'integer'
        ]);

        // return $course;
        $course->update($request->all());

        return new CourseResource($course);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Course  $course
     * @return \Illuminate\Http\Response
     */
    public function destroy(Course $course)
    {
        $course->delete();
        return 'SuccessFully Deleted';
    }
}
