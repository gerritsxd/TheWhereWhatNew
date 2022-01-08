@extends('layouts.app')

@section('content')

    <div id="googleMap"></div>
    <div id="inputBigBubble" class="inputbigbubble" style="display: none;">
        <br><br><br><br><br><br><br>
        <form class="bigbubbleform_horizontal">
            <div class="form-group">
                <input type="text" class="form-control bubble-input" name="bubble-title" id="bubble-title"  maxlength="15" placeholder="Title">
            </div>
            <div class="form-group">

                <textarea class="form-control bubble-input" name="bubble-text" id="bubble-text"  maxlength="100" placeholder="Text" rows="4"></textarea>
            </div>
            <div><br></div>
            <div class="form-group text-center">
                <a href="#" class="btn btn-outline-primary btn-rounded" id="bubble-ok" >OK</a>
                <a href="#" class="btn btn-outline-primary" id="bubble-cancel" >Cancel</a>
            </div>

        </form>


    </div>

    <div id="BigBubble" class="inputbigbubble" style="display: none;">
        <br><br><br><br>
        <div class="bigbubbleform_horizontal">
            <div  id="bubbletitle" class="bigbubbleform_horizontal bigbubbletitle"></div>
            <div  id="bubbletext" class="bigbubbleform_horizontal bigbubbletext"></div>
            <div class="form-group">

                </div>
            <div class="vote-buttons">
                <img src="/img/down.svg" width="100" id="downvote">

                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <img src="/img/up.svg" width="100" id="upvote">
            </div>


        </div>


    </div>


@endsection

