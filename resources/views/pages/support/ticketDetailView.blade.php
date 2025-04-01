@include('layouts.header')

<div class="page_content">
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">

@php
    if($ticket->closed) {
        $status = 'Geschlossen';
    }else if($ticket->leadingOperator) {
        $status = 'Bearbeitung';
    }else {
        $status = 'Offen';
    } 

    if($ticket->type == "discord") {
        $platform = 'fa-brands fa-discord';
    } else {
        $platform = 'fa-solid fa-browser';
    }
@endphp

<style>
.ql-container.ql-snow {
    background: #1F2128;
    border-radius: 0px 0px 12px 12px;
    border: 2px solid rgba(154, 159, 165, 0.15);
    color: white;
    font-family: 'Montserrat';
    font-weight: 400;
    width: 100%;
    height: 15vh;
}

.ql-toolbar.ql-snow {
    background: #272B30;
    border: 2px solid rgba(154, 159, 165, 0.15);
    border-radius: 12px 12px 0px 0px;
    color: #6F767E;
    width: 100%
}
</style>


<div class="pageHeadline">
    <div class="leftSite">
        <a class="goBack" href="/tickets"><i class="fa-solid fa-left"></i> Zurück</a>
        <a class="pageTitle">Ticket Chat</a>
    </div>
    <div class="rightSite">
        <div class="row d-flex justify-content-end align-items-center">
            <div class="col">
                <button class="btn button-primary-orange" data-toggle="modal" data-target="#modal-closeTicket" {{ $status == 'Geschlossen' ? 'disabled readonly' : '' }}>
                    <i class="fa-solid fa-lock"></i> Ticket schließen
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Start Page Content -->
<div class="row mt-5">

    <!-- Support Stats -->
    <div class="col-xl-3 mr-5 supportCard">
        <div class="row">
            <div class="col-xl-12">
                <form id="form_ticketTitle" action="/ticket/changeTitle" method="post">@csrf
                <input onfocusout="$('#form_ticketTitle').submit();" name="ticketTitle" class="ticketNameInput chatTitle" type="text" value="{{ $ticket->ticket_title != null ? $ticket->ticket_title : 'Ticket #' . $ticket->id}}" />
                <input name="ticketId" value="{{ $ticket->id }}" hidden/>
                <input type="submit" style="position: absolute; left: -9999px; width: 1px; height: 1px;" tabindex="-1" />
                </form>
                <!-- <i class="fa-solid fa-pen-to-square"></i> -->
            </div>
            <div class="col-xl-12 chatCreator mt-1">
                <a>Ersteller: <span>{{ $ticket_discord_user ?: 'Kein Name' }}</span></a>
            </div>
        </div>

        <div class="row mt-4 supportDetailDataSets">
            <div class="col-xl-6">
                <div class="supportDetailDataSet">
                    <div class="icon">
                        <i class="fa-solid fa-calendar-plus"></i>
                    </div>
                    <div class="content">
                        <a class="head">Erstellt</a>
                        <a class="sub mt-1">{{date_format($ticket->created_at,"d.m.Y");}}</a>
                    </div>
                </div>
            </div>
            <div class="col-xl-6">
                <div class="supportDetailDataSet">
                    <div class="icon">
                        <i class="fa-solid fa-layer-group"></i>
                    </div>
                    <div class="content">
                        <a class="head">Platform</a>
                        <a class="sub mt-1"><i class="{{ $platform }}"></i></a>
                    </div>
                </div>
            </div>
            <div class="col-xl-6">
                <div class="supportDetailDataSet">
                    <div class="icon">
                        <i class="fa-solid fa-traffic-light"></i>
                    </div>
                    <div class="content">
                        <a class="head">Kategorie</a>
                        <a class="sub mt-1">{{ $ticket->category ?: '-' }}</a>
                    </div>
                </div>
            </div>
            <div class="col-xl-6">
                <div class="supportDetailDataSet">
                    <div class="icon">
                        <i class="fa-solid fa-traffic-light"></i>
                    </div>
                    <div class="content">
                        <a class="head">Status</a>
                        <a class="sub mt-1" {{ $status == 'Geschlossen' ? 'color: #da0000!important;' : '' }}>{{ $status ? $status : 'Unbearbeitet'}}</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <a class="subtitle">Support Agent</a>
        </div>
        <div class="row mt-2">
            @if ($ticket->leadingOperator)
                <div class="col-xl-2 d-flex justify-content-center pt-1" data-toggle="tooltip" data-placement="bottom" title="{{ $ticket->leadingOperator->username }}">
                    <img data-toggle="modal" data-target="#modal-changeAgent" class="supportAgentImage" src="https://avatars.githubusercontent.com/u/51745793?v=4">
                    {{-- <img data-toggle="modal" data-target="#modal-changeAgent" class="supportAgentImage" src="{{ $ticket->leadingOperator->avatar ?: 'https://avatars.githubusercontent.com/u/51745793?v=4' }}"> --}}
                </div>
            @else
                <div class="col-xl-2 d-flex justify-content-center pt-1 addSupportAgent">
                    <a data-toggle="modal" data-target="#modal-changeAgent"><img class="supportAgentImage" src="/assets/images/svg/addRoundIcon.svg"></a>
                </div>
            @endif
        </div>

        <div class="row mt-5">
            <a class="subtitle mb-3">Chat Notizen <a class="pl-2" role="button" onclick="saveNote()"><i class="fa-solid fa-floppy-disk"></i></a></a>
            <form id="saveNote" action="/ticket/addNote/{{ $ticket->id }}" method="post">@csrf
            <input id="noteContent" value="Hallo" name="note" hidden>
            </form>
            <div id="editor"></div>
        </div>
    </div>

    <style>
        .chatContainer {
            height: 85%;
            overflow-y: auto;
            overflow-x: hidden;
            padding-bottom: 20px;
            position: relative;
        }

        .chatAnswereField {
            position: absolute;
            bottom: 0px;
            left: 0px;
            width: 100%;
            height: auto;
            padding: 32px 32px;
            display: flex;
        }

        .chatAnswereField input {
            width: 100%;
            border: 2px solid #353945;
            border-radius: 90px;
            padding: 8px 8px 8px 24px;
            color: #777E91!important;
            background-color: #1F2128;
            font-weight: 400;
                        padding-right: 42px;
        }

        .chatAnswereField .sendButton {
            position: absolute;
            right: 57px;
            top: 35.3px;
            border-radius: 50%;
            width: 12px;
        }

        .chatMessageTime {
            font-weight: 600;
            font-size: 12px;
            color: #FCFCFD!important;
            text-align: end;
            position: absolute;
            bottom: -20px;
            right: 8px;
        }

        .chatAgentMessage {
            background-color: #2A85FF;
            border-radius: 32px;
            padding: 18px 18px;
            min-width: 20%;
            max-width: 60%;
            text-align: start;
            color: #FCFCFD;
            float: right;
            position: relative;
            margin: 25px 0px;
            margin-right: 10px;
            margin-left: 40%;
        }

        .chatUserMessage {
            background-color: #1F2128;
            border-radius: 16px;
            padding: 18px 18px;
            min-width: 20%;
            max-width: 60%;
            text-align: start;
            color: #FCFCFD;
            float: left;
            position: relative;
            margin: 25px 0px;
            margin-left: 10px;
            margin-right: 40%;

        }

        .chatBotMessage {
            font-weight: 600;
            font-size: 12px;
            color: #FCFCFD!important;
            text-align: center;
            float: left;
            margin: 60px 0px 25px 20px;
        }

        
    </style>

    <!-- Start Chat Content -->
    <div class="col-xl-8 supportCard">
        @if(App\Helpers\Subscription::hasActiveSubscription('support'))
        <div class="textSnippetDiv" id="snippetDiv">
            <ul class="list">
                <li class="list-group-item">Wähle ein Textsnippet: <a class="closeTextSnippet" onclick="closeTextSnippets()"><i class="fa-solid fa-square-xmark"></i></a></li>
                @foreach ($textSnippets as $textSnippet)
                    <li onclick="setTextSnippet('{{ $textSnippet->id }}')" class="list-group-item hover-color">#{{ $textSnippet->identifier }}</li>
                @endforeach
            </ul>
        </div>
        @endif
        <div class="chatContainer" id="ticket-messages-render">

            {{-- <div class="chatUserMessage">
                <a>I have bought items from your store that have not arrived.</a>
                <div class="chatMessageTime">
                    <a>17:34 Uhr</a>
                </div>
            </div>

            <div class="chatBotMessage">
                <a>-- Einen Moment bitte, ein Supportmitarbeiter wird sich umgehend bei dir melden. --</a>
            </div>

            <div class="chatAgentMessage">
                <a>Hello Mike, I'm Celine and your supporter for this ticket. One moment please.</a>
                <div class="chatMessageTime">
                    <a>17:34 Uhr</a>
                </div>
            </div>

            <div class="chatAgentMessage">
                <a>Hello Mike, I'm Celine and your supporter for this ticket. One moment please.</a>
                <div class="chatMessageTime">
                    <a>17:34 Uhr</a>
                </div>
            </div>

            <div class="chatUserMessage">
                <a>I have bought items from your store that have not arrived.</a>
                <div class="chatMessageTime">
                    <a>17:34 Uhr</a>
                </div>
            </div> --}}

        </div>

        <form action="/ticket/answer/{{ $ticket->id }}" method="post">@csrf
        <div class="chatAnswereField">
            <input id="answerTextarea" name="reply" placeholder="Antwort hier...">
            <button style="background: transparent;border: none;" type="submit" {{ $status == 'Geschlossen' ? 'disabled readonly' : '' }} class="sendButton">
                <img src="/assets/images/svg/sendButtonRound.svg">
            </button>
        </div>
        </form>

    </div>
