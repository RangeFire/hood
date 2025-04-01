
@include('layouts.header')
@php $copyLinkURL = 'https://'.(new App\Models\Project)->findProject(session('activeProject'))->project_hash.'.'.env('APP_DOMAIN').'/cc/wishes'; @endphp

<div class="page_content"> 
    <div class="container customContainer">
        <div class="subNavigation">
            @include('layouts.trialRuntimeInfo')
            <div class="subNavigationSection">
                <div class="subNavLeft">
                    <a class="navlink active" href="">Nutzerwünsche</a>
                    <a class="navlink" href="/changelogs">Ankündigungen</a>
                    <a class="navlink" href="/bugreports">Bugreports</a>
                    {{-- <a class="navlink" href="">Umfragen</a> --}}
                </div>
                <div class="subNavLeft">
                   <a class="link" href="{{$copyLinkURL}}" target="_blank">Zur Community Ansicht</a> 
                </div>
            </div>
        </div>


        @if (sizeof($wishes) != 0)
        <div class="col-10">
        <div class="wishSection" style="width: 100%!important;">
         <div class="WishItems">
          @foreach ($wishes as $wish) 
            <div class="wishItem">
                <div class="wishLeft">
                    <div class="votesDiv">
                        <a class="voteCount">{{ $wish->votes }}</a><span class="voteNote">Votes</span>
                    </div>
                    <div class="voting mt-2">
                        <form method="post" id="deleteWishForm{{$count}}" action="wish/deleteWish">@csrf
                        <input type="hidden" name="wishId" value="{{$wish->id}}"> 
                        <button onclick="deleteWish({{$count}})" class="customButton" style="width: 100px;">Löschen</button>  
                        </form>
                    </div> 
                </div>
                <div class="wishContent">
                    <div class="wishHeaderRow">
                        <a class="wishHeadline">{{ $wish->title }}</a>
                        <div class="wishInfo">
                                <form id="form_wishSetTag" action="/wish/changeTag" method="post">@csrf
                                <input onfocusout="$('#form_wishSetTag').submit();" class="wishSetTag" name="wishTag" type="text" placeholder="{{ $wish->tag != null  ? $wish->tag : 'Tag hinzufügen'}}" value="{{ $wish->tag != null  ? $wish->tag : ''}}">
                                <input name="wishId" value="{{ $wish->id }}" hidden/>
                                <input type="submit" style="position: absolute; left: -9999px; width: 1px; height: 1px;" tabindex="-1" />
                            </form>
                        </div>
                    </div>
                        <a class="shortDescription">{{ $wish->content }}</a><br>
                        <a class="wishAnswer" onclick="setAdminAnswer('{{$wish->id}}')"><i class="fa-solid fa-comment"></i>{{$wish->answer != null ? ' Antwort ändern' : ' Wunsch beantworten'}}</a><br> 
                        <a class="shortDescription">vom {{ ($wish->created_at)->format('d.m.Y H:i'); }}</a>
                </div>
            </div>
            @endforeach
         </div>
        </div>
        </div>
        @else 
         <div class="col-12" style="margin-top: 220px;">
            @include('pages.comp.errorEmpty')
        </div>
        @endif

        <div class="col"></div>
    </div>
</div>

<!-- Wish Answer Modal -->
<div class="modal fade" id="wishAdminAnswerModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <form action="/wish/changeAdminAsnwer" method="post">@csrf
            <div class="modal-content" style="width: 650px; margin: 0 auto;">
                <div class="modal-header custom_modal_header">
                    <p class="h3" id="exampleModalLabel">Wunsch beantworten</p>
                </div>
                <div class="modal-body">
                    <div class="row mt-3">
                        <div class="col-12" style="position:relative;">
                            <div class="form-group">
                                <label for="exampleFormControlTextarea1">Deine Antwort</label>
                                <textarea class="form-control" id="wishAnswerText" name="adminAnswerText" rows="4"></textarea>
                                <input id="adminAnswerWishId" name="wishId" hidden/>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-center">
                    <button type="submit" class="btn btn-block button-primary-orange w-50">Speichern</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    let wishes = @json($wishes);

    function copyLinkToClipboard() {
        var finalText = '{{$copyLinkURL}}';
        
        navigator.clipboard.writeText(finalText);
        new Notification('success', 'Der Link wurde kopiert.', '5000')
    }

    function deleteWish(count) {
        var form = document.getElementById("deleteWishForm"+count);
        form.submit();
    }

    function setAdminAnswer(id) {
        let wish = wishes.find(wish => wish.id == id);
        if(!wish) {
           return new Notification('error', 'Es ist ein Fehler afgetreten', '5000');
        }
        document.getElementById('adminAnswerWishId').value = wish.id;
        document.getElementById('wishAnswerText').value = wish.answer;
        $('#wishAdminAnswerModal').modal('show');
    }
</script>
