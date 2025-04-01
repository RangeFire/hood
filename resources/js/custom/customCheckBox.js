

class customCheckBox {


    type = 'blue';
    displayText = 'Lorem ipsum dolor sit amet, consectetur adipiscing';

    /* postname */
    customName = '';

    state;

    callback = {
        active: {

        },
        unactive: {
            
        },
    };

    constructor(refElement, option = null) {

        $(() => {
            this.contructHelper(refElement, option);

            this.renderMain(refElement);

            this.setupListener();

            this.initVal(option.initVal);

        });

    }

    contructHelper(refElement, option) {
        this.uniqueID = this.generateUniqueID();

        if(option) {
            if(option.type) this.type = option.type;
            if(option.displayText) this.displayText = option.displayText;
            if(option.customName) this.customName = option.customName;
        }
    }
    
    set(val) {
        this.initVal(val);
    }

    initVal(val) {

        if(!val) val = 0;

        if(val == 0) {
            $(`#check-active-${this.uniqueID}`).hide();
            $(`#check-unactive-${this.uniqueID}`).show();
            this.state = 0;
        }else if(val == 1) {
            $(`#check-active-${this.uniqueID}`).show();
            $(`#check-unactive-${this.uniqueID}`).hide();
            this.state = 1;
        }
        this.prepareData();
    }

    renderMain(refElement) {
        let html = `
        <div class="customCheckBoxContainer">
            
            <div class="check-box">
                <svg id="check-active-${this.uniqueID}" width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="8" cy="8" r="8" fill="#1784D5"/>
                    <path d="M11 6L6.875 10.5L5 8.45455" stroke="#F7F7F8" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <svg id="check-unactive-${this.uniqueID}" width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="8" cy="8" r="8" fill="#D9D9EF"/>
                </svg>
            </div>

            <div class="check-text">
                <small class="form-text text-muted">${this.displayText}</small>
            </div>

            <input id="check-input-helper-${this.uniqueID}" name="${this.customName ? this.customName : 'check-box'}" hidden>

        </div>
        `;

        $(refElement).append(html);

    }

    setupListener() {
        let self = this;
        $(`#check-active-${this.uniqueID}`).on('click', () => {
            self.handleClick('active')
        });
        $(`#check-unactive-${this.uniqueID}`).on('click', () => {
            self.handleClick('unactive')
        });

    }

    handleClick(type) {
        if(type == 'active') {
            $(`#check-active-${this.uniqueID}`).fadeOut();
            $(`#check-unactive-${this.uniqueID}`).fadeIn();
            this.state = 0;
            if(this.callback.unactive.callback) this.callback.unactive.callback();
        }else if(type == 'unactive'){
            $(`#check-active-${this.uniqueID}`).fadeIn();
            $(`#check-unactive-${this.uniqueID}`).fadeOut();
            if(this.callback.active.callback) this.callback.active.callback();
            this.state = 1;
        }
        this.prepareData();
    }

    at(type, callback) {
        if(type == 'set-active') {
            this.callback.active.callback = callback;
        }else if(type == 'set-unactive') {
            this.callback.unactive.callback = callback;
        }
    }

    prepareData() {
        $(`#check-input-helper-${this.uniqueID}`).val(this.state)
    }

    generateUniqueID() {
        return Date.now().toString(36) + Math.random().toString(36).substring(2);
    }

}

export { customCheckBox };