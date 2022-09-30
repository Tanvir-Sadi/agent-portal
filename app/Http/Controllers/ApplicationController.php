<?php

namespace App\Http\Controllers;

use App\Http\Requests\ApplicationStoreRequest;
use App\Http\Requests\ApplicationUpdateRequest;
use App\Models\Application;
use App\Models\User;
use App\Models\Status;
use App\Services\ApplicationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Http\Resources\MediaResource;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\MediaLibrary\Support\MediaStream;
use App\Notifications\NewMessage;
use Illuminate\Support\Facades\Notification;

class ApplicationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(ApplicationService $applicationService)
    {
        return response()->json($applicationService->getApplications(),200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param ApplicationStoreRequest $request
     * @return JsonResponse
     */
    public function store(ApplicationStoreRequest $request)
    {

        $application = auth()->user()->applications()->create($request->all());
        $application->application_id = Carbon::now()->year.$application->id;
        $application->save();
        $message =
            [
                'message' => 'Application has been received. It will be reviewed shortly.',
                'type' => 'Application Under Review',
                'user' => auth()->user()->getName()
            ];
        $message = $application->messages()->create($message);
        $users=User::where('roles','admin')
                    ->get();
        Notification::send($users, new NewMessage($message));
        return response()->json($application,200);

    }

    /**
     * Display the specified resource.
     *
     * @param Application $application
     * @return JsonResponse
     */
    public function show(Application $application)
    {
        auth()->user()->notifications()->where('data->application_id', $application->id)->update(['read_at' => now()]);
        return response()->json($application, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param ApplicationUpdateRequest $request
     * @param Application $application
     * @return JsonResponse
     */
    public function update(ApplicationUpdateRequest $request, Application $application)
    {
        return response()->json(auth()->user()->applications()->update($request->except(['_method' ])), 200);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param Application $application
     * @return JsonResponse
     */
    public function destroy(Application $application)
    {
        $application->delete();
        return response()->json('Successfully Deleted', 202);
    }

    /**
     * Upload Files a resource in storage.
     *
     * @param ApplicationStoreRequest $request
     * @return JsonResponse
     */
    public function upload(Request $request, $id)
    {
        $request->validate([
            'document.*' => 'file|max:10240',
            'type' => 'required'
        ]);

        if ($request->hasFile('document')) {
            $application = Application::find($id);
            $application->addMultipleMediaFromRequest(['document'])
                ->each(function ($fileAdder) use($request) {
                    $fileAdder->toMediaCollection($request->type);
                });
            $application->updated_at=Carbon::now();
            $application->save();
            return response()->json('Uploaded Successfully',200);
        }else{
            return response()->json('File Not Found',404);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Media $media
     * @return JsonResponse
     */
    public function destroyMedia(Media $media)
    {
        $media->delete();
        return response()->json('Media Successfully Deleted', 200);
    }


    /**
     * Remove the specified media from storage.
     *
     * @param Application $application
     * @return JsonResponse
     */
    public function getMedia(Application $application)
    {
        $result = new MediaResource($application);
        return response()->json($result, 200);
    }

    public function downloadAll(Application $application)
    {
        return MediaStream::create($application->application_id.' '.$application->student_name.'.zip')->addMedia(
            $application->media->filter(function(Media $media) {
            return in_array($media->collection_name, ['academic', 'cv', 'recomendation', 'refference', 'english', 'passport', 'work', 'visa', 'sop', 'conditional', 'unconditional', 'other']);
        }));
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

        $application->updated_at=Carbon::now();
        $application->save();
        $application->messages()->create(
            [
                'message' => auth()->user()->getName().' changed Application Status',
                'type' => $status->name,
                'user' => auth()->user()->getName()
            ]
        );
        return $application->statuses()->updateExistingPivot($status,['status'=>$request->status]);
    }

}
