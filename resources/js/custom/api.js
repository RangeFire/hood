
// class System {
//     api = 'http://localhost:8000/api';

//     static post(endpoint, form_id, success = null, error = null) {
//         API.api = this.api;
//         API.post(endpoint, form_id, success, error);
//     }

// }

// class ToolTrack {
//     api = 'http://localhost:8001/api';

//     static post() {
//         API.api = this.api;
//         API.post(endpoint, form_id, success, error);
//     }

// }

class API {

    api;
    bearerToken;

    fallBackAPIRoute = 'http://localhost:8000/api';

    constructor(customAPI = null, bearer = null) {
        this.api = customAPI ?? this.fallBackAPIRoute;
        this.bearerToken = bearer ?? null;
    }

    post(endpoint, data, success = null, error = null, requestType) {
        this.request(endpoint, data, success, error, 'post');
    }

    set() {
        this.api = customAPI ?? this.fallBackAPIRoute;
        this.bearerToken = bearer ?? null;
    }

    disableSubmitButton(form) {
        $(`#${form} [type="submit"]`).attr('disabled', true);
    }

    enableSubmitButton(form) {
        $(`#${form} [type="submit"]`).attr('disabled', false);
    }

    request(endpoint, data, success = null, error = null, requestType) {

        if(!this.api) 
            return setTimeout(this.request(endpoint, data, success, error, requestType), 100);

        // no form
        if(data.constructor.name === 'Object') {

            this.doRequest(endpoint, data, success, error, 'post');

        // uses form
        }else {
            let form_id = data;

            $(form_id).unbind('submit');

            $(form_id).on('submit', (event) => {
                event.preventDefault();
                const formData = new FormData(form_id);

                this.doRequest(endpoint, formData, success, error, 'post')

            });
        }

    }

    doRequest(endpoint, data, success, error, requestType = 'post') {

        axios.defaults.headers.common = {'Authorization': `Bearer ${this.bearerToken}`}

        let request;

        if(requestType === 'post')
            request = axios.post(`${this.api}${endpoint}`, data);
        else if(requestType === 'patch')
            request = axios.patch(`${this.api}${endpoint}`, data);
        else if(requestType === 'delete')
            request = axios.delete(`${this.api}${endpoint}`, data);
        else if(requestType === 'put')
            request = axios.put(`${this.api}${endpoint}`, data);

        request.catch(function (error) {

            let status = error.response.status;

            $('.modal.fade.show').modal('hide');

            if(status != 200) {
                // this.enableSubmitButton(form_id);
            }

            if(status == 401) {
                return new Notification('error', 'Die Verbindung konnte nicht aufgebaut werden');
            }else if(status == 403) {
                return new Notification('error', 'Die Verbindungsdaten sind inkorrekt');
            }else{
                return new Notification('error', 'Ein unbekannter Fehler ist aufgetreten');
            }

        });

        request.then((res) => {

            $('.modal.fade.show').modal('hide');

            if(res.data.error == false) {
                if(typeof success === 'function')
                    success(res.data.data);
            }else {
                // this.enableSubmitButton(form_id);
                if(typeof success === 'function')
                    error(res.data.error, res.data.data);
            }
        });
    }


    static post(endpoint, form_id, success = null, error = null) {
        API.request(endpoint, form_id, success, error, 'post');
    }

    static put(endpoint, form_id, success = null, error = null) {
        API.request(endpoint, form_id, success, error, 'put');
    }

    static patch(endpoint, form_id, success, error) {
        API.request(endpoint, form_id, success, error, 'patch');
    }

    static delete(endpoint, form_id, success, error) {
        API.request(endpoint, form_id, success, error, 'delete');
    }

}

window.API = API;

// export { API/*, System, ToolTrack */};