</div>


<!-- Modals -->
<!-- Modal - Ticket schließen -->
<div class="modal fade" id="modal-closeTicket" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content custom_modal">
                <div class="modal-body">
                    <h4 class="h2" id="exampleModalLabel" style="margin-bottom: 16px;">Ticket schließen</h4>

                    <div class="text-muted">Bist du sicher, das du das Ticket schließen möchtest? Es kann danach nicht mehr bearbeitet werden.</div>

                </div>
                <div class="modal-footer custom_modal_footer justify-content-center">
                    <button style="width: 46%;" type="button" class="btn button-primary"
                            data-dismiss="modal">Abbrechen
                    </button>
                    <a style="width: 46%;" type="submit"
                        class="btn button-primary-orange" href="/ticket/closeTicket/{{ $ticket->id }}">Bestätigen
                    </a>
                </div>
        </div>
    </div>
</div>

<!-- Modal - Support Agent wechseln -->
<div class="modal fade" id="modal-changeAgent" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content custom_modal">
            <form action="/ticket/changeAgent/{{ $ticket->id }}" method="post">@csrf
                <div class="modal-body">
                    <h4 class="h2" id="exampleModalLabel" style="margin-bottom: 16px;">Support Agent wechseln</h4>
                    <select name="newSupportagent" class="form-control">
                        <option selected>Bitte wählen</option>
                        @foreach ($users as $user)
                            <option value="{{ $user->user->id }}">{{ $user->user->username }} | {{ $user->user->fullname}} </option>   
                        @endforeach
                    </select>
                </div>
                <div class="modal-footer custom_modal_footer justify-content-center">
                    <button style="width: 46%;" type="button" class="btn button-primary"
                            data-dismiss="modal">Abbrechen
                    </button>
                    <button style="width: 46%;" type="submit"
                        class="btn button-primary-orange">Ändern
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal - Close Ticket -->
<div class="modal fade" id="modal-verifyOrder" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content custom_modal">
            <form action="" method="post">
            @csrf
                <input type="text" value="" name="id" hidden>
                <div class="modal-body">
                    <h4 class="h2" id="exampleModalLabel" style="margin-bottom: 16px;">Bestellung bestätigen</h4>

                    <label>Sind Sie sicher das Sie diese Bestellung bestätigen wollen?<br><br> Ihr Sticker Versand Team wird benachrichtigt
                     und der Kunde erhält seine Bestellbestätigung.</label>

                </div>
                <div class="modal-footer custom_modal_footer justify-content-center">
                    <button style="width: 46%;" type="button" class="btn button-primary"
                            data-dismiss="modal">Abbrechen
                    </button>
                    <button style="width: 46%;" type="submit"
                        class="btn button-primary-orange">Bestätigen
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>


