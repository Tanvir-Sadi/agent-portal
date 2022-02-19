<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\University;
use Illuminate\Http\Request;
use App\Http\Requests\CourseStoreRequest;
use App\Http\Requests\CourseUpdateRequest;
use App\Http\Resources\CourseResource;


use App\Imports\CourseImport;
use Maatwebsite\Excel\Facades\Excel;
use Spatie\QueryBuilder\QueryBuilder;

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
    public function index($university)
    {
        if($university==0){
            return CourseResource::collection(Course::with(['intakes:id,name','levels', 'university:id,name',])->paginate());
        }
        return CourseResource::collection(University::find($university)->courses()->with(['intakes:id,name', 'university:id,name', 'levels:id,name'])->paginate());
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(University $university, CourseStoreRequest $request)
    {
        $course = $university->courses()->create($request->validated());
        return new CourseResource($course);
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
    public function update(CourseUpdateRequest $request, Course $course)
    {
        $course->update($request->validated());
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
        return response()->json('SuccessFully Deleted', 202,);
    }

    public function import(Request $request)
    {
        if($request->hasFile('importCourse')){
            Excel::import(new CourseImport, $request->importCourse);
        }
    }

    public function search()
    {
        return QueryBuilder::for(Course::class)
        ->select(['name'])
        ->allowedFilters(['university.name', 'intakes.name', 'levels.name'])
        ->get();
    }
}
