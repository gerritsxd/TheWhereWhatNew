@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Invite') }}</div>
                    <div class="card-body">
                        Send the next link to the person you want to invite:
                        <br>

                        <br>
                        {{URL::to('/')}}/register?invitation_code={{$invitation_code}}
                        <div id="shareButton" class="bigbubbleredtext"><img src="/img/share.svg" width="24" id="shareButton"></div>

                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
@section('scripts')
    <script>
        jQuery(document).ready(function () {
            $("#shareButton").click(function (clickEvent) {
                var self = $(this);
                // Web Share API
                if (navigator.share) {
                    clickEvent.preventDefault();
                    navigator.share({
                        title: 'Youre invited to register',
                        url: '{{URL::to('/')}}/register?invitation_code={{$invitation_code}}'
                    })
                        .then(function () {
                            console.log('Successful share');
                        })
                        .catch(function (error) {
                            console.log('Error sharing:', error);
                            window.open($(self).attr('href'), '_blank');
                        });
                }
            });
        })
    </script>
    @stop
