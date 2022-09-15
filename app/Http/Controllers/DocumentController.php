<?php

namespace App\Http\Controllers;

use App\Models\Document;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\MediaLibrary\Support\MediaStream;


class DocumentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $document = Document::whereIsRoot()->with(
            ['documents'=>function($q){
                $q->orderBy('updated_at','desc')
                ->orderBy('name','asc');
            },'media',
            'ancestors'=>function($q){
                $q->orderBy('_lft','asc');
            }])->first();
        return response()->json($document, 200);
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
     * @param  \App\Models\Document  $document
     * @return \Illuminate\Http\Response
     */
    public function show($document)
    {
        $result = Document::where('id', $document)->with(
            ['documents'=>function($q){
                $q->orderBy('updated_at','desc');
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
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Document  $document
     * @return \Illuminate\Http\Response
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
     * @param  \App\Models\Document  $document
     * @return \Illuminate\Http\Response
     */
    public function destroy(Document $document)
    {
        $document->delete();
        return response()->json("Successfully Deleted", 200);
    }

    public function upload(Request $request, $id)
    {
        if ($request->hasFile('document')) {
            $document = Document::find($id);
            $document->addMedia($request->document)->toMediaCollection();
            $document->updated_at=Carbon::now();
            $document->save();
            return response()->json('Uploaded Successfully',200);
        }else{
            return response()->json('File Not Found',404);
        }
    }

    public function download(Request $request, $id)
    {
        
        $media = Media::where('id',$id)->first();
        return $media->toResponse($request);
        $headers = [
            'Content-Type' => $media->mime_type,
         ];
        return response()->json($media->getpath());

    }


    public function breadcrumb(Document $document)
    {
        $document->with(['document'=>function($q){
            $q->with('document');
        }]);
    }
}
