@extends('layouts.adminApp')

@section('content')
<div class="row justify-content-center paddingSides">
    <div class="col-12 col-md-5">
        <form action="..." method="..." class="w-400 mw-full">
            <div class="form-group">
              <label for="sitetitle" class="required">Название сайта</label>
              <input type="text" class="form-control" id="sitetitle" name="sitetitle" placeholder="{{$basicSettings['siteTitle']}}" required="required" value="{{$basicSettings['siteTitle']}}">
            </div>
            <div class="form-group">
                <label for="version" class="required">Версия</label>
                <input type="text" class="form-control" id="version" name="version" placeholder="{{$basicSettings['version']}}" required="required" value="{{$basicSettings['version']}}">
            </div>
            <div class="form-group">
                <label for="contact_email" class="required">Контактный E-Mail</label>
                <input type="email" class="form-control" id="contact_email" name="contact_email" placeholder="contact@email.com" required="required" value="{{$basicSettings['contactEmail']}}">
            </div>
            <div class="form-group">
                <label for="spotify_client_id" class="required">Spotify API: Client ID</label>
                <input type="spotify_client_id" class="form-control" id="spotify_client_id" name="spotify_client_id" placeholder="" required="required" value="{{$basicSettings['spotify_api_client_id']}}">
            </div>
            <div class="form-group">
                <label for="spotify_client_secret" class="required">Spotify API: Client Secret</label>
                <input type="spotify_client_secret" class="form-control" id="spotify_client_secret" name="spotify_client_secret" placeholder="" required="required" value="{{$basicSettings['spotify_api_client_secret']}}">
            </div>
            <div class="form-group">
                <label for="spotify_redirect_id" class="required">Spotify API: Redirect ID</label>
                <input type="spotify_redirect_id" class="form-control" id="spotify_redirect_id" name="spotify_redirect_id" placeholder="" required="required" value="{{$basicSettings['spotify_api_redirect_uri']}}">
            </div>
            <input class="btn btn-primary btn-block" type="submit" value="Сохранить">
          </form>
    </div>
</div>
@endsection