/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */
 require('./sw.js');
 require('./bootstrap');
 window.swall = require('sweetalert');
 
 window.Vue = require('vue').default;
 
 /**
  * The following block of code may be used to automatically register your
  * Vue components. It will recursively scan this directory for the Vue
  * components and automatically register them with their "basename".
  *
  * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
  */
 
 // const files = require.context('./', true, /\.vue$/i)
 // files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))
 
 Vue.component('example-component', require('./components/ExampleComponent.vue').default);
 
 /**
  * Next, we will create a fresh Vue application instance and attach it to
  * the page. Then, you may begin adding components to this application
  * or customize the JavaScript scaffolding to fit your unique needs.
  */
 
 const app = new Vue({
     el: '#app',
 });
 
 
 getObseleteBubbles = function (loadedbubbles, orgbubbles) {
     var obsolete_bubbles = [];
     $.each(orgbubbles, function (index) {
         if (loadedbubbles.some(bubble => bubble.id === orgbubbles[index].id)) {
         } else {
             obsolete_bubbles.push(orgbubbles[index])
         }
         ;
     });
     return obsolete_bubbles;
 }
 
 getNewBubbles = function (loadedbubbles, orgbubbles) {
     var new_bubbles = [];
     $.each(loadedbubbles, function (index) {
         if (orgbubbles.some(bubble => bubble.id === loadedbubbles[index].id)) {
         } else {
             new_bubbles.push(loadedbubbles[index])
         }
         ;
     })
     return new_bubbles;
 }
 
 getChangedBubbles = function (loadedbubbles, orgbubbles) {
     changed_bubbles = [];
     $.each(loadedbubbles, function (index) {
         if (JSON.stringify(loadedbubbles[index]) === JSON.stringify(orgbubbles[index])) {
         } else {
             changed_bubbles.push(loadedbubbles[index])
         }
     })
     return changed_bubbles;
 }
 updateChangedBubbles = function (bubbles) {
     $.each(bubbles, function (index) {
         changeMarker(bubbles[index]);
         var orgbubbleid = orgbubbles.findIndex(orgbubble => orgbubble.id === bubbles[index].id);
         orgbubbles[orgbubbleid] = bubbles[index];
     })
 
 }
 
 addNewBubbles = function (bubbles) {
     $.each(bubbles, function (index) {
         currentbubbleID = bubbles[index].id;
         let marker = addMarker(bubbles[index]);
         markers.push({currentbubbleID, marker});
         orgbubbles.push(bubbles[index]);
     })
 }
 
 removeObseleteBubbles = function (bubbles) {
     $.each(bubbles, function (index) {
         removeMarker(bubbles[index].id);
         orgbubbles = orgbubbles.filter(function (el) {
             return el.id != bubbles[index].id;
         });
         //orgbubbles.splice(bubbles.indexOf(bubbles[index].id),1)
         markers = markers.filter(function (el) {
             return el.currentbubbleID != bubbles[index].id;
         });
     })
 
 }
 
 changeMarker = function (bubble) {
 
     marker = markers.find(marker => marker.currentbubbleID === bubble.id).marker;
     minbubblesize = 15;
     votesmultiplier = getvotesmultiplier(bubble.upvotes - bubble.downvotes);
     zoommultiplier = 14 - map.getZoom();
     bubblezize = (70 * votesmultiplier) - (zoommultiplier * 70) < minbubblesize ? minbubblesize : (70 * votesmultiplier) - (zoommultiplier * 70);
 
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
     var fontsize = (votesmultiplier * 6) - (zoommultiplier * 6) + "px";
     labelObj.fontSize = fontsize + 'px';
     labelObj.text = label;
 
     marker.setLabel(labelObj)
 
 }
 
 removeMarker = function (bubbleID) {
     let obsoletemarker = markers.find(marker => marker.currentbubbleID === bubbleID);
     obsoletemarker.marker.setMap(null);
     var removeIndex = markers.map(item => item.id).indexOf(bubbleID);
     ~removeIndex && markers.splice(removeIndex, 1);
 
 }
 
 saveMarker = function (title, text, lat, long) {
     return new Promise((resolve, reject) => {
         $.ajax({
             url: '/addbubble/',
             type: "GET",
             dataType: "json",
             data: {userid: userid, title: title, text: text, lat: lat, long: long},
             statusCode: {
                 403: function () {
                     alert('your email has to be verified');
                     window.location.href = "/email/verify";
                 },
                 401: function () {
                     alert('you have to log in');
                     window.location.href = "/login";
                 }
             },
             success: function (data) {
                 resolve(data);
 
             },
             fail: function (xhr, textStatus, errorThrown) {
                 reject('request failed');
             }
         });
     })
 }
 
 // createNewMarker = function (bubbletitle, bubbletext) {
 //     map.panTo(bubblepos);
 //     if (bubbletitle.length < 1 || bubbletext.length < 1) {
 //         swall('You need a title, and text.')
 //     } else {
 //         return saveMarker(bubbletitle, bubbletext, bubblepos.lng, bubblepos.lat);
 //     }
 //
 // }
 
 markerOnDblClick = function (bubble) {
    activemarkerid = bubble.id;
    map.setZoom(16);
    map.panTo(new google.maps.LatLng(bubble.longitude, bubble.latitude));
    map.panBy(10, -200);
    $('#bubbletitle').html(bubble.title);
    $('#bubbletext').html(bubble.text);
  
    // Animate the map view
    const bubblePos = new google.maps.LatLng(bubble.latitude, bubble.longitude);
    const targetZoom = 18;
    const targetTilt = 45;
    const targetAngle = 45;
    const duration = 2000;
    const easingFunction = google.maps.Animation.Easing.Sinusoidal.InOut;
  
    let start = null;
    function step(timestamp) {
      if (!start) start = timestamp;
      const progress = timestamp - start;
  
      const newZoom = easingFunction(progress / duration) * (targetZoom - map.getZoom()) + map.getZoom();
      const newTilt = easingFunction(progress / duration) * (targetTilt - map.getTilt()) + map.getTilt();
      const newAngle = easingFunction(progress / duration) * (targetAngle - map.getHeading()) + map.getHeading();
  
      map.setZoom(newZoom);
      map.setTilt(newTilt);
      map.setHeading(newAngle);
  
      const progressPercentage = progress / duration;
      const latDiff = bubblePos.lat() - map.getCenter().lat();
      const lngDiff = bubblePos.lng() - map.getCenter().lng();
      const newLat = map.getCenter().lat() + latDiff * easingFunction(progressPercentage);
      const newLng = map.getCenter().lng() + lngDiff * easingFunction(progressPercentage);
      map.panTo(new google.maps.LatLng(newLat, newLng));
  
      if (progress < duration) {
        window.requestAnimationFrame(step);
      }
    }
  
    window.requestAnimationFrame(step);
  
    var imagesrc = '/storage/'+activemarkerid+'.png';
    $.get(imagesrc)
      .done(function() {
        $('#bubbletext').empty();
        $('#imagediv').html('<img id="theImg" src="'+imagesrc+'" />');
      })
      .fail(function() {
        $('#imagediv').empty();
      });
  
    $('#shareButton').click(function (e) {
      navigator.share(
        ({
          title: 'Look!',
          text: 'Look what\'s happening',
          url: window.location.origin + '/deeplink/' + activemarkerid,
        })
      );
    });
  
    (userid === bubble.userid) ? $('#vote_buttons').hide() : $('#vote_buttons').show();
    (userid === bubble.userid) ? $('#deleteButton').show() : $('#deleteButton').hide();
  
    document.getElementById("BigBubble").style.display = "block";
  };
  
  
 addMarker = function (bubble) {
 
     position = new google.maps.LatLng(bubble.longitude, bubble.latitude);
     zoommultiplier = 14 - map.getZoom();
     votesmultiplier = votesmultiplier = getvotesmultiplier(bubble.upvotes - bubble.downvotes);
     bubblezize = (70 * votesmultiplier) - (zoommultiplier * 70);
     var marker = new google.maps.Marker({
         position: position,
         icon: {url: '/img/bubble.svg', scaledSize: new google.maps.Size(bubblezize, bubblezize)},
         opacity: 1,
         label: {
             color: '#000000',
             fontWeight: 'normal',
             fontSize: (votesmultiplier * 6) - (zoommultiplier * 6) + 'px',
             text: bubble.title
         },
         optimized: true,
         map: map
     });
     marker.addListener('dblclick', function () {
         markerOnDblClick(bubble);
     });
     return marker;
 
 }
 
 voteBubble = function (vote) {
 
     $.ajax({
         url: '/votebubble/',
         type: "GET",
         dataType: "json",
         data: {userid: userid, id: activemarkerid, vote: vote},
         statusCode: {
             200: function (data) {
 
                 console.log(data);
                 if (data === false) {
                     swall('Already voted');
                 }
             },
             403: function () {
                 alert('your email has to be verified');
 
                 window.location.href = "/email/verify";
             },
             401: function () {
                 alert('you have to log in');
                 window.location.href = "/login";
             }
         },
         success: function (data) {
             console.log(data);
 
         },
         fail: function (xhr, textStatus, errorThrown) {
             alert('request failed');
         }
     });
 
 }
 
 deleteBubble = function () {
 
 
     $.ajax({
         url: '/deletebubble/',
         type: "GET",
         dataType: "json",
         data: {bubbleid: activemarkerid},
         statusCode: {
             403: function () {
                 swall('new email', 'your email has to be verified', 'error');
 
                 window.location.href = "/email/verify";
             },
             401: function () {
                 alert('you have to log in');
                 window.location.href = "/login";
             }
         },
         success: function (data) {
             console.log(data);
 
         },
         fail: function (xhr, textStatus, errorThrown) {
             alert('request failed');
         }
     });
 
 }
 
 getvotesmultiplier = function (n) {
     var i, s = 0.0;
     for (i = 1; i <= n; i++)
         s = s + 1 / i;
     return s;
 }
 
 addWhereAmIbutton = function () {
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
 
                     map.setZoom(17);
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
 getCoords = function () {
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
 checkGeolocation = function () {
     coords = new google.maps.LatLng(52.364061, 4.882769);
     getCoords().then(
         coords => map.panTo(new google.maps.LatLng(coords.latitude, coords.longitude)),
         reject => map.panTo(new google.maps.LatLng(52.364061, 4.882769))
     )
 
 
 }
 
 drawTheMap = function () {
     var mapProp = {
         center: new google.maps.LatLng(52.364061, 4.882769),
         zoom: 14,
         mapTypeId: google.maps.MapTypeId.ROADMAP,
         disableDefaultUI: true,
         gestureHandling: "greedy",
         draggableCursor: 'crosshair',
     };
     map = new google.maps.Map(document.getElementById('googleMap'), {
       center: {lat: -34.397, lng: 150.644},
       zoom: 14,
       disableDefaultUI: true,
       draggableCursor: 'crosshair',
       gestureHandling: "greedy",
       mapId: '4c6b8496882084c3'
       });    
     map.addListener('click', function (e) {
 
         map.setZoom(17);
         map.panTo(e.latLng);
         map.panBy(10, -200);
         bubblepos = e.latLng;
         var inputBigBubble = document.getElementById("inputBigBubble");
         if (inputBigBubble.style.display === "none") {
             inputBigBubble.style.display = "block";
         }
 
 
     });
     map.addListener('zoom_changed', function () {
         resizeMarkers();
     })
     addWhereAmIbutton();
 
 
 }
 sound = function(src) {
   this.sound = document.createElement('mapBackgroundAudio');
   this.sound.src = src;
   this.sound.setAttribute('preload', 'auto');
   this.sound.setAttribute('controls', 'none');
   this.sound.style.display = 'none';
   document.body.appendChild(this.sound);
   this.play = function() {
       this.sound.play();
   }
     
   };
   window.onload = function() {
     sound('/music/backgroundmusic.mp3');
   }