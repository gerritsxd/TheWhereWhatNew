@extends('layouts.app')

@section('content')
    <div id="googleMap"></div>


@endsection

@section('scripts')
        <script>
        function myMap() {
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


            var map = new google.maps.Map(document.getElementById("googleMap"),mapProp);
            map.addListener('click', function(e) {
                placeMarker(e.latLng, map);
            });

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
            }
            google.maps.event.addListener(map, 'zoom_changed', function() {

                var zoom = map.getZoom();
                markerWidth = (zoom/9)*34
                markerHeight = (zoom/9)*34


                //set the icon with the new size to the marker
                marker.setIcon({
                    url: 'img/minibubble.png',
                    scaledSize: new google.maps.Size(markerWidth, markerHeight)
                });
            });



        }


    </script>
<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyA3nWMXuE4-LK2T2ALH6scWLhvta1B0PD0&callback=myMap"></script>

@stop