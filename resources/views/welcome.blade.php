@extends('layouts.app')
@section('content')

    <div id="googleMap"></div>
    <div id="instructions" class="instructionbubble " style="display: none;">
        <div class="bigbubbletitle"><br>How to use TheWhereWhat:<br><br><br></div>
        <div class="bigbubbleform_horizontal bigbubbletext">
        1. Register with your invitation code and verify your email address.<br>
        2. Drag across the screen to move around the map or click “Find me” to go to your
        location (Location must be ON on your browser).<br>
        3. Double click on the screen to write your own bubble. Try to summarise the nature of
        your activity on the title, as characters are limited. You can explain the activity in
        more depth in the description box.<br>
        4. Double click on bubbles to interact with them. If you wrote the bubble, you will be
        allowed to share it or delete it. If the bubble was placed by another user, you will be
        able to upvote it, downvote it and share it.<br>
        5. Have fun and stay safe.<br><br>

            <a class="bigbubbleform_horizontal btn btn-outline-primary" id="instructionsButton">Agree to Cookies and Close</a>
        </div>
    </div>

    </div>
    <div id="inputBigBubble" class="inputbigbubble" style="display: none;">
        <br><br><br><br><br><br>
        <div class="bigbubbletitle">He whats up here?</div>
        <form class="bigbubbleinputform_horizontal">
            <div class="form-group">
                <input type="text" class="form-control bubble-input" name="bubble-title" id="bubble-title"
                       maxlength="15" placeholder="Short title">
            </div>
            <div class="form-group">

                <textarea class="form-control bubble-input" name="bubble-text" id="bubble-text" maxlength="250"
                          placeholder="Description" rows="4"></textarea>
            </div>
            <div class="bigbubbletitle"><img src="/img/camera.svg" width="45px" id="cameraicon"></div>
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

                <div class="bigbubble_image" id="imagediv"></div>

                <div id="bubbletext" class="bigbubbleform_horizontal bigbubbletext"></div>

            <div id="bubbleowner" class="bigbubbleform_horizontal bigbubbleredtext"></div>

            <div id="deleteButton" class="deletbutton"><img src="/img/delete.svg" width="24" id="deleteButton"></div>
            <div id="shareButton" class="bigbubbleredtext"><img src="/img/share.svg" width="24" id="shareButton"></div>
            <div class="form-group">

            </div>
            <div class="vote-buttons" id="vote_buttons" style="display: none;">
                <img src="/img/downvote.svg" width="24" id="downvote">

                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <img src="/img/upvote.svg" width="24" id="upvote">
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
        var largestbubblesize = 0;


        function loadBubbles() {
            $.getJSON('/getbubbles', function (bubbles) {
                loadedbubbles = bubbles;
                prepareBubbles();
            });
        }


        function prepareBubbles() {
            if(!(JSON.stringify(loadedbubbles) === JSON.stringify(orgbubbles))) {
                    $.each(loadedbubbles,function(index){
                       bubblesize = loadedbubbles[index].upvotes - loadedbubbles[index].downvotes;
                        if((bubblesize)>largestbubblesize ){
                            largestbubblesize = bubblesize;
                        }
                    })
                console.log('LARGETS:'+largestbubblesize);
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
            var fontsize =  (votesmultiplier * 6) -(zoommultiplier*6)<0?0+"px":(votesmultiplier * 6) -(zoommultiplier*6) +"px";
            console.log('font: '+fontsize);
            labelObj.fontSize =fontsize;
            labelObj.text=label;

            marker.setLabel(labelObj)
        }



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
            setupMapAndBubbles();

            if (!cookieExists(COOKIE_NAME)){$('#instructions').show()}


            $('#instructionsButton').click(function(){
                $('#instructions').hide();
                consentWithCookies();
            })

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
                if (bubbletitle.length < 1 || bubbletext.length < 1) {
                    swall('You need a title, and text.');
                    return;
                }
                saveMarker(bubbletitle, bubbletext, bubblepos.lng, bubblepos.lat).then(

                )
               // createNewMarker(bubbletitle,bubbletext);
                $('#inputBigBubble').hide();
            })
            $('#cameraicon').click(function (e) {
                var bubbletitle = $('#bubble-title').val();
                var bubbletext = $('#bubble-text').val();
                if (bubbletitle.length < 1 || bubbletext.length < 1) {
                    swall('You need a title, and text.');
                    return;
                }
                saveMarker(bubbletitle, bubbletext, bubblepos.lng, bubblepos.lat).then(
                    function(value){
                        window.location.href = "/cropImage?bubbleID="+value;
                    }
                )
                //createNewMarker(bubbletitle,bubbletext);
                $('#inputBigBubble').hide();
            })
            $('#bubble-cancel').click(function(e) {
                $('#inputBigBubble').hide();
            })


        });

        @auth
            userid = {{Auth::id()}};
        @endauth



            const COOKIE_VALUE = 1;
            const COOKIE_DOMAIN = window.location.host;


        const COOKIE_NAME ='cookieConsent'

            function consentWithCookies() {
                setCookie(COOKIE_NAME, COOKIE_VALUE, 365 * 20);

            }

            function cookieExists(name) {
                return (document.cookie.split('; ').indexOf(name + '=' + COOKIE_VALUE) !== -1);
            }



            function setCookie(name, value, expirationInDays) {
                const date = new Date();
                date.setTime(date.getTime() + (expirationInDays * 24 * 60 * 60 * 1000));
                mycookie = name + '=' + value
                    + ';expires=' + date.toUTCString()
                    + ';domain=' + COOKIE_DOMAIN
                    + ';path=/{{ config('session.secure') ? ';secure' : null }}'
                    + '{{ config('session.same_site') ? ';samesite='.config('session.same_site') : null }}';

                document.cookie = mycookie;


            }

            if (cookieExists('COOKIE_NAME')) {
                hideCookieDialog();
            }









    </script>

@stop
