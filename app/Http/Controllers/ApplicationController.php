<?php

namespace App\Http\Controllers;

use App\Models\Application;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Http\Resources\ApplicationResource;

class ApplicationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (auth()->user()->roles == 'admin') {
            return ApplicationResource::collection(Application->orderBy('updated_at','desc')->paginate());
        } else {
            return ApplicationResource::collection(auth()->user()->applications()->orderBy('updated_at','desc')->paginate(6));
        }
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
            'university_name' => 'required|string|max:255',
            'course_level' => 'required|string|max:255',
            'course_name' => 'required|string|max:255',
            'student_name' => 'required|string|max:255',
            'student_email' => 'required|email|string||max:255',
            'student_number' => 'required|string|max:255',
            'student_dob' => 'required|date',
            'visa_refusal' => 'required|boolean',
            'nationality' => 'required|string|max:255',
        ]);
        
        $application = auth()->user()->applications()->create($request->all());
        $application->application_id = Carbon::now()->year.$application->id;
        $application->save();
        return $application;

    }


    public function upload(Request $request, $id)
    {
        if ($request->hasFile('document')) {
            $application = Application::find($id);
            $application->addMedia($request->document)->toMediaCollection($request->type);
            return 'Uploaded Successfully';
        }else{
            return response()->json('File Not Found',404);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Application  $application
     * @return \Illuminate\Http\Response
     */
    public function show(Application $application)
    {
        return $application;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Application  $application
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Application $application)
    {
        $request->validate([
            'university_name' => 'string|max:255',
            'course_level' => 'string|max:255',
            'course_name' => 'string|max:255',
            'student_name' => 'string|max:255',
            'student_email' => 'email|string||max:255',
            'student_number' => 'string|max:255',
            'student_dob' => 'date',
            'visa_refusal' => 'boolean',
            'nationality' => 'string|max:255',
        ]);

        $application = auth()->user()->applications()->update($request->except(['_method' ]));
        return $application;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Application  $application
     * @return \Illuminate\Http\Response
     */
    public function destroy(Application $application)
    {
        $application->delete();
        return 'SuccessFully Deleted';
    }
}
