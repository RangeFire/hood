

class CustomUpload {

    uploadBtnText = 'Hochladen';
    onlyImagesAllowed = 'Es sind nur Bilder erlaubt!';
    onlyDocumentsAllowed = 'Es sind nur Dokumente erlaubt!';
    onlyExcelIsAllowed = 'Es sind nur Excel-Dateien (.xls, .xlsx, .csv) erlaubt!';
    displayText = 'Bild zum hochladen hier ablegen';
    successText = 'Das Bild wurde erfolgreich hochgeladen';
    formRequired = true;
    isOnlyExcel = false;

    initImage;

    isFinished = false;

    
    cropperNeeded = false;
    inlineNeeded = false;

    callBackFunctions;

    file = {};
    croppedFile = {};

    postConfig;

    cropper;
    cropperConfig;

    savedModalJQ;

    /* on init define crop, only upload or with inline image display */
    /** option = 'crop' || 'normal' || 'inline-display' || 'crop-inline-display' */
    constructor(refElement, option) {

        $(() => {
            this.contructHelper(refElement, option);
        });

    }

    contructHelper(refElement, option) {
        this.refElement = refElement;
        
        this.option = option;
        this.mode = option.mode;
        this.uniqueID = this.generateUniqueID();


        if(option.displayText) this.displayText = option.displayText;
        if(option.successText) this.successText = option.successText;
        if(option.onlyImagesAllowed) this.onlyImagesAllowed = option.onlyImagesAllowed;
        if(option.uploadBtnText) this.uploadBtnText = option.uploadBtnText;
        if(option.hasOwnProperty('formRequired')) this.formRequired = option.formRequired;
        if(option.initImage) if(this.checkImageUrlExist(option.initImage)) this.initImage = option.initImage;
        if(option.postConfig) this.postConfig = option.postConfig;
        if(option.callBacks) this.callBackFunctions = option.callBacks;
        if(option.cropperConfig) this.cropperConfig = option.cropperConfig;
        if(option.isOnlyExcel) this.isOnlyExcel = option.isOnlyExcel;

        if(this.mode == 'crop')
            this.crop();
        else if(this.mode == 'normal')
            this.normal();
        else if(this.mode == 'inline-display')
            this.inlineDisplay();
        else if(this.mode == 'crop-inline-display')
            this.cropInlineDisplay();
        else if(this.mode == 'document')
            this.initDocumentUpload();
        else
            this.normal();

        this.setupListener();
    }

    changeInlineImage(url = '') {

        $(`#image-drop-inline-image-${this.uniqueID}`).attr('src', url ?? '');

    }

    initDocumentUpload() {

        this.render();

    }

    /* EXPERIMENTAL */
    checkImageUrlExist(url) {
        return true;
        // if(!url || url == '') return false;
        // var xhr = new XMLHttpRequest();
        // xhr.open('HEAD', url);
        // xhr.send();

        // if (xhr.status == "404" || xhr.status == '500') {
        //     return false;
        // } else {
        //     return true;
        // }
    }

    crop() {
        this.cropperNeeded = true;
        this.render();
    }

    normal() {
        this.render();
    }

    inlineDisplay() {
        this.inlineNeeded = true;
        this.render();
    }

    cropInlineDisplay() {
        this.cropperNeeded = true;
        this.inlineNeeded = true;
        this.render();
    }

