@extends('layouts.adminApp')

@section('content')
<div class="row justify-content-center paddingSides">
    <div class="col-12 col-md-5">
        <form action="/superuser/control_panel/save_basic" method="POST" class="w-400 mw-full">
            @csrf
            <div class="form-group">
              <label for="site_title" class="required">Название сайта</label>
              <input type="text" class="form-control" id="site_title" name="site_title" placeholder="{{$basicSettings['siteTitle']}}" required="required" 
                value="@if($errors->any()){{old('site_title')}}@else{{$basicSettings['siteTitle']}}@endif" maxlength="10">
              @error('site_title')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
              @enderror
            </div>
            <div class="form-group">
                <label for="version" class="required">Версия</label>
                <input type="text" class="form-control" id="version" name="version" placeholder="{{$basicSettings['version']}}" required="required" 
                    value="@if($errors->any()){{old('version')}}@else{{$basicSettings['version']}}@endif" maxlength="5">
                @error('version')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            <div class="form-group">
                <label for="contact_email" class="required">Контактный E-Mail</label>
                <input type="email" class="form-control" id="contact_email" name="contact_email" placeholder="contact@email.com" required="required" 
                    value="@if($errors->any()){{old('contact_email')}}@else{{$basicSettings['contactEmail']}}@endif" maxlength="50">
                @error('contact_email')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            <div class="form-group">
                <label for="spotify_client_id" class="required">Spotify API: Client ID</label>
                <input type="spotify_client_id" class="form-control" id="spotify_client_id" name="spotify_client_id" placeholder="" required="required" 
                    value="@if($errors->any()){{old('spotify_client_id')}}@else{{$basicSettings['spotify_api_client_id']}}@endif" maxlength="32">
                @error('spotify_client_id')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            <div class="form-group">
                <label for="spotify_client_secret" class="required">Spotify API: Client Secret</label>
                <input type="spotify_client_secret" class="form-control" id="spotify_client_secret" name="spotify_client_secret" placeholder="" required="required" 
                    value="@if($errors->any()){{old('spotify_client_secret')}}@else{{$basicSettings['spotify_api_client_secret']}}@endif" maxlength="32">
                @error('spotify_client_secret')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            <div class="form-group">
                <label for="spotify_redirect_uri" class="required">Spotify API: Redirect URI</label>
                <input type="spotify_redirect_uri" class="form-control" id="spotify_redirect_uri" name="spotify_redirect_uri" placeholder="" required="required" 
                    value="@if($errors->any()){{old('spotify_redirect_uri')}}@else{{$basicSettings['spotify_api_redirect_uri']}}@endif" maxlength="200">
                @error('spotify_redirect_uri')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            <input class="btn btn-primary btn-block" type="submit" value="Сохранить">
          </form>
          <br>
    </div>
</div>
@endsection

@push('scripts')
<script>

    $("#site_title").charCounter();
    $("#version").charCounter();
    $("#contact_email").charCounter();
    $("#spotify_client_id").charCounter();
    $("#spotify_client_secret").charCounter();
    $("#spotify_redirect_uri").charCounter();

</script>
@endpush