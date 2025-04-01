let selectedElements = [];

let left = new Sortable(dragDropContent_left, {
    group: 'shared', // set both lists to same group
    animation: 150,
    draggable: ".dragDropElement",
});

let right = new Sortable(dragDropContent_right, {
    group: 'shared',
    animation: 150,
    draggable: ".dragDropElement",
    onAdd: function (evt) {
		checkRightEmpty();
	},
    onRemove: function (evt) {
        checkRightEmpty()
    },
});

$(() => {
    /* handles nothing found image on page load */
    checkRightEmpty();
})

function checkRightEmpty() {
    prepareToolContentSend();

    if(selectedElements.length == 0)
        $('#dragDropContent_right_emptyImage').fadeIn("slow");
    else 
        $('#dragDropContent_right_emptyImage').fadeOut("slow");
}

function prepareToolContentSend() {
    let data = [];
    $('#dragDropContent_right .dragDropElement').each((i, e) => {

        let name = $(e).data('name');
        let count = $($(e).find('.count div')[0]).html();

        data.push({
            name,
            count
        });

    })

    selectedElements = data;

    if(integratedForm) {
        $('#input_helper_toolContent').val(JSON.stringify(data));
    }else {
        $('#input_toolContent').val(JSON.stringify(data));
    }

}

function sendToolContent() {
    prepareToolContentSend();

    if(integratedForm) {
        if($('#input_helper_toolContent').val() == '') return;
        $('#modal-toolContent').modal('hide');
    }else {
        if($('#input_toolContent').val() == '') return;
        $('#form_toolContent').submit();
    }

}

function toolContentCounter(type, ref) {
    let count = parseInt($($(ref).parent().find('.count div')[0]).html());
    let calc;
    
    if(type == '-') calc = count - 1;
    if(type == '+') calc = count + 1;

    if(calc <= 0) calc = 1;
    if(calc > 99) calc = 99;

    $($(ref).parent().find('.count div')[0]).html(calc);
}

function toolContentSearch() {
    let searchWord = $('#toolContentSearch').val().toLowerCase();
    let elements = $('#dragDropContent_left').children();

    if(searchWord == '' || searchWord == ' ') {
        $(elements).show();
        return;
    }

    elements.each((i,e) => {
        let elementName = $(e).data('name').toLowerCase();

        if(elementName.includes(searchWord))
            $(e).show();
        else 
            $(e).hide();

    })

}

function isExisting(searchText) {

    let existingNames = [];

    $(`.dragDropElement[data-name]`).each((i,e) => {
        existingNames.push($(e).data('name').trim());
    })

    let check = existingNames.includes(searchText.trim());

    if(check) {
        console.log('existing');
        return true;
    }else {
        console.log('not existing');
        return false;
    }

}

function checkNameIsRightArea(name) {
    let check = $(`.tool-content-right .dragDropElement[data-name="${name}"]`).length;

    if(check) console.log('is right');
    if(!check) console.log('is left');

    if(check) return true;
    
    return false;

}

function moveToRight(name) {
    let isAlreadyRight = checkNameIsRightArea(name);

    if(isAlreadyRight) return true;

    /* element is not in right area */

    let html = $(`.dragDropElement[data-name="${name}"]`).prop('outerHTML');

    if(html.trim() == '') return false;

    $(`.dragDropElement[data-name="${name}"]`).remove();
    $('.tool-content-right').prepend(html);

    console.log('was moved');

    return true;

}

function addToolContent() {
    let text = $('#toolContentSearch').val();

    if(text.trim() == '') return;

    /* checks existing (no duplicates) */
    let exists = isExisting(text);

    if(exists) {
        /* moves existing element to right */
        let move = moveToRight(text);

        if(!move)
            return new Notification('error', 'Ein Fehler beim erstellen ist aufgetreten. Duplikate sind nicht erlaubt');

        /* finalizes creation */
        finalizeCustomContentAdd();

        return;
    }

    $('#dragDropContent_right').prepend(`
        <div data-wascreated="true" data-name="${text}" class="dragDropElement card noselect br-8">
            <a class="text-muted" data-toggle="tooltip" data-placement="top" title="${text}">${text}</a>
            <img src="${settings_BaseURL}/assets/svg/dragDropDots.svg" alt="">
            <div class="control">
                <div class="minus pointer" onclick="toolContentCounter('-', this)">
                    <img src="${settings_BaseURL}/assets/svg/border-minus.svg" alt="">
                </div>
                <div class="count">
                    <div class="text-muted" name="count">1</div>
                </div>
                <div class="plus pointer" onclick="toolContentCounter('+', this)">
                    <img src="${settings_BaseURL}/assets/svg/border-plus.svg" alt="">
                </div>
                <div class="trash pointer" onclick="trashToolContent(this)">
                    <img src="${settings_BaseURL}/assets/svg/trash-2.svg" alt="">
                </div>
            </div>
        </div>
    `);

    /* finalizes creation */
    finalizeCustomContentAdd();

}

function finalizeCustomContentAdd() {
    $('#toolContentSearch').val('');

    /* calls search function so the search list gets reseted after creation */
    toolContentSearch();

    /* checks removal of no content image */
    setTimeout(() => {
        checkRightEmpty();
    }, 500);
}

function trashToolContent(e) {
    let dragDropElement = $(e).parent().parent();

    /* if it was not created the elements will be prepended to the left column */
    if($(dragDropElement).data('wascreated') === false) {
        let html = $(dragDropElement).prop('outerHTML');
        $('#dragDropContent_left').prepend(html);
    }

    $(dragDropElement).remove();
}

window.checkRightEmpty = checkRightEmpty;
window.prepareToolContentSend = prepareToolContentSend;
window.sendToolContent = sendToolContent;
window.toolContentCounter = toolContentCounter;
window.toolContentSearch = toolContentSearch;
window.isExisting = isExisting;
window.checkNameIsRightArea = checkNameIsRightArea;
window.moveToRight = moveToRight;
window.addToolContent = addToolContent;
window.finalizeCustomContentAdd = finalizeCustomContentAdd;
window.trashToolContent = trashToolContent;