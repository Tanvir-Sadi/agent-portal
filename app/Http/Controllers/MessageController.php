<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\Application;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Notifications\NewMessage;
use Illuminate\Support\Facades\Notification;

class MessageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Application $application)
    {
        return $application->messages()->get();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Application $application, Request $request)
    {
        $request->validate([
            'message'=> 'string|max:2000',
            'type'=> 'required|string|max:255',
            'user'=> 'required|string|max:255',
        ]);
        $message = $application->messages()->create([
            'message' => $request->message,
            'type' => $request->type,
            'user' => $request->user
        ]);
        $application->updated_at=Carbon::now();
        $application->save();
        $users=User::where([['roles','admin'], ['id', '!=',auth()->user()->id],])
                            ->orWhere([['id', $application->user->id], ['id', '!=',auth()->user()->id],])
                            ->get();

        Notification::send($users, new NewMessage($message));
        return $message;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Message  $message
     * @return \Illuminate\Http\Response
     */
    public function show(Message $message)
    {
        return $message;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Message  $message
     * @return \Illuminate\Http\Response
     */
    public function edit(Message $message)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Message  $message
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Message $message)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Message  $message
     * @return \Illuminate\Http\Response
     */
    public function destroy(Message $message)
    {
        //
    }
}
