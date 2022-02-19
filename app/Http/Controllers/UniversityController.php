<?php

namespace App\Http\Controllers;

use App\Models\University;
use Illuminate\Http\Request;
use App\Http\Requests\UniversityStoreRequest;
use App\Http\Requests\UniversityUpdateRequest;
use App\Http\Resources\UniversityResource;

use App\Imports\UniversityImport;
use Maatwebsite\Excel\Facades\Excel;

class UniversityController extends Controller
{
    /**
     * Create the controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->authorizeResource(University::class, 'university');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->input('all'))
            $university = University::select('name')->get();
        else
            $university = University::paginate();
        
        return UniversityResource::collection($university);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UniversityStoreRequest $request)
    {
        $university = University::create($request->validated());
        return new UniversityResource($university);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\University  $university
     * @return \Illuminate\Http\Response
     */
    public function show(University $university)
    {
        return new UniversityResource($university);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\University  $university
     * @return \Illuminate\Http\Response
     */
    public function update(UniversityUpdateRequest $request, University $university)
    {
        $university->update($request->validated());
        return new UniversityResource($university);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\University  $university
     * @return \Illuminate\Http\Response
     */
    public function destroy(University $university)
    {
        $data = $university->delete();
        return response()->json($data, 202);
    }

    public function import(Request $request)
    {
        if($request->hasFile('importUniversity')){
            Excel::import(new UniversityImport, $request->importUniversity);
        }
    }
}