@section('scripts')
    <script>
        var map;
        var infowindow;
        var markers = [];
        var texts = [];
        var markerids = [];
        var bubblepos;
        var activemarkerid;
        var userid;



        var drawTheMap = function drawTheMap() {
            var location = {lat: 52.363953, lng: 4.882714};
            var marker;
            var mapProp = {
                center: new google.maps.LatLng(52.364061, 4.882769),
                zoom: 14,
                mapTypeId: google.maps.MapTypeId.ROADMAP,
                disableDefaultUI: true,
                gestureHandling: "greedy",
                draggableCursor: 'crosshair',
                styles: [
                    {
                        "featureType": "administrative.country",
                        "elementType": "geometry.fill",
                        "stylers": [
                            {
                                "color": "#378a00"
                            }
                        ]
                    },
                    {
                        "featureType": "landscape.man_made",
                        "elementType": "geometry.fill",
                        "stylers": [
                            {
                                "color": "#998fe6"
                            }
                        ]
                    },
                    {
                        "featureType": "landscape.natural",
                        "elementType": "geometry.fill",
                        "stylers": [
                            {
                                "color": "#2c9905"
                            }
                        ]
                    },
                    {
                        "featureType": "landscape.natural.terrain",
                        "elementType": "geometry.fill",
                        "stylers": [
                            {
                                "color": "#bc7906"
                            }
                        ]
                    },
                    {
                        "featureType": "poi.attraction",
                        "elementType": "geometry.fill",
                        "stylers": [
                            {
                                "color": "#3606bc"
                            }
                        ]
                    },
                    {
                        "featureType": "poi.business",
                        "elementType": "geometry.fill",
                        "stylers": [
                            {
                                "color": "#6d6d6f"
                            }
                        ]
                    },
                    {
                        "featureType": "poi.government",
                        "elementType": "geometry.fill",
                        "stylers": [
                            {
                                "color": "#6d6d6f"
                            }
                        ]
                    },
                    {
                        "featureType": "poi.medical",
                        "elementType": "geometry.fill",
                        "stylers": [
                            {
                                "color": "#940025"
                            }
                        ]
                    },
                    {
                        "featureType": "poi.park",
                        "elementType": "geometry.fill",
                        "stylers": [
                            {
                                "color": "#4cb625"
                            }
                        ]
                    },
                    {
                        "featureType": "poi.place_of_worship",
                        "elementType": "geometry.fill",
                        "stylers": [
                            {
                                "color": "#b719c2"
                            }
                        ]
                    },
                    {
                        "featureType": "poi.school",
                        "elementType": "geometry.fill",
                        "stylers": [
                            {
                                "color": "#7619c2"
                            }
                        ]
                    },
                    {
                        "featureType": "poi.sports_complex",
                        "elementType": "geometry.fill",
                        "stylers": [
                            {
                                "color": "#8026c9"
                            }
                        ]
                    },
                    {
                        "featureType": "transit.station.airport",
                        "elementType": "geometry.fill",
                        "stylers": [
                            {
                                "color": "#a8a8a8"
                            }
                        ]
                    },
                    {
                        "featureType": "water",
                        "elementType": "geometry.fill",
                        "stylers": [
                            {
                                "color": "#3d28e2"
                            }
                        ]
                    }
                ]
            };
            map = new google.maps.Map(document.getElementById("googleMap"), mapProp);
            map.addListener('click', function (e) {
                @auth
                 map.setZoom(17);
                 map.panTo(e.latLng);
                 map.panBy(10,-200);
                bubblepos = e.latLng;
                var inputBigBubble = document.getElementById("inputBigBubble");
                if (inputBigBubble.style.display === "none") {
                    inputBigBubble.style.display = "block";
                }
                @endauth
                @guest
                    window.alert("Please login to add event");
                @endguest
            });
            map.addListener('zoom_changed', function() {
                for (var i=0, len = markers.length; i < len; i++) {
                    // set new icon depending on the value of map.getZoom()

                }
            });
            addWhereAmIbutton();
            checkGeolocation();
        }
        function createNewMarker(bubbletitle,bubbletext) {

            var zoomLevel = map.getZoom() + '';
            marker = new google.maps.Marker({
                position: bubblepos,
                icon: 'img/bubble.svg',
                iconAnchor: new google.maps.Point(255.498, -26.204),
                label: {color: '#FF0000', fontWeight: 'bold', fontSize: zoomLevel, text: bubbletitle},
                title:bubbletext,
                map: map
            });
            map.panTo(bubblepos);
            saveMarker(bubbletitle, bubbletext,bubblepos.lng, bubblepos.lat)
        }
        function addMarker(id, title, text, position, updated_at,upvotes,downvotes) {
            if (!markerids.includes(id)) {
                markerids.push(id);
                var seconds = Math.floor((new Date() - new Date(updated_at).getTime()) / 1000);
                interval = seconds / 3600; //hours
                opacity = 1 / interval;
                //text = text + '<br> hace: '+Math.round(interval * 10) / 10+'horas';
                texts[id]=text;
                bubblezize = 70 * (upvotes-downvotes);
                markers[id] = new google.maps.Marker({
                    position: position,
                    icon: {url:'img/bubble.svg', scaledSize: new google.maps.Size(bubblezize, bubblezize)},
                    opacity: opacity,
                    label: {color: '#FF0000', fontWeight: 'bold', fontSize: '14', text: title},
                    optimized: true,
                    map: map
                });
                markers[id].addListener('dblclick', function () {
                    markers[id].setMap(null);

                    activemarkerid = id;
                    map.setZoom(17);
                    map.panTo(markers[id].position);
                    map.panBy(10,-200);
                    $('#bubbletitle').html(markers[id].label.text);
                    $('#bubbletext').html(texts[id]);
                    document.getElementById("BigBubble").style.display = "block";


                });
            }
        }

        function saveMarker(title,text, lat, long) {

            $.ajax({
                url: '/addbubble/',
                type: "GET",
                dataType: "json",
                data: {userid: userid, title:title, text: text, lat: lat, long: long},
                statusCode: {
                    403: function() {
                        window.location.href = "/email/verify";
                    }},
                success: function (data) {


                },
                fail: function(xhr, textStatus, errorThrown){
                    alert('request failed');
                }
            });
        }
        function voteBubble(vote){

            $.ajax({
                url: '/votebubble/',
                type: "GET",
                dataType: "json",
                data: {userid: userid, id:activemarkerid , vote:vote},
                statusCode: {
                    403: function() {
                        window.location.href = "/email/verify";
                    }},
                success: function (data) {
                    console.log(data);

                },
                fail: function(xhr, textStatus, errorThrown){
                    alert('request failed');
                }
            });

        }
        function addWhereAmIbutton() {
            infoWindow = new google.maps.Marker;

            const locationButton = document.createElement("button");
            locationButton.backgroundImage = "/img/mylocation.svg";
            locationButton.textContent = "Find me";
            locationButton.classList.add("btn");
            locationButton.classList.add("btn-secondary");
            map.controls[google.maps.ControlPosition.TOP_CENTER].push(locationButton);
            locationButton.addEventListener("click", () => {
                // Try HTML5 geolocation.
                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(
                        (position) => {
                            const pos = {
                                lat: position.coords.latitude,
                                lng: position.coords.longitude,
                            };

                            infoWindow.setPosition(pos);
                            map.setCenter(pos);
                        },
                        () => {
                            handleLocationError(true, infoWindow, map.getCenter());
                        }
                    );
                } else {
                    // Browser doesn't support Geolocation
                    handleLocationError(false, infoWindow, map.getCenter());
                }
            });
        }
        function getCoords() {
            return new Promise((resolve, reject) =>
                navigator.permissions ?

                    // Permission API is implemented
                    navigator.permissions.query({
                        name: 'geolocation'
                    }).then(permission =>
                        // is geolocation granted?
                        permission.state === "granted"
                            ? navigator.geolocation.getCurrentPosition(pos => resolve(pos.coords))
                            : reject()
                    ) :

                    // Permission API was not implemented
                    reject(new Error("Permission API is not supported"))
            )
        }
        function checkGeolocation(){
            coords=new google.maps.LatLng(52.364061, 4.882769);
            getCoords().then(
                    coords => map.panTo(new google.maps.LatLng(coords.latitude,coords.longitude)),
                    reject => map.panTo(new google.maps.LatLng(52.364061, 4.882769))
            )



        }
        jQuery(document).ready(function () {

            loadMarkers();
            setInterval(function () {
                loadMarkers()
            }, 1000);
            function loadMarkers() {
                $.getJSON('/getbubbles', function (data) {
                    $.each(data, function (index) {
                        var position = new google.maps.LatLng(data[index].longitude, data[index].latitude);
                        addMarker(data[index].id, data[index].title, data[index].text, position, data[index].updated_at,data[index].upvotes,data[index].downvotes)
                    });
                });
            }

            $('#BigBubble').click(function(e) {
                $('#BigBubble').hide();
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
            $('#upvote').click(function(e) {
                voteBubble(1);
                const index = markerids.indexOf(activemarkerid);
                if (index > -1) {
                    markerids.splice(index, 1);
                }
                $('#inputBigBubble').hide();
                loadMarkers();
            })
            $('#downvote').click(function(e) {
                voteBubble(-1);
                const index = markerids.indexOf(activemarkerid);
                if (index > -1) {
                    markerids.splice(index, 1);
                }
                $('#inputBigBubble').hide();
                loadMarkers();
            })
            @auth
                userid = {{Auth::id()}};
            @endauth
        })

    </script>


@stop