@include('layouts.headerCommunityCenter')

<div class="communityCenterPageContent">
    <div class="headline">
    <div class="headlineLeft">
        <p class="communityCenterHeadline" style="text-align:left!important;margin-bottom: 5px;">Wunschliste</p>
        <a class="communityCenterSubtitle">Vote für Vorschläge anderer oder erstelle deine eigenen</a>
    </div>
    <div class="headlineRight">
        <button class="btn button-primary-orange" data-toggle="modal" data-target="#addWish">Neuen Wunsch erstellen</button>
    </div>
</div>

    <style>

    </style>

    <div class="wishSection">
        <div class="WishItems">
            @foreach ($wishes as $wish) 
            <div class="wishItem">
                <div class="wishLeft">
                    <div class="votesDiv">
                        <a class="voteCount">{{ $wish->votes }}</a><span class="voteNote">Votes</span>
                    </div>
                    <div class="voting mt-2">
                        @if($wish->canVote === true)
                        <form method="post" action="/project/wish/vote">@csrf
                        <input type="hidden" name="wishID" value="{{ $wish->id }}">
                        <button type="submit" class="customButton" style="width: 100px;">Voten</button>  
                        </form>
                        @else
                        <button class="customButton" style="width: 100px;" disabled>Gevotet</button>  
                        @endif

                    </div> 
                </div>
                <div class="wishContent">
                    <div class="wishHeaderRow">
                        <a class="wishHeadline">{{ $wish->title }}</a>
                        <div class="wishInfo">
                            @if($wish->tag != null)
                            <a class="wishTag">{{ $wish->tag }}</a>
                            @endif
                        </div>
                    </div>
                        <a class="shortDescription">{{ $wish->content }}</a><br>
                        @if($wish->answer != null)
                        <a class="wishAnswer" onclick="showAdminAnswer('{{$wish->answer}}')"><i class="fa-solid fa-comment"></i> Admin Antwort</a>
                        @endif
                        <a class="wishAnswer">Erstellt: {{ ($wish->created_at)->format('d.m.Y H:i') }} Uhr</a><br>
                </div>
                {{-- <a class="wishStatusTag">In Planung</a> --}}
            </div>
            @endforeach
        </div>
    </div>
</div>


<!-- Wish erstellen -->
<div class="modal fade" id="addWish" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-body">
            <div class="row mt-3">
              <div class="col-12 d-flex justify-content-center">
                  <a class="headline">Wunsch erstellen</a>
              </div>
              <div class="col-12 d-flex justify-content-center mt-2">
                  <a class="subtitle">Wirf deine Idee in den Raum</a>
              </div>
            </div>
                <div class="row mt-5" id="modal_emoji_root_element">
                    <div class="col-12">
                    <form method="post" action="/project/wish/add" id="submitWish">
                        @csrf
                        <div class="row">
                            <div class="col-5">
                                <div class="form-group">
                                    <input name="wishTitle" type="text" class="form-control" minlength="1" maxlength="40" autocomplete="off" placeholder="Titel deiner Idee" required>
                                </div>
                            </div>
                            <div class="col-5">
                                {{-- <div class="form-group">
                                    <input name="wishAuthor" type="text" class="form-control" autocomplete="off" placeholder="Discord Tag / Name" required>
                                </div> --}}
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col">
                                <div class="form-group">
                                    <textarea oninput="countCharacters()"  id="wishDescription" class="form-control mb-2" style="margin-top:5px;" minlength="1" maxlength="330" placeholder="Beschreibe deine Idee..." name="wishContent" rows="4" required></textarea>
                                    <span style="color:#B0B7C3" id="char_count">0/330</span><span style="color:#B0B7C3"> Zeichen.</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <input type="text" name="project_id" value="{{ $project ? $project->id : '' }}" hidden>
                </div>     
             <div class="row d-flex justify-content-center mt-4">
                <div class="col d-flex justify-content-center">
                    <button class="customButton" type="submit" id="submitNewWish" onsubmit="checkForm()">Erstellen und veröffentlichen</button>
                </div>      
             </div>
         </form>
      </div>
    </div>
  </div>
</div>

<!-- invite an Employee -->
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
let textArea = document.getElementById("wishDescription");
let characterCounter = document.getElementById("char_count");
const maxNumOfChars = 330;

const countCharacters = () => {
    let numOfEnteredChars = textArea.value.length;
    let counter = maxNumOfChars - numOfEnteredChars;
    characterCounter.textContent = textArea.value.length + "/330";
};

function showAdminAnswer(adminMessage) {
    adminAnswer.innerHTML = adminMessage;
    // Open Modal
    $('#showAdminMessageModal').modal('show');
}

function checkForm(form)
{
    $("#submitNewWish").attr('disabled', 'true');
}
</script>

@include('layouts.footerCommunityCenter')
