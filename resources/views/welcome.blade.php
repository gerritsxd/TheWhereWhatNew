@extends('layouts.app')

@section('content')

    <div id="googleMap"></div>
    <div id="inputBigBubble" class="inputbigbubble" style="display: none;">
        <br><br><br><br><br><br><br>
        <form class="bigbubbleform_horizontal">
            <div class="form-group">
                <input type="text" class="form-control" name="bubble-title" id="bubble-title" placeholder="Title">
            </div>
            <div class="form-group">

                <textarea class="form-control" name="bubble-text" id="bubble-text" placeholder="Text" rows="4"></textarea>
            </div>
            <div class="form-group text-center">
                <a href="#" class="btn btn-primary" id="bubble-ok" >OK</a>
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


        var drawTheMap = function drawTheMap() {
            var location = {lat: 52.363953, lng: 4.882714};
            var marker;
            var mapProp = {
                center: new google.maps.LatLng(52.364061, 4.882769),
                zoom: 14,
                mapTypeId: google.maps.MapTypeId.ROADMAP,
                disableDefaultUI: true,
                gestureHandling: "greedy",
                styles: [
                    {
                        "elementType": "geometry",
                        "stylers": [
                            {
                                "color": "#ebe3cd"
                            }
                        ]
                    },
                    {
                        "elementType": "labels.text.fill",
                        "stylers": [
                            {
                                "color": "#523735"
                            }
                        ]
                    },
                    {
                        "elementType": "labels.text.stroke",
                        "stylers": [
                            {
                                "color": "#f5f1e6"
                            }
                        ]
                    },
                    {
                        "featureType": "administrative",
                        "elementType": "geometry.stroke",
                        "stylers": [
                            {
                                "color": "#c9b2a6"
                            }
                        ]
                    },
                    {
                        "featureType": "administrative.land_parcel",
                        "stylers": [
                            {
                                "visibility": "off"
                            }
                        ]
                    },
                    {
                        "featureType": "administrative.land_parcel",
                        "elementType": "geometry.stroke",
                        "stylers": [
                            {
                                "color": "#dcd2be"
                            }
                        ]
                    },
                    {
                        "featureType": "administrative.land_parcel",
                        "elementType": "labels.text.fill",
                        "stylers": [
                            {
                                "color": "#ae9e90"
                            }
                        ]
                    },
                    {
                        "featureType": "administrative.neighborhood",
                        "stylers": [
                            {
                                "visibility": "off"
                            }
                        ]
                    },
                    {
                        "featureType": "landscape.man_made",
                        "elementType": "geometry",
                        "stylers": [
                            {
                                "color": "#92a0d3"
                            }
                        ]
                    },
                    {
                        "featureType": "landscape.natural",
                        "elementType": "geometry",
                        "stylers": [
                            {
                                "color": "#6b8b50"
                            }
                        ]
                    },
                    {
                        "featureType": "poi",
                        "elementType": "geometry",
                        "stylers": [
                            {
                                "color": "#6aaad2"
                            }
                        ]
                    },
                    {
                        "featureType": "poi",
                        "elementType": "labels.text.fill",
                        "stylers": [
                            {
                                "color": "#93817c"
                            }
                        ]
                    },
                    {
                        "featureType": "poi.business",
                        "stylers": [
                            {
                                "visibility": "off"
                            }
                        ]
                    },
                    {
                        "featureType": "poi.park",
                        "elementType": "geometry",
                        "stylers": [
                            {
                                "color": "#7184d1"
                            }
                        ]
                    },
                    {
                        "featureType": "poi.park",
                        "elementType": "geometry.fill",
                        "stylers": [
                            {
                                "color": "#628f3d"
                            }
                        ]
                    },
                    {
                        "featureType": "poi.park",
                        "elementType": "labels.text",
                        "stylers": [
                            {
                                "visibility": "off"
                            }
                        ]
                    },
                    {
                        "featureType": "poi.park",
                        "elementType": "labels.text.fill",
                        "stylers": [
                            {
                                "color": "#447530"
                            }
                        ]
                    },
                    {
                        "featureType": "road",
                        "elementType": "geometry",
                        "stylers": [
                            {
                                "color": "#f5f1e6"
                            }
                        ]
                    },
                    {
                        "featureType": "road",
                        "elementType": "labels",
                        "stylers": [
                            {
                                "visibility": "off"
                            }
                        ]
                    },
                    {
                        "featureType": "road.arterial",
                        "elementType": "geometry",
                        "stylers": [
                            {
                                "color": "#fdfcf8"
                            }
                        ]
                    },
                    {
                        "featureType": "road.highway",
                        "elementType": "geometry",
                        "stylers": [
                            {
                                "color": "#f8c967"
                            }
                        ]
                    },
                    {
                        "featureType": "road.highway",
                        "elementType": "geometry.stroke",
                        "stylers": [
                            {
                                "color": "#e9bc62"
                            }
                        ]
                    },
                    {
                        "featureType": "road.highway.controlled_access",
                        "elementType": "geometry",
                        "stylers": [
                            {
                                "color": "#e98d58"
                            }
                        ]
                    },
                    {
                        "featureType": "road.highway.controlled_access",
                        "elementType": "geometry.stroke",
                        "stylers": [
                            {
                                "color": "#db8555"
                            }
                        ]
                    },
                    {
                        "featureType": "road.local",
                        "elementType": "labels.text.fill",
                        "stylers": [
                            {
                                "color": "#806b63"
                            }
                        ]
                    },
                    {
                        "featureType": "transit.line",
                        "elementType": "geometry",
                        "stylers": [
                            {
                                "color": "#dfd2ae"
                            }
                        ]
                    },
                    {
                        "featureType": "transit.line",
                        "elementType": "labels.text.fill",
                        "stylers": [
                            {
                                "color": "#8f7d77"
                            }
                        ]
                    },
                    {
                        "featureType": "transit.line",
                        "elementType": "labels.text.stroke",
                        "stylers": [
                            {
                                "color": "#ebe3cd"
                            }
                        ]
                    },
                    {
                        "featureType": "transit.station",
                        "elementType": "geometry",
                        "stylers": [
                            {
                                "color": "#dfd2ae"
                            }
                        ]
                    },
                    {
                        "featureType": "transit.station.airport",
                        "elementType": "geometry",
                        "stylers": [
                            {
                                "color": "#787b7d"
                            }
                        ]
                    },
                    {
                        "featureType": "water",
                        "elementType": "geometry.fill",
                        "stylers": [
                            {
                                "color": "#355df4"
                            }
                        ]
                    },
                    {
                        "featureType": "water",
                        "elementType": "labels.text",
                        "stylers": [
                            {
                                "visibility": "off"
                            }
                        ]
                    },
                    {
                        "featureType": "water",
                        "elementType": "labels.text.fill",
                        "stylers": [
                            {
                                "color": "#92998d"
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
            infowindow = new google.maps.InfoWindow();
        }
        function createNewMarker(bubbletitle,bubbletext) {

            var zoomLevel = map.getZoom() + '';
            marker = new google.maps.Marker({
                position: bubblepos,
                icon: 'img/bubble.svg',
                iconAnchor: new google.maps.Point(255.498, -26.204),
                label: {color: '#FF0000', fontWeight: 'bold', fontSize: zoomLevel, text: bubbletitle},
                map: map
            });
            map.panTo(bubblepos);
            saveMarker(bubbletitle, bubbletext,bubblepos.lng, bubblepos.lat)
        }
        function addMarker(id, title, text, position, updated_at) {
            if (!markerids.includes(id)) {
                markerids.push(id);
                var seconds = Math.floor((new Date() - new Date(updated_at).getTime()) / 1000);
                interval = seconds / 3600; //hours
                opacity = 1 / interval;
                //text = text + '<br> hace: '+Math.round(interval * 10) / 10+'horas';
                texts[id]=text;
                markers[id] = new google.maps.Marker({
                    position: position,
                    icon: 'img/bubble.svg',
                    opacity: opacity,

                    label: {color: '#FF0000', fontWeight: 'bold', fontSize: '14', text: title},
                    optimized: true,
                    map: map
                });
                markers[id].addListener('dblclick', function () {
                    map.setZoom(17);
                    map.panTo(markers[id].position);
                    $('#bubbletitle').html(markers[id].label.text);
                    $('#bubbletext').html(texts[id]);
                    document.getElementById("BigBubble").style.display = "block";


                });
            }
        }

        function saveMarker(title,text, lat, long) {
                    @auth
            var userid = {{Auth::id()}};
            @endauth
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
        function addWhereAmIbutton() {
            infoWindow = new google.maps.Marker;

            const locationButton = document.createElement("button");

            locationButton.textContent = "¿Donde estoy?";
            locationButton.classList.add("custom-map-control-button");
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
        jQuery(document).ready(function () {
            loadMarkers();
            setInterval(function () {
                loadMarkers()
            }, 1000);
            function loadMarkers() {
                $.getJSON('/getbubbles', function (data) {
                    $.each(data, function (index) {
                        var position = new google.maps.LatLng(data[index].longitude, data[index].latitude);
                        addMarker(data[index].id, data[index].title, data[index].text, position, data[index].updated_at)
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
        })

    </script>


@stop