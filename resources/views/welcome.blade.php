@extends('layouts.app')
@section('content')

    <div id="googleMap"></div>
    <div id="inputBigBubble" class="inputbigbubble" style="display: none;">
        <br><br><br><br><br><br>
        <div class="bigbubbletitle">Share what's happening</div>
        <form class="bigbubbleinputform_horizontal">
            <div class="form-group">
                <input type="text" class="form-control bubble-input" name="bubble-title" id="bubble-title"
                       maxlength="15" placeholder="Short title">
            </div>
            <div class="form-group">

                <textarea class="form-control bubble-input" name="bubble-text" id="bubble-text" maxlength="250"
                          placeholder="Description" rows="4"></textarea>
            </div>
            <div><br></div>
            <div class="form-group text-center">
                <a href="#" class="btn btn-outline-primary btn-rounded" id="bubble-ok">OK</a>
                <a href="#" class="btn btn-outline-primary" id="bubble-cancel">Cancel</a>
            </div>

        </form>


    </div>

    <div id="BigBubble" class="inputbigbubble" style="display: none;">
        <br><br><br><br>
        <div class="bigbubbleform_horizontal">
            <div id="bubbletitle" class="bigbubbleform_horizontal bigbubbletitle"></div>
            <br>

            <div id="bubbletext" class="bigbubbleform_horizontal bigbubbletext"></div>
            <div id="bubbleowner" class="bigbubbleform_horizontal bigbubbleredtext"></div>
            <div id="deleteButton" class="bigbubbleredtext"><img src="/img/delete.svg" width="24" id="deleteButton"></div>
            <div id="shareButton" class="bigbubbleredtext"><img src="/img/share.svg" width="24" id="shareButton"></div>
            <div class="form-group">

            </div>
            <div class="vote-buttons" id="vote_buttons" style="display: none;">
                <img src="/img/down.svg" width="100" id="downvote">

                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <img src="/img/up.svg" width="100" id="upvote">
            </div>


        </div>


    </div>



@endsection
@section('scripts')
    <script>

        var orgbubbles = [];
        var loadedbubbles = [];
        var markers = [];
        var userid;
        var map;
        var deeplinkexecuted = false;


        function loadBubbles() {
            $.getJSON('/getbubbles', function (bubbles) {
                loadedbubbles = bubbles;
                prepareBubbles();
            });
        }


        function prepareBubbles() {
            if(!(JSON.stringify(loadedbubbles) === JSON.stringify(orgbubbles))) {
                addNewBubbles(getNewBubbles(loadedbubbles, orgbubbles));
                updateChangedBubbles(getChangedBubbles(loadedbubbles, orgbubbles));
                removeObseleteBubbles(getObseleteBubbles(loadedbubbles, orgbubbles));
            }



            @if(isset($bubble))
            if (!deeplinkexecuted) {
                deeplinkexecuted = true;
                markerOnDblClick(@json($bubble));
            }
            @endif

        }

        function resizeMarkers(){
            $.each(markers,function(index){
                resizeMarker(markers[index].marker,orgbubbles[index]);
            })
            console.log(14 -map.getZoom());
        }
        function resizeMarker(marker,bubble){
            minbubblesize = 15;
            votesmultiplier = getvotesmultiplier(bubble.upvotes-bubble.downvotes);
            zoommultiplier = 14 - map.getZoom();
            bubblezize = (70 * votesmultiplier) - (zoommultiplier *70) < minbubblesize?minbubblesize:(70 * votesmultiplier) - (zoommultiplier *70);

            marker.setIcon(
                new google.maps.MarkerImage(
                    marker.getIcon().url, //marker's same icon graphic
                    null,//size
                    null,//origin
                    null, //anchor
                    new google.maps.Size(bubblezize, bubblezize) //changes the scale
                )
            )
            var labelObj = {};
            var label = marker.getLabel().text;
            var fontsize = (votesmultiplier * 6) -(zoommultiplier*6) +"px";
            console.log('font: '+fontsize);
            labelObj.fontSize =fontsize;
            labelObj.text=label;

            marker.setLabel(labelObj)
        }
        var resolvePromise = null;
        var promise = new Promise(function(resolve, reject) {
            resolvePromise = resolve;
        });
        promise.then(function() {
            console.log('loaded');
            setupMapAndBubbles();
        });


function setupMapAndBubbles(){
    drawTheMap();
    @if(!isset($bubble))
    checkGeolocation();
    @endif
    setInterval(function () {
        loadBubbles()
    }, 1000);
}





        jQuery(document).ready(function () {


            $('#BigBubble').click(function(e) {
                $('#BigBubble').hide();
                loadBubbles();
            })
            $('#deleteButton').click(function(e){
                deleteBubble();
            })

            $('#upvote').click(function(e) {
                voteBubble(1);
                loadBubbles();
                $('#inputBigBubble').hide();

            })

            $('#downvote').click(function(e) {
                voteBubble(-1);
                loadBubbles();
                $('#inputBigBubble').hide();
            })
            $('#bubble-ok').click(function(e) {
                var bubbletitle = $('#bubble-title').val();
                var bubbletext = $('#bubble-text').val();
                createNewMarker(bubbletitle,bubbletext);
                $('#inputBigBubble').hide();
            })
            $('#bubble-cancel').click(function(e) {
                $('#inputBigBubble').hide();
            })

        });
        @auth
            userid = {{Auth::id()}};
        @endauth



    </script>

@stop
