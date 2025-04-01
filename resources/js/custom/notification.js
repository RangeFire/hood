class Notification {
    constructor(type, message, timeout = 2000){
        let uniqid = Math.floor(Math.random() * 999999);
        this.createNotification(uniqid, type, message);
        this.initNotification(uniqid, timeout);
    }

    initDiv(){
        // inits parent notifications
        if (!$(".notifications")[0]){
            if (!$(".page_content")[0]){
                $('body').append(`<div class="notifications" style="width: 100vw;"></div>`);
            }else{
                $('.page_content').append(`<div class="notifications"></div>`);
            }
        }
    }

    createNotification(uniqid, type, message) {
        this.initDiv();
        $('.notifications').prepend(`
            <div class="notification-container">
                <div id="notf-${uniqid}" class="notification ${type == 'success' ? 'notf-success' : 'notf-error'}">

                    ${type == 'success' ? `
                        <svg width="24" height="25" viewBox="0 0 24 25" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M20 6.5L9 17.5L4 12.5" stroke="#F7F7F8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    ` : `
                        <svg width="25" height="25" viewBox="0 0 25 25" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M10.7193 4.02083L1.89637 18.75C1.71447 19.065 1.61821 19.4222 1.6172 19.7859C1.61618 20.1497 1.71043 20.5074 1.89057 20.8235C2.07071 21.1395 2.33046 21.4028 2.64399 21.5873C2.95751 21.7718 3.31387 21.871 3.67762 21.875H21.3235C21.6872 21.871 22.0436 21.7718 22.3571 21.5873C22.6706 21.4028 22.9304 21.1395 23.1105 20.8235C23.2907 20.5074 23.3849 20.1497 23.3839 19.7859C23.3829 19.4222 23.2866 19.065 23.1047 18.75L14.2818 4.02083C14.0961 3.71469 13.8346 3.46157 13.5226 3.28591C13.2106 3.11025 12.8586 3.01797 12.5005 3.01797C12.1425 3.01797 11.7905 3.11025 11.4785 3.28591C11.1665 3.46157 10.905 3.71469 10.7193 4.02083V4.02083Z" stroke="#FCFCFC" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M12.5 9.375V13.5417" stroke="#FCFCFC" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M12.5 17.7083H12.5112" stroke="#FCFCFC" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    `}

                    <h1>${message}</h1>
                </div>
            </div>
        `);
    }

    initNotification(uniqid, timeout){
        $(`#notf-${uniqid}`).animate({
            top: "+=40",
            opacity: "1",
        }, 300, function() {
            try {
                setTimeout(() => {
                    $(`#notf-${uniqid}`).animate({
                        top: "-=40",
                        opacity: "0",
                    }, 300, function() {
                        $(`#notf-${uniqid}`).parent().remove();
                    });
                }, timeout); 
            } catch (error) {
                $(`#notf-${uniqid}`).parent().remove();
            }
        });
        $(`#notf-${uniqid}`).on('click', ()=>{
            try {
                $(`#notf-${uniqid}`).animate({
                    top: "-=40",
                    opacity: "0",
                }, 300, function() {
                    $(`#notf-${uniqid}`).parent().remove();
                });
            }catch (error) {
                $(`#notf-${uniqid}`).parent().remove();
            }
        });
    }

}

export { Notification };