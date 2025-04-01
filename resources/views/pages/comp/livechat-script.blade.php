

    const appURL = '{{ env('APP_URL') }}';
    const livechat_token = '{{ $livechat_token }}';

    let chatData = httpRequest('get', `${appURL}/livechat/${livechat_token}/v1`);
    console.log(`${appURL}/livechat/${livechat_token}/v1`);
    var template = document.createElement('template');
    html = chatData.trim(); // Never return a text node of whitespace as the result
    template.innerHTML = html;
    document.body.appendChild(template.content.firstChild);
    console.log(template.content.firstChild);

    var chatWindow = document.getElementById("chatWindow");

    function enterChat() {

        // search for livechat_ticket_token in cookies
        let livechat_ticket_token = getCookie('livechat_ticket_token');

        if(livechat_ticket_token) {
            reopenChat();
        }else {
            openNewChat();
        }

    }

    function openMainMenu() {
            var livechatWindow = document.getElementById("livechatWindow");
            livechatWindow.style.display = 'none';
        } 

    function openNewChat() {

        setTimeout(() => {
            startChat();
        }, 100);

    }

    function createNewChatTicketIfNotOpen() {
        let response = callAPI('openTicket', {});

        if(response == 'error') return console.error('Error while opening new chat');

        response = JSON.parse(response);

        if(!response.livechat_ticket_token) return console.error('Error while getting livechat_ticket_token');

        let expires = new Date();
        expires.setTime(expires.getTime() + (60 * 24 * 60 * 60 * 1000));
        document.cookie = `livechat_ticket_token=${response.livechat_ticket_token};expires=${expires};path=/`;
    
        setInterval(() => {
            loadLiveChatMessages();
        }, 5000);
        
    }

    function reopenChat() {
        startChat();

        loadLiveChatMessages();
        setInterval(() => {
            loadLiveChatMessages();
        }, 5000);

    }

    function getCookie(name) {
        const value = `; ${document.cookie}`;
        const parts = value.split(`; ${name}=`);
        if (parts.length === 2) return parts.pop().split(';').shift();
        return false;
    }

    function loadLiveChatSettings() {

        let responseSettings = callAPI('loadLiveChatSettings', {
            ticket_token: getCookie('livechat_ticket_token')
        });

        let responseProject = callAPI('loadProjectData', {
            ticket_token: getCookie('livechat_ticket_token')
        });

        let responseWhitelabelStatus = callAPI('loadProjectBrandingStatus', {
            ticket_token: getCookie('livechat_ticket_token')
        });

        responseSettings = JSON.parse(responseSettings);
        responseProject = JSON.parse(responseProject);
        responseWhitelabelStatus = JSON.parse(responseWhitelabelStatus);

        //TODO: Hier einbauen das dies nur ausgeführt wird wenn != null in DB
        if(responseSettings.chat_headline != null) document.getElementById('projectChatHeadline').text = responseSettings.chat_headline;
        if(responseSettings.chat_subtitle != null) document.getElementById('projectChatSubtitle').text = responseSettings.chat_subtitle;
        if(responseSettings.bubble_image != null) document.getElementById('projectChatBubbleImage').src = responseSettings.bubble_image;
        
        if(responseWhitelabelStatus == "off") {
            document.getElementById('projectLogoMain').src = responseProject.logo
            document.getElementById('projectLogoChat').src = responseProject.logo
            document.getElementById('showWhitelabelMain').style.display = 'none';
            document.getElementById('showWhitelabelChat').style.display = 'none';
        }

        if(responseProject.error) {
            let error = responseProject.error;
            if(error == 'error') return console.error('Error while loading livechat messages');
        }

    }

    function loadLiveChatMessages() {

        let response = callAPI('loadTicketMessages', {
            ticket_token: getCookie('livechat_ticket_token')
        });

        response = JSON.parse(response);

        console.log(response);

        if(response.error) {
            let error = response.error;
            if(error == 'error') return console.error('Error while loading livechat messages');
            if(error == 'ticket_closed' || error == 'no_ticket_found') return openNewChat();
        }

        
        clearMessages();

        for(let message of response.messages) {

            if(message.author) {
                addMessage('agent', message.input);
            }else {
                addMessage('user', message.input);
            }

        }

        // set supportername
        if(response.supportername && response.supportername != '') {
            document.getElementById('supportername').innerHTML = `Du spricht mit ${response.supportername}`;
        }

    }

    function messageLiveChat() {
        console.log("Called")
        let message = document.getElementById('liveChatAnswer').value;

        if(message.length > 0) {

            let livechat_ticket_token = getCookie('livechat_ticket_token');
            if(!livechat_ticket_token) {
                createNewChatTicketIfNotOpen();
            }            

            let response = callAPI('messageTicket', {
                ticket_token: getCookie('livechat_ticket_token'),
                message: message
            });

            addMessage('user', message);

            document.getElementById('liveChatAnswer').value = '';

        }

        return false;

    }

    const addMessage = (type, message) => {
        let chatContent = document.getElementById("hood-chat-content");

        if(type == 'user') {
            chatContent.innerHTML += userText(message);
        }else if(type == 'agent') {
            chatContent.innerHTML += agentText(message);
        }
    }

    const clearMessages = () => {
        let chatContent = document.getElementById("hood-chat-content");
        chatContent.innerHTML = '';
    }

    const agentText = (message) => {
        return `
        <div class="hood_agentText">
            <a>${message}</a>
        </div>`;
    }

    const userText = (message) => {
        return `
        <div class="hood_userText">
            <a>${message}</a>
        </div>`;
    }

    function callAPI(endpoint, data) {
        let api = `${appURL}/livechat/${livechat_token}/v1/${endpoint}`;
        return httpRequest('post', api, data);
    }

    function httpRequest(type, url, data = null) {
        if(type == 'post') {

            var xmlHttp = new XMLHttpRequest();
            xmlHttp.open("POST", url, false ); // false for synchronous request
            xmlHttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

            xmlHttp.send(formatPostData(data));

            return xmlHttp.responseText;
        }else {
            var xmlHttp = new XMLHttpRequest();
            xmlHttp.open("GET", url, false ); // false for synchronous request
            xmlHttp.send(null);
            return xmlHttp.responseText;
        }
    }

    const formatPostData = (data) => {

        let sendString = '';

        for (var key in data) {
            if (sendString.length > 0) {
                sendString += '&';
            }
            sendString += key + '=' + encodeURIComponent(data[key]);
        }

        return sendString;

    }

    let toggleState = true;

    function toggleChatWindow() {

        let chatWindows = document.getElementById("chat-windows");

        if(toggleState) {
            chatWindows.style.display = 'block';
        }else {
            chatWindows.style.display = 'none';
        }

        toggleState = !toggleState;

    }

    function hrefTo(url){
        window.open(url, '_blank').focus();
    }

    function startChat() {
        var livechatWindow = document.getElementById("livechatWindow");
        livechatWindow.style.display = 'block';
    }

    function tst() {
        console.log('test');
    }

    function uploadLivechatToggleImage() {
        const clientChatBubble = filestack.init("AhY2QLM5BQViijG7RYx4iz");
        const clientChatBubbleOptions = {
             fromSources: ["local_file_system","url"],
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
            onUploadDone: (res) => saveChatBubbleImage(res),
        };
        clientChatBubble.picker(clientChatBubbleOptions).open();
    }

    function saveChatBubbleImage(response) {
        let imageURL = "http://storage.mycraftit.com/" + response.filesUploaded[0].key;
        document.getElementById('liveChatBubbleImageURL').value = imageURL;
        document.getElementById("editLiveChatBubbleImage").submit();
    }
    
    loadLiveChatSettings();
