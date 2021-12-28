@extends('layouts.app')

@section('content')
    <div id="googleMap"></div>


@endsection

@section('scripts')
        <script>
            var map;
            var myMap = function myMap() {
                var location = {lat: 52.363953, lng: 4.882714};
                var marker;
                var mapProp= {
                    center:new google.maps.LatLng(52.364061,4.882769),
                    zoom:14,
                    mapTypeId: google.maps.MapTypeId.ROADMAP,
                    disableDefaultUI: true,
                    styles:
                        [
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


                map = new google.maps.Map(document.getElementById("googleMap"),mapProp);
                addEventListener();
                addWhereAmIbutton();

                function placeMarker(position, map) {
                    var bubbletext = window.prompt("Please enter your name", "TEO");


                    var zoomLevel = map.getZoom()+'';
                    marker = new google.maps.Marker({
                        position: position,
                        icon: 'img/minibubble.png',
                        iconAnchor: new google.maps.Point(255.498,-26.204),
                        label: { color: '#FF0000', fontWeight: 'bold', fontSize: zoomLevel , text: bubbletext},
                        map: map
                    });
                    map.panTo(position);
                    saveMarker(bubbletext,position.lng,position.lat)
                }




            }
            function addMarker(text,position){

                marker = new google.maps.Marker({
                    position: position,
                    icon: 'img/minibubble.png',
                    iconAnchor: new google.maps.Point(255.498,-26.204),
                    label: { color: '#FF0000', fontWeight: 'bold', fontSize: '14' , text: text},
                    map: map
                });
            }
            function addWhereAmIbutton(){
                infoWindow = new google.maps.InfoWindow();

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
                    infoWindow.setContent("Estoy aqui");
                    infoWindow.open(map);
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

            function saveMarker(text,lat,long) {
                        @auth
                            var userid = {{Auth::id()}};
                        @endauth
                    $.ajax({
                    url: '/addbubble/',
                    type: "GET",
                    dataType: "json",
                    data : { userid: userid, text : text, lat : lat, long:long },
                    success: function (data) {



                    }
                });
            }
            function addEventListener(){
                map.addListener('click', function(e) {
                    @auth
                        placeMarker(e.latLng, map);
                    @endauth
                    @guest
                        window.alert("Please login to add event");
                    @endguest
                });

            }

            jQuery(document).ready(function () {

                $.getJSON('/getbubbles', function(data) {
                    $.each(data, function(index) {

                        var position = new google.maps.LatLng(data[index].longitude, data[index].latitude);
                        addMarker(data[index].text,position)
                    });
                });
            })




        </script>


@stop