<?php

namespace App\Http\Controllers;

use App\Models\Document;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Http\Response;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\MediaLibrary\Support\MediaStream;
use Spatie\QueryBuilder\QueryBuilder;
use Illuminate\Support\Facades\DB;


class DocumentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     * ponse
     */
    public function index(Request $request)
    {
        $document = Document::whereIsRoot()->with(
            ['documents'=>function($q){
                $q->orderBy('name','asc');
            },'media',
            'ancestors'=>function($q){
                $q->orderBy('_lft','asc');
            }])->first();
        return response()->json($document, 200);
    }

    public function search(Request $request)
    {
        $document = Document::where('name','like','%'.$request->input('name').'%')
            ->orderBy('name','asc')
            ->get();

        $media = DB::table('media')->where('model_type', 'App\Models\Document')
            ->where(function ($q) use($request){
                $q->where('file_name','like','%'.$request->input('name').'%')
                    ->orWhere('name','like','%'.$request->input('name').'%');
            })
            ->orderBy('file_name','asc')
            ->get();

        $result = [
            'name'=>'Showing Searched Result for "'.$request->input('name').'"',
            'documents'=>$document,
            'media'=>$media,
            'ancestors'=>[]
        ];
        return response()->json($result, 200);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'document_id' => 'numeric',
        ]);

        if ($request->exists('document_id')) {
            $document = auth()->user()->documents()->create(['name'=>$request->name]);
            Document::find($request->document_id)->appendNode($document);
        }else{
            $document = auth()->user()->documents()->create(['name'=>$request->name]);
            Document::whereIsRoot()->first()->appendNode($document);
        }

        return response()->json($document, 200);
    }

    /**
     * Display the specified resource.
     *
     * @return JsonResponse
     */
    public function show($document)
    {
        $result = Document::where('id', $document)->with(
            ['documents'=>function($q){
                $q->orderBy('name','asc');
            },
            'media',
            'ancestors'=>function($q){
                $q->orderBy('_lft','asc');
            }])->first();
        return response()->json($result, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Document $document
     * @return JsonResponse
     */
    public function update(Request $request, Document $document)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $document = auth()->user()->documents()->update($request->except(['_method' ]));
        return response()->json($document, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Document $document
     * @return JsonResponse
     */
    public function destroy(Document $document)
    {
        $document->delete();
        return response()->json("Successfully Deleted", 200);
    }

    /**
     * Upload documents from request.
     *
     * @param Request $request
     * @param Document $document
     * @return JsonResponse
     */
    public function upload(Request $request, Document $document)
    {
        $request->validate([
            'document.*' => 'file|max:102400',
        ]);

        if ($request->hasFile('document')) {
            $fileAdders = $document
                ->addMultipleMediaFromRequest(['document'])
                ->each(function ($fileAdder) {
                    $fileAdder->toMediaCollection();
                });
            $document->updated_at=Carbon::now();
            $document->save();
            return response()->json($document->getMedia(),200);
        }

        return response()->json('File Not Found',404);
    }

    public function renameMedia(Request $request ,Media $media){
        $request->validate([

        ]);
        $media->name = str_replace(['#', '/', '\\', ' '], '-',$media->name);
        $replaced_name = str_replace(['#', '/', '\\', ' '], '-',$request->name);
        $media->file_name = str_replace($media->name, $replaced_name, $media->file_name);
        $media->name = $request->name;
        $media->save();
        return response()->json($media,200);
    }

}
