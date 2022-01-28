@extends('layouts.app')

@section('content')

    <div class="panel panel-info">
        <div class="panel-heading"></div>
        <div class="panel-body">
            <div class="row">
                <div class="text-center">
                    <div id="upload-demo"></div>
                </div>
                <div class="text-center" >
                    <strong>Selecionar imagen:</strong>
                </div>
                    <div class="text-center" >
                    <input class="btn btn-success" type="file" id="image_file">
                </div>
                <div class="text-center" >
                    <button class="btn btn-primary btn-block upload-image" style="margin-top:2%">Guardar Imagen</button>
                    <div class="alert alert-success" id="upload-success" style="display: none;margin-top:10px;"></div>

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
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });


            var resize = $('#upload-demo').croppie({
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
        url: '',});

        });
    </script>
@stop