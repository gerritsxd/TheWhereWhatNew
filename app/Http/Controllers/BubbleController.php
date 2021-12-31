<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBubbleRequest;
use App\Http\Requests\UpdateBubbleRequest;
use App\Models\Bubble;
use Carbon\Carbon;
use Illuminate\Http\Request;


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
     * @param  \App\Http\Requests\StoreBubbleRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreBubbleRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Bubble  $bubble
     * @return \Illuminate\Http\Response
     */
    public function show(Bubble $bubble)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Bubble  $bubble
     * @return \Illuminate\Http\Response
     */
    public function edit(Bubble $bubble)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateBubbleRequest  $request
     * @param  \App\Models\Bubble  $bubble
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateBubbleRequest $request, Bubble $bubble)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Bubble  $bubble
     * @return \Illuminate\Http\Response
     */
    public function destroy(Bubble $bubble)
    {
        //
    }

    public function addBubble(Request $request){

        $bubble = new Bubble();

        $bubble->userid = $request->get('userid');
        $bubble->longitude = $request->get('long');
        $bubble->latitude = $request->get('lat');
        $bubble->title = $request->get('title');
        $bubble->text = $request->get('text');

        $bubble->bubble_type = 1;
        $bubble->save();

        return ('SUCCESS');




    }

    public function getBubbles(){
        $bubbles = Bubble::where('updated_at', '>', Carbon::now()->subHours(24)->toDateTimeString())->get();
        return $bubbles;



    }
}