    setMainRenderElement() {
        let renderElement = `
        <div id="image-drop-${this.uniqueID}" class="image-drop mt-3">
            <div class="image-drop-text">
                <img id="image-drop-inline-image-${this.uniqueID}" style="object-fit: contain;/* height: auto;width: 50% */ max-height: 250px; max-width:50%;border-radius: 15px;" ${this.initImage ? `src="${this.initImage}"` : ''}></img>
                <h3 id="image-drop-action-${this.uniqueID}" style="margin-left: 26px;">
                    <span id="image-drop-infotext-${this.uniqueID}">${this.displayText}</span>
                    <br>
                    <button id="image-drop-upload-btn-${this.uniqueID}" class="upload-btn">
                        <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M15.75 11.25V14.25C15.75 14.6478 15.592 15.0294 15.3107 15.3107C15.0294 15.592 14.6478 15.75 14.25 15.75H3.75C3.35218 15.75 2.97064 15.592 2.68934 15.3107C2.40804 15.0294 2.25 14.6478 2.25 14.25V11.25" stroke="#14142A" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M12.75 6L9 2.25L5.25 6" stroke="#14142A" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M9 2.25V11.25" stroke="#14142A" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        ${this.uploadBtnText}
                    </button>

                    <input id="image-drop-forminput-${this.uniqueID}" name="customFileToUpload" type="text"
                        style="width: 0px;height: 0px;border: solid 1px;position:absolute;left: 50%;transform: translate(0px, 15px);z-index: -99"
                        ${this.formRequired ? 'xxxrequired' : ''}>

                    <input id="image-drop-inputhelper-${this.uniqueID}" type="text"
                        style="width: 0px;height: 0px;border: solid 1px;position:absolute;left: 50%;transform: translate(0px, 15px);z-index: -99"
                        ${this.formRequired ? 'required' : ''}>
                </h3>
            </div>
        </div>
        `;
        this.renderElement = renderElement;

        /* hides actions on initimage */
        // if(this.initImage) this.handleUploadActions('hide');

    }

    handleUploadActions(type) {
        let e = $(`#image-drop-${this.uniqueID} .image-drop-text h3`);
        if(type == 'hide') $(e).hide();
        if(type == 'show') $(e).show();
    }

    cropperModalExists() {
        let cropperModal = $(`#modal-${this.uniqueID}-customFileUploadCropper`);
        if(cropperModal)
            if(cropperModal.length > 0) return true;

        return false;
    }

    render() {
        if(this.cropperNeeded && !this.cropperModalExists())
            this.renderCropper();

        this.setMainRenderElement();
        $(this.refElement).append(this.renderElement);
    }

    renderCropper() {
        let renderElement = `
        <div class="modal fade" id="modal-${this.uniqueID}-customFileUploadCropper" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content custom_modal">
                    <div class="modal-header custom_modal_header">
                        <h4 class="modal-title h4" id="exampleModalLabel">Bild zuschneiden</h4>
                    </div>
                    <div class="modal-body">

                        <div class="row">
                            <div class="col-xl-12" style="overflow: hidden;margin-right: 20px;">
                                <img src="" alt="" id="customFileUploadCropper-${this.uniqueID}-img" style="width: auto; height: 50vh;">
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer  justify-content-center">
                        <button style="width: 46%;" type="button" class="btn button-primary" id="customFileUploadCropper-${this.uniqueID}-cancel">Abbrechen
                        </button>
                        <button style="color: #FCFCFC; width: 46%;" type="button" id="customFileUploadCropper-${this.uniqueID}-submit"
                            class="btn button-primary-orange">Bestätigen
                        </button>
                    </div>
                </div>
            </div>
        </div>
        `;
        $('body').append(renderElement);
    }

    openCropper() {
        let callsFromAModal = $('.modal.fade.show').length > 0 ? true : false;


        if(callsFromAModal) {
            this.savedModalJQ = $('.modal.fade.show');
            localStorage.setItem('savedModalJQ', this.savedModalJQ.attr('id'));

            $(this.savedModalJQ).modal('hide');

            setTimeout(() => {
                $(`#modal-${this.uniqueID}-customFileUploadCropper`).modal('show');
            }, 500);

        }else {
            $(`#modal-${this.uniqueID}-customFileUploadCropper`).modal('show');
        }

    }

    closeCropper() {

        if(!this.savedModalJQ)
            if(localStorage.getItem('savedModalJQ'))
                this.savedModalJQ = $('#'+localStorage.getItem('savedModalJQ'));

        if(this.savedModalJQ) {
            
            $(`#modal-${this.uniqueID}-customFileUploadCropper`).modal('hide');
            setTimeout(() => {
                $(this.savedModalJQ).modal('show');
            }, 500);
        }else {
            $(`#modal-${this.uniqueID}-customFileUploadCropper`).modal('hide');
        }

    }

    generateUniqueID() {
        return Date.now().toString(36) + Math.random().toString(36).substring(2);
    }