<!-- JS Section -->
<script>

    $(() => {
        renderMessages();
        setInterval(() => {
            renderMessages();
        }, 5000); 
    });



    var editor = document.getElementById("answerTextarea");
    var snippetDiv = document.getElementById("snippetDiv");
    editor.addEventListener("keyup", showTextSnippets);
  
    function showTextSnippets() {
        const answerTextarea = document.getElementById('answerTextarea').value;
        const lastChar = answerTextarea.charAt(answerTextarea.length - 1);
        
        if(lastChar === '#') {
            snippetDiv.style.display = 'block';
        }
    }

    function closeTextSnippets() {
        snippetDiv.style.display = 'none';
    }

    function setTextSnippet(id) {
        console.log(id);
        const textSnippets = <?= json_encode($textSnippets); ?>;

        textSnippets.forEach(function(element) {
            if(element['id'] == id) {
                const answerTextarea = document.getElementById('answerTextarea');
                 answerTextarea.value = element['message'];
                 snippetDiv.style.display = 'none';
            }
        });
    }



    function renderMessages(pageLoad = false) {

        axios.get('/ticket/getTicketMessages/{{ $ticket->id }}')
            .then(response => {
                let renderHTML = formatMessages(response.data);
                $('#ticket-messages-render').html(renderHTML);

                if(pageLoad) {
                    $("#ticket-chat-scroll-element").scrollTop($("#ticket-chat-scroll-element")[0].scrollHeight);
                }

            })
            .catch(error => {
                console.log(error);
            });

    }

    function formatMessages(response) {

        let renderHTML = '';

        response.forEach(chat => {
            let created_at = new Date(chat.created_at).toString("dd.MM - HH:mm");
            /* web message */
            if(chat.author) {

                renderHTML += `
                <div class="chatAgentMessage">
                    <a>${chat.input}</a>
                    <div class="chatMessageTime">
                        <a>${created_at} Uhr</a>
                    </div>
                </div> 
                `;

            /* discord message */
            }else {
                renderHTML += `
                <div class="chatUserMessage">
                    <a>${chat.input}</a>
                    <div class="chatMessageTime">
                        <a>${created_at} Uhr</a>
                    </div>
                </div>
                `;
            }
            
        });

        return renderHTML;

    }

    function saveNote() {
        var editor_content = quill.getText();
        //var editor_content = quill.container.innerHTML;
        document.getElementById('noteContent').value = editor_content;
        document.getElementById("saveNote").submit();
    }

    const toolbarOptions = {
        container: [
            ['emoji'],   
        ],
        handlers: {'emoji': function() {}}
        }
        const quill = new Quill('#editor', {
        theme: 'snow',
        modules: {
            toolbar: toolbarOptions,
            "emoji-toolbar": true,
        }
    });

    var editorContent = @json($ticket->note);
    quill.root.innerHTML = editorContent;
</script>
