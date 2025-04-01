@include('layouts.header')
@php
    $changelogContent = $changelogData->content;
@endphp
<script src="https://cdn.tiny.cloud/1/4yv6jbs5sphwk14j3kzc28z9lhdgsqum1qej45ayndlasxh6/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
<style>
    ..tox-tinymce {
        background-color: #f5f5f5;
        border: none!important;
    }
</style>
<div class="page_content">
    <!--- Page headline -->
<form action="/changelog/editChangelog/{{$changelogData->hash}}" method="post" id="editChangelog_form">@csrf
    <div class="row">
        <div class="col-6 page_title_area">
            <input name="changelogPostTitle" class="ticketNameInput chatTitle" type="text" value="{{ $changelogData->title }}" />
        </div>
    </div>

            <div class="row mt-3">
                <div class="col-xl-8 mt-2 postEditorCard">
                    <textarea onchange="saveEditorData()" name="changelogContent" class="postEditor" id="changelogContent"></textarea>
                    <input name="changelogFinalContent" id="changelogFinalContent" value="" hidden>
                </div>

                <div class="col-xl-4 d-flex justify-content-center align-items-xl-stretch mt-1">
                    <div class="changelogWriterBox">
                    <div class="writeBoxElement mt-3">                  
                        <a class="postItemSubheadline">Autor</a><br>
                        <a class="postItemHeadline">{{(new App\Models\User)->findUser($changelogData->creator)->username}}</a>
                    </div>
                    <div class="writeBoxElement mt-3">       
                        <a class="postItemSubheadline">Status</a>
                        <select class="form-control mt-1" name="changelogPostStatus" required>
                            <option value="Public" @if($changelogData->status == 'Public') selected @endif>Public</option>
                            <option value="Draft" @if($changelogData->status == 'Draft') selected @endif>Entwurf</option>
                            <option value="Private" @if($changelogData->status == 'Private') selected @endif>Privat</option>
                        </select>
                    </div>
                    <div class="writeBoxElement mt-4">
                        <button class="btn mr-2 button-primary-orange" style="margin-right: 0px!important;" onclick="saveEditorData()">
                            Post speichern
                        </button>
                    </div>
                </div>
            </div>
    </div>
</form>
</div>

<script>
tinymce.init({
    selector: 'textarea',
    skin: "oxide-dark",
    content_css: "dark",
    plugins: 'tinydrive anchor autolink charmap codesample emoticons image link lists media visualblocks wordcount textcolor',
    toolbar: 'undo redo | blocks fontfamily fontsize | bold underline strikethrough | forecolor backcolor | link image media table | align lineheight | checklist numlist bullist indent outdent | emoticons charmap | removeformat',
    menubar: false,
    statusbar: false,
    tinydrive_token_provider: '/editor/jwt',
    setup: function(editor) {
        editor.on('init', function(e) {
            loadEditorData()
        });
    }
    //checklist pageembed linkchecker
});

function loadEditorData() {
    var editorContent = @json($changelogContent);
    tinymce.get("changelogContent").setContent(editorContent);
}

function saveEditorData() {
    var changelogContent = tinymce.get("changelogContent").getContent();
    document.getElementById('changelogFinalContent').value = changelogContent;

    var submitEditForm = document.getElementById("editChangelog_form");
    submitEditForm.submit();
}
</script>
<style>
.tam .tam-assetmanager.tam-assetmanager-open {
    display: none!important;
}

.tam .tam-assetmanager-modal .tam-assetmanager-container {
    display: none!important;

}
</style>