@extends('errors.errorLayout')
@section('title', 'Error 403'." -")
@section('content')
    <div class="">
        <div class="text-center">
            <br><br>
            <h1 class="text-center borderUnderline fadeInAnimSlow"><b>Oh no!</b></h1>
            <span class="icon warningIcon fadeInAnimSlow">
                🤒
            </span>
            <div class="fadeInAnimSlow">
                <h3 class="subtitle">The web-site is under maintenance</h3>
                <h6>It's okay, though.</h6>
                <h6>Come again later.</h6>
            </div>
        
        </div>
    </div>
@endsection