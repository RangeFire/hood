@include('layouts.header')
<script src="https://cdn.tiny.cloud/1/4yv6jbs5sphwk14j3kzc28z9lhdgsqum1qej45ayndlasxh6/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>

<div class="page_content">
    <!--- Page headline -->
<form action="/changelog/saveChangelog" method="post">@csrf
    <div class="row">
        <div class="col-6 page_title_area">
            <input name="changelogPostTitle" class="ticketNameInput chatTitle" type="text" value="Dein Interner Post Name" />
        </div>
    </div>

            <div class="row mt-3">
                <div class="col-xl-8 mt-2 postEditorCard">
                    <textarea onchange="test()" name="changelogContent" class="postEditor" id="changelogContent">
                        Was hast du zu erzählen?
                    </textarea>
                    <input name="changelogFinalContent" id="changelogFinalContent" value="" hidden>
                </div>

                <div class="col-xl-4 d-flex justify-content-center align-items-xl-stretch mt-1">
                    <div class="changelogWriterBox">
                    <div class="writeBoxElement mt-3">                  
                        <a class="postItemSubheadline">Autor</a><br>
                        <a class="postItemHeadline">{{Auth::user('username')}}</a>
                    </div>
                    <div class="writeBoxElement mt-3">       
                        <a class="postItemSubheadline">Status</a>
                        <select class="form-control mt-1" name="changelogPostStatus" required>
                            <option value="Public" selected>Public</option>
                            <option value="Private">Privat</option>
                            <option value="Draft">Entwurf</option>
                        </select>
                    </div>
                    <div class="writeBoxElement mt-4">
                        <button class="btn mr-2 button-primary-orange" style="margin-right: 0px!important;" onclick="">
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
    plugins: 'tinydrive anchor autolink charmap codesample emoticons image link lists media table visualblocks wordcount textcolor',
    toolbar: 'undo redo | blocks fontfamily fontsize | bold underline strikethrough | forecolor backcolor | link image media table | align lineheight | checklist numlist bullist indent outdent | emoticons charmap | removeformat',
    menubar: false,
    statusbar: false,
    tinydrive_token_provider: '/editor/jwt'
});

//tinymce.get("myTextarea").setContent("<p>Hello world!</p>");

function test() {
    var changelogContent = tinymce.get("changelogContent").getContent();
    console.log(changelogContent);
    document.getElementById('changelogFinalContent').value = changelogContent;
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