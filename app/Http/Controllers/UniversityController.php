<?php

namespace App\Http\Controllers;

use App\Models\University;

use Illuminate\Http\Request;
use App\Http\Resources\UniversityResource;

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
    public function index()
    {
        return UniversityResource::collection(University::all());
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
            'address'=> 'required|string|max:255',
            'link'=> 'required|string|max:255',
            'tuitionfees'=> 'required|string|max:255',
            'intake'=> 'required|string|max:255'
        ]);


        $university = University::create([
            'name' => $request->name,
            'address' => $request->address,
            'link' => $request->link,
            'tuitionfees' => $request->tuitionfees,
            'intake' => $request->intake,
        ]);

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
    public function update(Request $request, University $university)
    {
        $request->validate([
            'name'=> 'string|max:255',
            'address'=> 'string|max:255',
            'link'=> 'string|max:255',
            'tuitionfees'=> 'string|max:255',
            'intake'=> 'string|max:255'
        ]);
        
        // return $university;
        $university->update($request->all());

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
        $university->delete();
        return 'SuccessFully Deleted';
    }
}