    setupListener() {
        let self = this;

        $(`#image-drop-${this.uniqueID}`)
            .on('dragover', false)
            .on('drop', async function (e) {
                e.preventDefault();
                event.preventDefault();
                self.handleFile(await event);

                return false;
            });

        $(`#customFileUploadCropper-${this.uniqueID}-submit`).click(() => {
            this.finalizeImageCrop();
        });

        $(`#image-drop-${this.uniqueID}`).click(function() {
        // $(`#image-drop-upload-btn-${this.uniqueID}`).click(function() {
            var input = $(document.createElement("input"));

            input.attr("type", "file");

            /* only xls */
            if(self.mode == 'document' && self.isOnlyExcel) {
                input.attr("accept", ".xlsx, .xls, .csv");
            /* regular document */
            }else if(self.mode == 'document') {
                input.attr("accept", "application/*");
            /* regular image */
            }else {
                input.attr("accept", "image/*");
            }

            input.trigger("click");

            $(input).on('change', (evt) => {
                // get value and send it to handle file function

                var files = evt.target.files; // FileList object
                
                if(!files) return;
                if(!files[0]) return;

                self.handleFile(null, files[0]);
            });

            return false;
        });

        $(`#customFileUploadCropper-${this.uniqueID}-cancel`).click(() => {
            this.closeCropper();
        });

        $(`#modal-${this.uniqueID}-customFileUploadCropper`).on('show.bs.modal', () => {
            setTimeout(() => {
                self.initCropper();
            }, 500)
        });
        
    }

    initCropping() {
        let self = this;
        this.openCropper();
    }

    initCropper() {
        let self = this;
        if(this.cropper) this.cropper.destroy();
        
        let image = document.getElementById(`customFileUploadCropper-${this.uniqueID}-img`);

        let aspectRatio = 1 / 1;
        if(this.cropperConfig && this.cropperConfig.aspectRatio)
            aspectRatio = this.cropperConfig.aspectRatio;

        this.cropper = new Cropper(image, {
            aspectRatio: aspectRatio,
        });

    }
    

    finalizeImageCrop() {

        let self = this;

        let cropper = this.cropper;

        this.closeCropper();

        let width = 160, height = 160, minWidth = 1024, minHeight = 1024, maxWidth = 4096, maxHeight = 4096;

        if(this.cropperConfig) {
            if(this.cropperConfig.width)
                width = this.cropperConfig.width;
            if(this.cropperConfig.height)
                height = this.cropperConfig.height;

            if(this.cropperConfig.minWidth)
                minWidth = this.cropperConfig.minWidth;
            if(this.cropperConfig.minHeight)
                minHeight = this.cropperConfig.minHeight;

            if(this.cropperConfig.maxWidth)
                maxWidth = this.cropperConfig.maxWidth;
            if(this.cropperConfig.maxHeight)
                maxHeight = this.cropperConfig.maxHeight;
        }
        
        cropper.getCroppedCanvas({
            // width: width,
            // height: height,
            minWidth: minWidth,
            minHeight: minHeight,
            maxWidth: maxWidth,
            maxHeight: maxHeight,
            fillColor: '#fff',
            imageSmoothingEnabled: true,
            imageSmoothingQuality: 'high',
        }).toBlob(blob => {
            
            let reader = new FileReader();
            reader.readAsDataURL(blob);
            reader.onloadend = function() {     
                let finalBlob = reader.result;

                self.croppedFile = {
                    info: {
                        name: self?.file?.info?.name ?? localStorage.getItem('fileName'),
                        size: blob.size,
                        type: blob.type,
                    },
                    blob: finalBlob,
                }

                if(self.inlineNeeded) {
                    $(`#image-drop-inline-image-${self.uniqueID}`).hide().attr('src', finalBlob).show();
                }else {
                    $(`#image-drop-infotext-${self.uniqueID}`).html(this.successText);
                }

                self.prepareForm();

                if(self.callBackFunctions)
                    if(self.callBackFunctions.onFinalize)
                        self.callBackFunctions.onFinalize();

            }

        }/*, 'image/png' */);

    }


