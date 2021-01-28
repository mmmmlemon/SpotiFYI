@extends('layouts.adminApp')

@section('content')
<div class="row justify-content-center paddingSides">
    <div class="col-12 col-md-5">
        <form action="/superuser/control_panel/save_logo_and_images" method="POST" class="w-400 mw-full " enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="col-md-6 form-group">
                    <label for="logo" class="required">Логотип сайта</label>
                    <div class="custom-file">
                      <input type="file" id="logo" name="logo" accept=".jpg, .jpeg, .png" >
                      <label for="logo">Выбрать изображение</label>
                    </div>
                </div>  
                <div class="col-md-6 border text-center paddingSides marginBottomSmall">
                    <a href="{{$images['logo']}}">
                        <img src="{{$images['logo']}}" width="50%" alt="">
                    </a>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 form-group">
                    <label for="home_img" class="required">Фон для домашней страницы</label>
                    <div class="custom-file">
                      <input type="file" id="home_img" name="home_img" accept=".jpg, .jpeg, .png" >
                      <label for="home_img">Выбрать изображение</label>
                    </div>
                </div>  
                <div class="col-md-6 border text-center paddingSides marginBottomSmall">
                    <a href="{{$images['home_img']}}">
                        <img src="{{$images['home_img']}}" width="50%" alt="">
                    </a>
                   
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 form-group">
                    <label for="welcome_img" class="required">Картинка для приветствия</label>
                    <div class="custom-file">
                      <input type="file" id="welcome_img" name="welcome_img" accept=".jpg, .jpeg, .png" >
                      <label for="welcome_img">Выбрать изображение</label>
                    </div>
                </div>  
                <div class="col-md-6 border text-center paddingSides marginBottomSmall">
                    <a href="{{$images['welcome_img']}}">
                        <img src="{{$images['welcome_img']}}" width="50%" alt="">
                    </a>
                   
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 form-group">
                    <label for="user_img" class="required">Картинка для пользователя</label>
                    <div class="custom-file">
                      <input type="file" id="user_img" name="user_img" accept=".jpg, .jpeg, .png" >
                      <label for="user_img">Выбрать изображение</label>
                    </div>
                </div>  
                <div class="col-md-6 border text-center paddingSides marginBottomSmall">
                    <a href="{{$images['user_img']}}">
                        <img src="{{$images['user_img']}}" width="50%" alt="">
                    </a>
                   
                </div>
            </div>
                 
            <input class="btn btn-primary btn-block" type="submit" value="Сохранить">
          </form>
          <br>
    </div>
</div>
@endsection