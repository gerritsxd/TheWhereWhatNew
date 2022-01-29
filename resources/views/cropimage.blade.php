@extends('layouts.app')

@section('content')

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Add Image') }}</div>
                    <div class="card-body">
                        <label  class="form-label">Take Photo</label>
                        <div class="text-center">
                        <a class="btn btn-primary rounded-pill" id="start-camera" style="display: none;"> Start Camera</a>
                        </div>
                        <div class="text-center">
                            <video id="video" width="320" height="240" autoplay></video>
                        </div>
                        <div class="text-center">
                            <a class="btn btn-primary rounded-pill" id="click-photo"><img src="/img/camera.svg"width="32"> Take Shot</a>
                        </div>
                        <div><br></div>
                        <canvas id="canvas" width="320" height="240" style="display: none;"></canvas>
                <div class="text-center">
                    <div id="upload-div"></div>
                </div>

                <label for="formFileSm" class="form-label">Or use image</label>
                <input class="form-control form-control-sm" id="image_file" type="file">

                </div>
                <div class="text-center" >
                    <button class="btn btn-primary btn-block upload-image" style="margin-top:2%">Guardar Imagen</button>
                    <div class="alert alert-success" id="upload-success" style="display: none;margin-top:10px;"></div>

                </div>

                </div>
            </div>
        </div>
    </div>
    </div>
@endsection
@section('scripts')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.2/croppie.min.css">

    <script src="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.2/croppie.js"></script>
    <script>
        jQuery(document).ready(function () {
            //let video = $("#video");

            navigator.permissions.query({ name: "camera" }).then(res => {
                if(res.state == "granted"){
                    navigator.mediaDevices.getUserMedia({ video: { facingMode: "environment" } })
                        .then(function (stream) {
                            video.srcObject = stream;

                        }).catch(function (error) {
                        console.log("Something went wrong!");
                    });
                }else{
                    $('#start-camera').show();
                }
            });
            var video = document.getElementById('video'),
                vendorUrl = window.URL || window.webkitURL;
            $("#start-camera").click(async function() {
                if (navigator.mediaDevices.getUserMedia) {
                    navigator.mediaDevices.getUserMedia({ video: { facingMode: "environment" } })
                        .then(function (stream) {
                            video.srcObject = stream;

                        }).catch(function (error) {
                        console.log("Something went wrong!");
                    });
                }


            });
            var canvas = document.getElementById("canvas");
            var ctx = canvas.getContext("2d");

            $("#click-photo").click(function() {
                ctx.drawImage(video, 0, 0, 320, 240);
                let image_data_url = canvas.toDataURL('image/jpeg');
                resize.croppie('bind', image_data_url);
                // data url of the image
                console.log(image_data_url);
            });
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            var resize = $('#upload-div').croppie({
                enableExif: true,
                enableOrientation: true,
                viewport: { // Default { width: 100, height: 100, type: 'square' }
                    width: 200,
                    height: 200,
                    type: 'circle' //square
                },
                boundary: {
                    width: 200,
                    height: 200
                }
            });
            $('#image_file').on('change', function () {
                var reader = new FileReader();
                reader.onload = function (e) {
                    resize.croppie('bind', {
                        url: e.target.result
                    }).then(function () {
                        console.log('jQuery bind complete');
                    });
                }
                reader.readAsDataURL(this.files[0]);
            });
            $('.upload-image').on('click', function (ev) {
                //alert('click');
                resize.croppie('result', {
                    type: 'canvas',
                    size: 'viewport'
                }).then(function (img) {


                    $.ajax({
                        url: "/cropImage",
                        type: "POST",
                        data: {"image": img,"bubbleID":{{$bubbleID}}},
                        success: function (data) {
                            $("#upload-success").html("Images cropped and uploaded successfully.");
                            $("#upload-success").show();
                            window.history.go(-1);

                        }
                    });
                });
            });
            resize.croppie('bind', {
                url: '/img/ww192.png',});


        });



    </script>
@stop