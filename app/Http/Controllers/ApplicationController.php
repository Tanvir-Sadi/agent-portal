<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\Status;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Http\Resources\ApplicationResource;
use App\Http\Resources\MediaResource;
use Illuminate\Support\Facades\Storage;
use Spatie\QueryBuilder\QueryBuilder;

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
            return ApplicationResource::collection( QueryBuilder::for(Application::class)
            ->allowedFilters(['application_id', 'student_name','university_name','course_name', 'course_level', 'course_intake'])
            ->with('user:id,name')
            ->orderBy('updated_at','desc')
            ->paginate(6)
        );
        } else {
            return ApplicationResource::collection( QueryBuilder::for(auth()->user()->applications())
            ->allowedFilters(['application_id', 'student_name','university_name','course_name', 'course_level', 'course_intake'])
            ->with('user:id,name')
            ->orderBy('updated_at','desc')
            ->paginate(6));
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
            'passport_number' => 'string|max:255',
            'passport_expire_date' => 'date',
            'visa_refusal' => 'required|boolean',
            'nationality' => 'required|string|max:255',
        ]);
        
        $application = auth()->user()->applications()->create($request->all());
        $application->application_id = Carbon::now()->year.$application->id;
        $application->save();
        $application->statuses()->sync(Status::all());
        return $application;

    }


    public function upload(Request $request, $id)
    {
        if ($request->hasFile('document')) {
            $application = Application::find($id);
            $application->addMedia($request->document)->toMediaCollection($request->type);
            $application->updated_at=Carbon::now();
            $application->save();
            return response()->json('Uploaded Successfully',200);
        }else{
            return response()->json('File Not Found',404);
        }
    }

    public function getMedia($id)
    {
        $application = Application::find($id);
        return new MediaResource($application);
    }

    public function downloadMedia(Request $request)
    {
        // return $request->name;
        return Storage::disk('public')->download($request->name);
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

    public function viewStatus(Application $application, Status $status)
    {
        return $application->statuses()->get();
    }

    public function updateStatus(Request $request, Application $application, Status $status)
    {
        $request->validate([
            'status' => 'required|boolean'
        ]);

        // return response()->json($request->status);
        $application->updated_at=Carbon::now();
        $application->save();
        return $application->statuses()->updateExistingPivot($status,['status'=>$request->status]);
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
        return response()->json('Successfully Deleted', 200);
    }
}
