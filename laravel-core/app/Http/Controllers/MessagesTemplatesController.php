<?php

namespace App\Http\Controllers;

use App\Models\MessagesTemplates;
use App\Http\Requests\StoreMessagesTemplatesRequest;
use App\Http\Requests\UpdateMessagesTemplatesRequest;

class MessagesTemplatesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $template = MessagesTemplates::where('deleted_at', null)->orderBy('created_at', 'desc')->paginate(10)->onEachSide(2); 
        return view('pages.messagestemplates')->with('messagestemplates', $template);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreMessagesTemplatesRequest $request)
    {
        $photos = [];
        $videos = [];
        $audios = [];
        if($request->hasFile('photos'))
        {
            foreach($request->file('photos') as $photo){
                $filename = time().$this->generateRandomUniqueName(12). '.' .$photo->getClientOriginalExtension();
                $photo->move(public_path('storage/remarketing'), $filename);
                $photos[] = asset('storage/remarketing/' . $filename);
            }
        }
        if($request->hasFile('videos'))
        {
            foreach($request->file('videos') as $video){
                $filename = time().$this->generateRandomUniqueName(12).'.'.$video->getClientOriginalExtension();
                $video->move(public_path('storage/remarketing'), $filename);
                $videos[] = asset('storage/remarketing/' . $filename);
            }
        }
        if($request->hasFile('audios'))
        {
            foreach($request->file('audios') as $audio){
                $filename = time().$this->generateRandomUniqueName(12).'.'.$audio->getClientOriginalExtension();
                $audio->move(public_path('storage/remarketing'), $filename);
                $audios[] = asset('storage/remarketing/' . $filename);
            }
        }
        /*
        if($request->hasFile('photos'))
        {
            foreach($request->file('photos') as $photo){
                $filename = str_replace(',', '', time() . '_' . $photo->getClientOriginalName());
                $photo->move(public_path('storage/remarketing'), $filename);
                $photos[] = asset('storage/remarketing/' . $filename);
            }
        }
        if($request->hasFile('videos'))
        {
            foreach($request->file('videos') as $video){
                $filename = str_replace(',', '', time() . '_' . $video->getClientOriginalName());
                $video->move(public_path('storage/remarketing'), $filename);
                $videos[] = asset('storage/remarketing/' . $filename);
            }
        }
        if($request->hasFile('audios'))
        {
            foreach($request->file('audios') as $audio){
                $filename = str_replace(',', '', time() . '_' . $audio->getClientOriginalName());
                $audio->move(public_path('storage/remarketing'), $filename);
                $audios[] = asset('storage/remarketing/' . $filename);
            }
        }*/
        MessagesTemplates::create([
            'name' => $request->input('name'),
            'photos' => implode(',', $photos),
            'video' => implode(',', $videos),
            'audios' => implode(',', $audios),
            'message' => $request->input('message'),
        ]);
        return back()->with("success", "template has been created successfully");
    } 
    private function generateRandomUniqueName($length = 8) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randomName = '';
        for ($i = 0; $i < $length; $i++) {
            $randomName .= $characters[rand(0, strlen($characters) - 1)];
        }
        return $randomName;
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateMessagesTemplatesRequest $request, MessagesTemplates $template)
    {
        $photos = [];
        $videos = [];
        $audios = [];
        if($request->hasFile('photos'))
        {
            foreach($request->file('photos') as $photo){
                $filename = time().$this->generateRandomUniqueName(12).'.'.$photo->getClientOriginalExtension();
                $photo->move(public_path('storage/remarketing'), $filename);
                $photos[] = asset('storage/remarketing/' . $filename);
            }
        }
        if($request->has('oldPhotos')){
            foreach($request->oldPhotos as $oldPhoto){
                $photos[] = $oldPhoto;
            }
        }
        if($request->hasFile('videos'))
        {
            foreach($request->file('videos') as $video){
                $filename = time().$this->generateRandomUniqueName(12).'.'.$video->getClientOriginalExtension();
                $video->move(public_path('storage/remarketing'), $filename);
                $videos[] = asset('storage/remarketing/' . $filename);
            }
        }
        if($request->has('oldVideos')){
            foreach($request->oldVideos as $oldVideo){
                $videos[] = $oldVideo;
            }
        }
        if($request->hasFile('audios'))
        {
            foreach($request->file('audios') as $audio){
                $filename = time().$this->generateRandomUniqueName(12).'.'.$audio->getClientOriginalExtension();
                $audio->move(public_path('storage/remarketing'), $filename);
                $audios[] = asset('storage/remarketing/' . $filename);
            }
        }
        if($request->has('oldAudios')){
            foreach($request->oldAudios as $oldAudio){
                $audios[] = $oldAudio;
            }
        }
        $template->update([
            'name' => $request->input('name'),
            'photos' => implode(',', $photos),
            'video' => implode(',', $videos),
            'audios' => implode(',', $audios),
            'message' => $request->input('message'),
        ]);
        return back()->with("success", "template has been updated successfully");

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MessagesTemplates $template)
    {
        $template->update(['deleted_at' => now()]);
        return back()->with("success", "template has been deleted successfully");
    }
}