    async handleFile(event, prepFile = null) {

        let self = this;
        let file;

        if(prepFile){
            file = prepFile;
        }else{
            file = await event.dataTransfer.files[0];
        }

        let fileReader = new FileReader();
        fileReader.onload = () => {
            let fileURL = fileReader.result;
            let fileInfo = file;
            
            this.file.info = {
                name: fileInfo.name,
                size: fileInfo.size,
                type: fileInfo.type,
            };

            localStorage.setItem('fileName', fileInfo.name);

            this.file.blob = fileURL;

            let fullType = file.type;

            let type = fullType.split('/')[0];

            /* only excel formats */
            if( (fullType == 'text/csv' || fullType == 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' || fullType == 'application/vnd.ms-excel')
                && this.mode == 'document' && this.isOnlyExcel) {
                
                $(`#image-drop-inline-image-${this.uniqueID}`).css('width', '80px').css('height', '80px').attr('src', '/assets/svg/card-blue-file.svg').show();
                $(`#image-drop-infotext-${this.uniqueID}`).html(fileInfo.name + ' erfolgreich erkannt');
                $(`#image-drop-action-${this.uniqueID}`).css('display', 'initial');
                $(`#image-drop-upload-btn-${this.uniqueID}`).css('display', 'none');

            }else if (type == 'application' && this.mode == 'document' && !this.isOnlyExcel) {
                $(`#image-drop-inline-image-${this.uniqueID}`).css('width', '80px').css('height', '80px').attr('src', '/assets/svg/card-blue-file.svg').show();
                $(`#image-drop-infotext-${this.uniqueID}`).html(fileInfo.name + ' erfolgreich erkannt');
                $(`#image-drop-action-${this.uniqueID}`).css('display', 'initial');
                $(`#image-drop-upload-btn-${this.uniqueID}`).css('display', 'none');
            } else if (type == 'image'  && this.mode != 'document') {
                if(this.inlineNeeded) {
                    $(`#image-drop-inline-image-${this.uniqueID}`).attr('src', fileURL).show();
                }
                $(`#image-drop-infotext-${this.uniqueID}`).html(this.successText);
            }else {
                $(`#image-drop-inline-image-${this.uniqueID}`).hide();
                $(`#image-drop-upload-btn-${this.uniqueID}`).css('display', 'initial');
                $(`#image-drop-action-${this.uniqueID}`).css('display', 'initial');

                if(this.mode == 'document' && this.isOnlyExcel)
                    $(`#image-drop-infotext-${this.uniqueID}`).html(this.onlyExcelIsAllowed);
                else if(this.mode == 'document' && !this.isOnlyExcel)
                    $(`#image-drop-infotext-${this.uniqueID}`).html(this.onlyDocumentsAllowed);
                else
                    $(`#image-drop-infotext-${this.uniqueID}`).html(this.onlyImagesAllowed);

                return;
            }

            if(this.cropperNeeded) {
                $(`#customFileUploadCropper-${this.uniqueID}-img`).attr('src', fileURL).show();
                setTimeout(() => {
                    this.initCropping();
                }, 1000);
            }

            self.prepareForm();

        }
        let operation = await fileReader.readAsDataURL(file);
        self.handleUploadActions('hide');
    }

    hasFinished() {
        return this.isFinished;
    }

    prepareForm() {

        this.isFinished = true;

        if(this.postConfig) {
            $(`#${this.postConfig.fileInfo}`).val(JSON.stringify(this.getFile().info));
            $(`#${this.postConfig.fileBlob}`).val(JSON.stringify(this.getFile().blob));
        }
        $(`#image-drop-forminput-${this.uniqueID}`).val(JSON.stringify(this.getFile()));
        $(`#image-drop-inputhelper-${this.uniqueID}`).val('set');

        if(this.callBackFunctions)
            if(this.callBackFunctions.onFormSendReady)
                this.callBackFunctions.onFormSendReady();

    }

    _getPreInfo(){
        return this.file;
    }

    getFile(){

        //check for empty object
        if(Object.keys(this.croppedFile).length === 0 && this.croppedFile.constructor === Object) {
            console.log('normal file');
            return this.file;
        }

        console.log('cropped file');
        return this.croppedFile;
    }

    getInfo(){
        return this.getFile().info;
    }

    getBlob(){
        return this.getFile().blob;
    }

    get(){
        return this.getFile()
    }

}

export { CustomUpload }