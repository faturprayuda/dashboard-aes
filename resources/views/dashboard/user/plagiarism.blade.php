@extends('dashboard.layouts.master')
@section('css')
<link href="https://unpkg.com/filepond/dist/filepond.css" rel="stylesheet">
<link href="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.css" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('assets/vendors/toastify/toastify.css') }}">
@endsection

@section('content')
<div class="page-heading">
    <h3>{{ $titlePage }}</h3>
</div>
<div class="page-content">
    <input type="text" value="{{ Auth::user()->id}}" id="user_id" hidden>
    <div class="card">
        <div class="card-header">
            <h5 class="card-title">Upload File</h5>
        </div>
        <div class="card-content">
            <div class="card-body">
                <!-- Basic file uploader -->
                <input type="file" class="key-answer">
                <button type="submit" class="btn btn-info" id='btn-keyAnswer'>Submit Form</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"
    integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ=="
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>

{{-- toastify --}}
<script src="{{ asset('assets/vendors/toastify/toastify.js') }}"></script>
{{-- <script src="{{ asset('assets/js/extensions/toastify.js') }}"></script> --}}

<!-- filepond validation -->
<script src="https://unpkg.com/filepond-plugin-file-validate-size/dist/filepond-plugin-file-validate-size.js"></script>
<script src="https://unpkg.com/filepond-plugin-file-validate-type/dist/filepond-plugin-file-validate-type.js"></script>

<!-- image editor -->
<script src="https://unpkg.com/filepond-plugin-image-exif-orientation/dist/filepond-plugin-image-exif-orientation.js">
</script>
<script src="https://unpkg.com/filepond-plugin-image-crop/dist/filepond-plugin-image-crop.js"></script>
<script src="https://unpkg.com/filepond-plugin-image-filter/dist/filepond-plugin-image-filter.js"></script>
<script src="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.js"></script>
<script src="https://unpkg.com/filepond-plugin-image-resize/dist/filepond-plugin-image-resize.js"></script>
<script src="https://unpkg.com/filepond/dist/filepond.js"></script>
<script>
    // register desired plugins...
	FilePond.registerPlugin(
        // validates the size of the file...
        FilePondPluginFileValidateSize,
        // validates the file type...
        FilePondPluginFileValidateType,

        // calculates & dds cropping info based on the input image dimensions and the set crop ratio...
        FilePondPluginImageCrop,
        // preview the image file type...
        FilePondPluginImagePreview,
        // filter the image file
        FilePondPluginImageFilter,
        // corrects mobile image orientation...
        FilePondPluginImageExifOrientation,
        // calculates & adds resize information...
        FilePondPluginImageResize,
    );

    // Filepond: Basic
    let keyAnswer = FilePond.create( document.querySelector('.key-answer'));

    let studentsAnswer = FilePond.create( document.querySelector('.students-answer'), {
        allowImagePreview: false,
        allowMultiple: false,
        allowFileEncode: false,
        required: false
    });

    $('#btn-keyAnswer').click(()=> {
        $('#btn-keyAnswer').hide()
        keyAnswer = keyAnswer.getFile().file;
        user_id = $('#user_id').val();
        var formData = new FormData();
        formData.append("_token", "{{ csrf_token() }}");
        formData.append("file", keyAnswer);
        formData.append('user_id', user_id);
        // set ajax
        $.ajax({
            url : 'http://127.0.0.1:5000/check-plagiarism',
            type: 'POST',
            contentType: false,
            processData: false,
            data: formData
        }).then((res)=> {
            Toastify({
                text: "Success Created Model",
                duration: 3000,
                close:true,
                gravity:"top",
                position: "center",
                backgroundColor: "#4fbe87",
            }).showToast();
            console.log(res)
        }).catch((res)=> {
            console.log(res)
        });
    });
</script>
@endsection
