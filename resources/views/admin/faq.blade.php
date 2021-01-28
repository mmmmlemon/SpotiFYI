@extends('layouts.adminApp')

@section('content')
<div class="row justify-content-center">
    <div class="col-12 col-md-8 paddingSides text-center">
        <form action="/superuser/control_panel/save_faq" method="POST" class="" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="faq">FAQ</label>
                <textarea width="100%" class="form-control" id="faq" name="faq" placeholder="FAQ">
                    {{$info['faq']}}
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
    $('#faq').trumbowyg({
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