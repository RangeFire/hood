@include('layouts.header')
@php $copyLinkURL = 'https://'.(new App\Models\Project)->findProject(session('activeProject'))->project_hash.'.'.env('APP_DOMAIN').'/cc/bugreport'; @endphp

<div class="page_content">
    <div class="container customContainer container-fluid">
        <div class="subNavigation">
            @include('layouts.trialRuntimeInfo')
            <div class="subNavigationSection">
                <div class="subNavLeft">
                    <a class="navlink" href="/wishes">Nutzerwünsche</a>
                    <a class="navlink" href="/changelogs">Ankündigungen</a>
                    <a class="navlink active" href="/bugreports">Bugreports</a>
                    {{-- <a class="navlink" href="">Umfragen</a> --}}
                </div>
                <div class="subNavLeft">
                   <a class="link" href="{{$copyLinkURL}}" target="_blank">Zur Community Ansicht</a> 
                </div>
            </div>
        </div>
    

        <div class="col-9">
            <div class="wishSection" style="width: 100%!important;">
                <div class="WishItems">
                    @foreach ($bugreports as $bugreport) 
                    <div class="wishItem">
                        <div class="bugreportsLeft">
                            <div class="voting mt-2">
                                <form method="post" id="deleteBugreportForm{{$count}}" action="bugreport/deleteBugreport">@csrf
                                <input type="hidden" name="bugreportId" value="{{$bugreport->id}}"> 
                                <button onclick="deleteBugreport({{$count}})" class="customButton" style="width: 100px;">Löschen</button>  
                                </form>
                            </div> 
                        </div>
                        <div class="wishContent">
                            <div class="wishHeaderRow">
                                <a class="wishHeadline">{{ $bugreport->title }}</a>
                                <div class="wishInfo mb-1">
                                        <form id="form_wishSetTag" action="/bugreport/changeTag" method="post">@csrf
                                        <input onfocusout="$('#form_wishSetTag').submit();" class="wishSetTag" name="bugreportTag" type="text" placeholder="{{ $bugreport->tag != null  ? $bugreport->tag : 'Tag hinzufügen'}}" value="{{ $bugreport->tag != null  ? $bugreport->tag : ''}}">
                                        <input name="bugreportId" value="{{ $bugreport->id }}" hidden/>
                                        <input type="submit" style="position: absolute; left: -9999px; width: 1px; height: 1px;" tabindex="-1" />
                                    </form>
                                </div>
                            </div>
                                <a class="shortDescription">{{ $bugreport->content }}</a><br>
                                <a class="wishAnswer" onclick="setAdminAnswer('{{$bugreport->id}}')"><i class="fa-solid fa-comment"></i>{{$bugreport->answer != null ? ' Antwort ändern' : ' Bugreport beantworten'}}</a>    
                                @if($bugreport->attachment) {
                                    <a class="wishAnswer" href="{{$bugreport->attachment}}" target="_blank"><i class="fa-solid fa-paperclip"></i> Anhang Download</a>    
                                }
                                @endif
                                <a class="shortDescription">vom {{ ($bugreport->created_at)->format('d.m.Y H:i'); }} Uhr</a>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Wish Answer Modal -->
<div class="modal fade" id="bugreportAdminAnswerModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <form action="/bugreport/changeAdminAsnwer" method="post">@csrf
            <div class="modal-content" style="width: 650px; margin: 0 auto;">
                <div class="modal-header custom_modal_header">
                    <p class="h3" id="exampleModalLabel">Bugreport beantworten</p>
                </div>
                <div class="modal-body">
                    <div class="row mt-3">
                        <div class="col-12" style="position:relative;">
                            <div class="form-group">
                                <label for="exampleFormControlTextarea1">Deine Antwort</label>
                                <textarea class="form-control" id="bugreportAnswerText" name="adminAnswerText" rows="4"></textarea>
                                <input id="adminAnswerBugreportId" name="bugreportId" hidden/>
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
</div>

<script>
    let bugreports = @json($bugreports);

    function copyLinkToClipboard() {
        var finalText = '{{$copyLinkURL}}';
        
        navigator.clipboard.writeText(finalText);
        new Notification('success', 'Der Link wurde kopiert.', '5000')
    }

    function deleteBugreport(count) {
        var form = document.getElementById("deleteBugreportForm"+count);
        form.submit();
    }

    function setAdminAnswer(id) {
        let bugreport = bugreports.find(bugreport => bugreport.id == id);
        if(!bugreport) {
           return new Notification('error', 'Es ist ein Fehler afgetreten', '5000');
        }
        document.getElementById('adminAnswerBugreportId').value = bugreport.id;
        document.getElementById('bugreportAnswerText').value = bugreport.answer;
        $('#bugreportAdminAnswerModal').modal('show');
    }
</script>
