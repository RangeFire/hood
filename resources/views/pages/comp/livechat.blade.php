<div class="global-chat-element">
{{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css" integrity="sha512-MV7K8+y+gLIBoVD59lQIYicR65iaqukzvf/nwasF0nqhPay5w/9lJmVM2hMDcnK1OnMGCdVK+iQrJ7lzPJQd1w==" crossorigin="anonymous" referrerpolicy="no-referrer" /> --}}
    <script src="https://kit.fontawesome.com/1f26011b9e.js" crossorigin="anonymous"></script>
    {{-- <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet"> --}}

    <style>
    @import url('https://fonts.cdnfonts.com/css/montserrat');
    
    * {
        
    }
    .chatBlob {
        display: flex;
        justify-content: center;
        width: 65px;
        height: 65px;
        position: fixed;
        background-color: #1F2128!important;
        border-radius: 50px;
        z-index: 99999;
    }

    .chatBlob:hover {
        cursor: pointer;
    }

    .chatBlob_bottomRight {
        bottom: 30px;
        right: 30px;
    }

    .chatBlob_bottomLeft {
        bottom: 30px;
        left: 30px;
    }

    .chatWindow {
        width: 360px;
        height: 650px;
        position: fixed;
        border-radius: 9px;
        background-color: #22252D;
        z-index: 99999;
    }

    .chatWindow_bottomRight {
        bottom: 110px;
        right: 30px;
    }

    .chatWindow_bottomLeft {
        bottom: 220px;
        left: 30px;
    }

    .chatWindow .hood-chatheader {
        background-color: #2A85FF!important;
        height: 17%;
        border-top-left-radius: 9px;
        border-top-right-radius: 9px;
        padding: 19px;
        position: relative;
    }

    .chatWindow .hood-chatbody {
        background-color: #22252D!important;
        /*height: 470px;*/
        border-radius: 9px;
        padding: 19px;
        position: relative;
        text-decoration: none!important;
        height: 83%;
    }


    /* Nicht Änderbar */
    a {
        font-family: 'Montserrat';
    }
    .chatWindow .headline {
        font-size: 23px;
        color: white!important;
        font-weight: 700;
        text-decoration: none!important;
    }

    .chatWindow .subtitle {
        font-size: 14px;
        color: white!important;
        font-weight: 500;
        text-decoration: none!important;
    }

    .chatWindow .hood-logo img{
        width: 30px;
        background: white!important;
        border-radius: 9px;
        padding: 7px;
        margin-bottom: 10px;
    }

    .chatWindow .whitelabel {

    }

    .whitelabel {
        position: absolute;
        bottom: 10px;
        left: 33%;
    }

    .whitelabel a {
        font-size: 14px;
        font-weight: 500;
        color: white!important;
        text-decoration: none;
        text-decoration: none!important;
    }

    .contentItem {
        margin: 10px 0px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        background-color: white!important;
        padding: 14px;
        border-radius: 9px;
        border: 2px solid transparent;
        box-shadow: 0 4px 15px 0 rgb(0 0 0 / 12%);
        text-decoration: none!important;
    }

    .contentItem:hover {
        border-color: #0062ff!important;
        cursor: pointer;
        text-decoration: none!important;
    }

    .contentItem .leftDiv {
        align-items: center;
        display: flex;
    }

    .contentItem img {
        width: 36px;
        border-radius: 9px;
        padding: 1px;
    }

    .contentItem a {
        margin-left: 10px;
        color: #22252D!important;
        font-weight: 700;
        font-size: 18px;
        text-decoration: none!important;
    }

    .contentItem i {
        font-size: 21px;
        text-decoration: none!important;
    }

    .liveChatContent {
        overflow-y: auto;
        height: 306px;
        width: 100%;
        display: flex;
        flex-direction: column;
        padding: 0 6px;
        text-decoration: none!important;
    }

    .hood_userText {
        background-color: #2A85FF!important;
        color: white!important;
        text-decoration: none!important;
        border-radius: 12px;
        padding: 10px;
        max-width: 60%;
        float: right;
        margin: 10px 0px;
        display: flex;
        margin-left: 30%;
        text-decoration: none!important;
    }

    .hood_agentText {
        background-color: #1F2128!important;
        border-radius: 12px;
        padding: 10px;
        color: white!important;
        text-decoration: none!important;
        max-width: 60%;
        margin: 11px 0px;
        display: flex;
        margin-right: 30%;
        text-decoration: none!important;
    }

    .hood_userText a, .agentText a {
        word-break: break-word;
    }

    .hood_agentText a:hover {
        cursor: unset!important;
        text-decoration: none!important;
    }

    .hood_answereField {
        position: absolute;
        bottom: 36px;
        width: 100%;
        display: flex;
        align-items: center;
        text-decoration: none!important;
    }

    .hood_answereField input {
        background-color: #353945!important;
        border: none;
        border-radius: 12px;
        padding: 10px 12px!important;
        color: white;
        width: 90%;
        text-decoration: none!important;
    }

    .hood_answereField .sendMessage {
        color: white;
        position: absolute;
        bottom: 7px;
        right: 42px;
        font-size: 18px;
        width: 10px;
        height: 50px;
        display: flex;
        /*flex-direction: column;*/
        justify-content: space-between;
        align-items: center;
        text-decoration: none!important;
    }

    .hood_answereField .sendMessage:hover {
        cursor: pointer;
    }
    /* Animationen */
    ::placeholder { /* Chrome, Firefox, Opera, Safari 10.1+ */
    color: #777E91!important;
    opacity: 1; /* Firefox */
    }

    :-ms-input-placeholder { /* Internet Explorer 10-11 */
    color: #777E91!important;
    }

    ::-ms-input-placeholder { /* Microsoft Edge */
    color: #777E91!important;
    }

    .goBackIcon {
        float: right;
        font-size: 19px;
        color: white;
        padding: 8px;
        border-radius: 9px;
        transition: 0.3s;
        text-decoration: none!important;
    }

    .goBackIcon:hover {
        background-color: rgba(0, 0, 0, 0.5);
    }

    .hood_messageTextbox{
        width: 89%;
        padding: 0.375rem 0.75rem;
        font-size: 1rem;
        line-height: 1.5;
        color: #495057!important;
        background-color: #fff;
        background-clip: padding-box;
        border: 1px solid #ced4da;
        border-radius: 0.25rem;
        transition: border-color .15s ease-in-out,box-shadow .15s ease-in-out;
        text-decoration: none!important;
    }
    </style>


    <div class="chatBlob chatBlob_bottomRight" onclick="toggleChatWindow()">
        <img class="chatBlobImage" id="projectChatBubbleImage" src="{{ env('APP_URL') }}/assets/images/logo/logoIconTransparent.png" />
    </div>

    <div id="chat-windows" style="display: none;">

        <div class="chatWindow chatWindow_bottomRight" id="chatWindow">
            <div class="hood-chatheader">
                <div class="hood-logo">
                    <img class="" id="projectLogoMain" src="{{ env('APP_URL') }}/assets/images/logo/logoIconTransparent.png" />
                </div>
                <div class="text">
                    <a class="headline" id="projectChatHeadline">Hi, wir sind Hood 👋</a><br>
                    <a class="subtitle" id="projectChatSubtitle">Cool, dass du da bist</a>
                </div>
            </div>
            <div class="hood-chatbody">
                <div class="hood-chatcontent">
    
                    <!-- Live Chat -->
                    <div class="contentItem" onclick="enterChat()">
                        <div class="leftDiv">
                            <img src="{{ env('APP_URL') }}/assets/images/icons/livechat.png">
                            <a>Chatte mit uns</a>
                        </div>
                        <i class="fa-solid fa-arrow-right-long"></i>
                    </div>
                    <!-- Live Chat END -->
    
                    <!-- Ticket -->
                    {{-- <div class="contentItem" onclick="hrefTo()">
                        <div class="leftDiv">
                            <img src="/assets/images/icons/ticket.png">
                            <a>Eröffne ein Ticket</a>
                        </div>
                        <i class="fa-solid fa-arrow-right-long"></i>
                    </div> --}}
                    <!-- Ticket END -->
    
                    <!-- Uptime -->
                    {{-- <div class="contentItem" onclick="hrefTo(test)">
                        <div class="leftDiv">
                            <img src="/assets/images/icons/uptime.png">
                            <a>System Status</a>
                        </div>
                        <i class="fa-solid fa-arrow-right-long"></i>
                    </div> --}}
                    <!-- Uptime END -->
    
                    <!-- Youtube -->
                    {{-- <div class="contentItem" onclick="hrefTo()">
                        <div class="leftDiv">
                            <img src="/assets/images/icons/youtube.png">
                            <a>Youtube Tutorials</a>
                        </div>
                        <i class="fa-solid fa-arrow-right-long"></i>
                    </div> --}}
                    <!-- Youtube END -->
    
                </div>
    
                <div class="whitelabel" id="showWhitelabelMain">
                    <a href="https://wehood.io" target="_blank">Wir nutzen Hood</a>
                </div>
            </div>
        </div>
    
        <div class="chatWindow chatWindow_bottomRight" id="livechatWindow" style="display: none;">
            <div class="hood-chatheader">
                <div class="hood-logo">
                    <img class="" id="projectLogoChat" src="{{ env('APP_URL') }}/assets/images/logo/logoIconTransparent.png" />
                    <div class="goBackIcon" onclick="openMainMenu()"><i class="fa-solid fa-arrow-left"></i></div>
                </div>
                <div class="text">
                    <a class="headline">Livechat</a><br>
                    <a class="subtitle" id="supportername">Warte auf Supporter...</a>
                </div>
            </div>
            <div class="hood-chatbody" style="padding-bottom: 42px;display: flex;">
                <div class="hood-chatcontent liveChatContent" id="hood-chat-content">
                </div>
                <div class="hood_answereField" id="emoji_anchor" style="margin-bottom: -23px;">
                    <div style="width: 90%;">
                        <form style="margin-bottom: 0px;" onsubmit="event.preventDefault(); return messageLiveChat();">@csrf
                            <textArea class="hood_messageTextbox" type="text" id="liveChatAnswer" name="liveChatAnswere" rows="2" placeholder="Deine Antwort"></textArea>
                        </form>
                    </div>
                    <div class="sendMessage">
                        <i onclick="messageLiveChat()" class="fa-solid fa-paper-plane"></i>
                        {{-- <i onclick="uploadLivechatToggleImage()" class="fa-regular fa-file"></i> --}}
                    </div>
                </div>
                <div class="whitelabel" id="showWhitelabelChat">
                    <a href="https://wehood.io" target="_blank">Wir nutzen Hood</a>
                </div>
            </div>
        </div>    

    </div>

    
    <script>
        let chatWindows = document.getElementById("chatWindow");
        let windowToggle = false;

        function toggleChatWindow() {
            if (!windowToggle) {
                chatWindows.style.display = 'block';
            } else {
                chatWindows.style.display = 'none';
            }
            windowToggle = !windowToggle;
        }

        function hrefTo(url){
            window.open(url, '_blank').focus();
        }

        function debug() {
            var livechatWindow = document.getElementById("livechatWindow");
            livechatWindow.style.display = 'block';
        }
    </script>

</div>