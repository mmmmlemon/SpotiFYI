@extends('errors.errorLayout')
@section('title', 'Error 403'." -")
@section('content')
    <div class="">
        <div class="text-center">
            <br><br>
            <h1 class="text-center borderUnderline fadeInAnimSlow"><b>403</b></h1>
            <span class="icon warningIcon fadeInAnimSlow">
                😳
            </span>
            <div class="fadeInAnimSlow">
                <h3 class="subtitle">You are not allowed to perform this action or enter this page. </h3>
            </div>
            
            <a href="/" class="btn btn-primary fadeInAnimSlow">
                Go to the home page
            </a>
        </div>
    </div>
@endsection