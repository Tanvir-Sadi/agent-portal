<?php

namespace App\Http\Controllers;

use App\Models\Intake;
use App\Http\Requests\IntakeStoreRequest;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;

class IntakeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return QueryBuilder::for(Intake::class)
        ->select(['name'])
        ->allowedFilters(['courses.university.name'])
        ->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(IntakeStoreRequest $request)
    {
        return Intake::create($request->validated());
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Intake  $intake
     * @return \Illuminate\Http\Response
     */
    public function show(Intake $intake)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Intake  $intake
     * @return \Illuminate\Http\Response
     */
    public function edit(Intake $intake)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Intake  $intake
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Intake $intake)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Intake  $intake
     * @return \Illuminate\Http\Response
     */
    public function destroy(Intake $intake)
    {
        //
    }
}
