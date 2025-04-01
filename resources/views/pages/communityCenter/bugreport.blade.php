@include('layouts.headerCommunityCenter')

<div class="communityCenterPageContent">
    <div class="headline">
    <div class="headlineLeft">
        <p class="communityCenterHeadline" style="text-align:left!important;margin-bottom: 5px;">Bugtracker</p>
        <a class="communityCenterSubtitle">Unterstütze uns und melde uns Fehler die dir auffallen</a>
    </div>
    <div class="headlineRight">
        <button class="btn button-primary-orange" data-toggle="modal" data-target="#addWish">Bugreport erstellen</button>
    </div>
</div>

    <style>

    </style>

    <div class="wishSection">
        <div class="WishItems">
            @foreach ($bugreports as $bugreport) 
            <div class="wishItem">
                <div class="wishContent" style="margin-left: 0px;">
                    <div class="wishHeaderRow">
                        <a class="wishHeadline">{{ $bugreport->title }}</a>
                        <div class="wishInfo">
                            @if($bugreport->tag != null)
                            <a class="wishTag">{{ $bugreport->tag }}</a>
                            @endif
                        </div>
                    </div>
                        <a class="shortDescription">{{ $bugreport->content }}</a><br>
                        @if($bugreport->answer != null)
                        <a class="wishAnswer" onclick="showAdminAnswer('{{$bugreport->answer}}')"><i class="fa-solid fa-comment"></i> Admin Antwort</a><br>
                        @endif
                        <a class="wishAnswer">Erstellt: {{ ($bugreport->created_at)->format('d.m.Y H:i') }} Uhr</a><br>
                </div>
                {{-- <a class="wishStatusTag">In Planung</a> --}}
            </div>
            @endforeach
        </div>
    </div>
</div>


<!-- Bugreport erstellen -->
<div class="modal fade" id="addWish" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-body">
            <div class="row mt-3">
              <div class="col-12 d-flex justify-content-center">
                  <a class="headline">Einen Bug melden</a>
              </div>
              <div class="col-12 d-flex justify-content-center mt-2">
                  <a class="subtitle">Hast du geschaut ob der Fehler bereits gemeldet wurde?</a>
              </div>
            </div>
                <div class="row mt-5" id="modal_emoji_root_element">
                    <div class="col-12">
                    <form method="post" action="/project/bugreport/add" id="submitBugreport">
                        @csrf 
                        <div class="row">
                            <div class="col-5">
                                <div class="form-group">
                                    <input name="bugreportTitle" type="text" class="form-control" minlength="1" maxlength="40" autocomplete="off" placeholder="Überschrift" required>
                                </div>
                            </div>
                            <div class="col-5">
                                <div class="form-group">
                                </div>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col">
                                <div class="form-group">
                                    <textarea oninput="countCharacters()"  id="bugreportDescription" class="form-control mb-2" style="margin-top:5px;" minlength="1" maxlength="330" placeholder="Beschreibe das Problem so genau wie möglich" name="bugreportDescription" rows="4" required></textarea>
                                    <span style="color:#B0B7C3" id="char_count">0/1000</span><span style="color:#B0B7C3"> Zeichen.</span>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col">
                                <div class="form-group">
                                    <button class="customButton mb-2" type="button" onclick="uploadAttachment()">Datei anhängen</button><br>
                                    <input id="attachmentURL" name="attachmentURL" hidden>
                                    <span style="color:#B0B7C3">Max 1MB</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <input type="text" name="project_id" value="{{ $project ? $project->id : '' }}" hidden>
                </div>     
             <div class="row d-flex justify-content-center mt-4">
                <div class="col d-flex justify-content-center">
                    <button class="customButton" type="button" onclick="checkForm()">Bugreport einreichen</button>
                </div>       
             </div>
         </form>
      </div>
    </div>
  </div>
</div>

<!-- Antworten -->
<div class="modal fade" id="showAdminMessageModal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-body">
            <div class="row mt-3 mb-3">
              <div class="col-12 d-flex justify-content-center">
                  <a class="headline">Antwort der Projektleitung</a>
              </div>
              <div class="col-12 d-flex justify-content-center mt-3">
                  <a class="subtitle" id="adminAnswer"></a>
              </div>
            </div>
      </div>
    </div>
  </div>
</div>

<script>
let textArea = document.getElementById("bugreportDescription");
let characterCounter = document.getElementById("char_count");
const maxNumOfChars = 1000;

const countCharacters = () => {
    let numOfEnteredChars = textArea.value.length;
    let counter = maxNumOfChars - numOfEnteredChars;
    characterCounter.textContent = textArea.value.length + "/1000";
};

function showAdminAnswer(adminMessage) {
    adminAnswer.innerHTML = adminMessage;
    // Open Modal
    $('#showAdminMessageModal').modal('show');
}
</script>

<script>
    function uploadAttachment() {
        const client = filestack.init("AhY2QLM5BQViijG7RYx4iz");
        const options = {
             fromSources: ["local_file_system"],
             maxSize: 1100000,
             lang: 'de',
             accept: ["image/*"],
             storeTo: {
                location: 's3',
                path: '/hood/'
            },
            transformations: {
                crop: true,
                circle: true,
                rotate: true
            },
            onUploadDone: (res) => saveLogo(res),
        };
        client.picker(options).open();
    }

    function saveLogo(response) {
        let attachmentURL = "http://storage.mycraftit.com/" + response.filesUploaded[0].key;
        document.getElementById('attachmentURL').value = attachmentURL;
        document.getElementById("addAttachment").submit();
    }

    let canBeSubmit = true;
    function checkForm(form)
    {

        if(canBeSubmit == true) {
            document.getElementById("submitBugreport").submit();
        }

        canBeSubmit = false;

    }
</script>

@include('layouts.footerCommunityCenter')
