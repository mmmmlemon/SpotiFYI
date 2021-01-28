@extends('layouts.adminApp')

@section('content')
<div class="row justify-content-center">
    <div class="col-12 col-md-8 paddingSides text-center">
        <form action="/superuser/control_panel/save_site_info" method="POST" class="" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="about">О проекте</label>
                <textarea width="100%" class="form-control" id="about" name="about" placeholder="Информация о проекте">
                    {{$info['about']}}
                </textarea>
            </div>

            <div class="form-group">
                <label for="poweredBy">Powered by</label>
                <textarea width="100%" class="form-control" id="poweredBy" name="poweredBy" placeholder="Powered by">
                    {{$info['poweredBy']}}
                </textarea>
            </div>
            <input class="btn btn-primary btn-block" type="submit" value="Сохранить">
        </form>
        <br>
    </div>
</div>
@endsection

@push('scripts')
<script>

    $('#poweredBy').trumbowyg({
            tagsToRemove:['script','img'],
            autogrow: true,
            imageWidthModalEdit: true,
            urlProtocol: true,
            btns: [
                ['viewHTML'],
                ['undo', 'redo'], // Only supported in Blink browsers
                ['formatting'],
                ['strong', 'em'],
                ['superscript', 'subscript'],
                ['link'],
                ['insertImage'],
                ['justifyLeft', 'justifyCenter', 'justifyRight', 'justifyFull'],
                ['unorderedList', 'orderedList'],
                ['horizontalRule'],
                ['removeformat'],
                ['fullscreen']
            ]
    });

    $('#about').trumbowyg({
        tagsToRemove:['script','img'],
        autogrow: true,
        imageWidthModalEdit: true,
        urlProtocol: true,
        btns: [
            ['viewHTML'],
            ['undo', 'redo'], // Only supported in Blink browsers
            ['formatting'],
            ['strong', 'em'],
            ['superscript', 'subscript'],
            ['link'],
            ['noembed'],
            ['insertImage'],
            ['justifyLeft', 'justifyCenter', 'justifyRight', 'justifyFull'],
            ['unorderedList', 'orderedList'],
            ['horizontalRule'],
            ['removeformat'],
            ['fullscreen']
        ]
    });
</script>
@endpush