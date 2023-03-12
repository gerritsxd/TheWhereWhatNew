<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBubbleRequest;
use App\Http\Requests\UpdateBubbleRequest;
use App\Models\Bubble;
use App\Models\UserVote;
use Carbon\Carbon;
use function compact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;



class BubbleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
     * @param  \App\Http\Requests\StoreBubbleRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreBubbleRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Bubble $bubble
     * @return \Illuminate\Http\Response
     */
    public function show(Bubble $bubble)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Bubble $bubble
     * @return \Illuminate\Http\Response
     */
    public function edit(Bubble $bubble)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateBubbleRequest $request
     * @param  \App\Models\Bubble $bubble
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateBubbleRequest $request, Bubble $bubble)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Bubble $bubble
     * @return \Illuminate\Http\Response
     */
    public function destroy(Bubble $bubble)
    {
        //
    }

    public function addBubble(Request $request)
    {

        $bubble = new Bubble();
        Log::debug($request);
        $bubble->userid = $request->get('userid') ?? 1;
        $bubble->longitude = $request->get('long');
        $bubble->latitude = $request->get('lat');
        $bubble->title = $request->get('title');
        $bubble->text = $request->get('text');
        $bubble->upvotes = 1;
        $bubble->downvotes = 0;

        $bubble->bubble_type = 1;
        $bubble->save();

        return ($bubble->id);


    }

    public function getBubbles()
    {
        $bubbles = Bubble::get();
        //Log::debug('bubbles:'.$bubbles[0]->user);
        return $bubbles;


    }

    public function voteBubble(Request $request)
    {

        $bubble = Bubble::find($request->get('id'));
        if (UserVote::where('user_id', Auth::user()->id)->where('bubble_id', $bubble->id)->exists()) {
            return 'false';
        } else {
            if ($request->get('vote') > 0) {
                $bubble->increment('upvotes');
                $userVote = new UserVote(['user_id' => Auth::user()->id, 'bubble_id' => $bubble->id, 'upvote' => true]);
            } else {
                $bubble->increment('downvotes');
                $userVote = new UserVote(['user_id' => Auth::user()->id, 'bubble_id' => $bubble->id, 'upvote' => false]);
            }

            $userVote->save();
            return 'true';
        }

    }

    public function deleteBubble(Request $request)
    {
        //Log::debug('enter delete');
        $bubble = Bubble::find($request->get('bubbleid'));
        $bubble->delete();
        return "SUCCESS";
    }

    public function startCropImage(Request $request){
        $bubbleID =$request->bubbleID;
        return view('cropimage',compact('bubbleID'));
    }

    public function cropImage(Request $request){

        $imageName = $request->bubbleID;
        Log::debug("Storing image");
        Log::debug("Storing image:"."/{$imageName}.png");
        $img = $request->image;
        $img = str_replace('data:image/png;base64,', '', $img);
        $img = str_replace(' ', '+', $img);
        $data = base64_decode($img);

        Storage::put("public/{$imageName}.png",  $data);
       // $request->image->move(public_path('images'), $imageName);
        return 'SUCCES';
    }
}
