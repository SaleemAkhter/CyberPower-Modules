
function pmToggleButton(data) {
    let button = $(".pmCreateButton");
    if(data.htmlData.createButtonStatus)
    {
        button.removeClass('hidden');
        $(".lu-has-dropdown").removeClass('hidden');
    }
    else
    {
        button.addClass('hidden');
        $(".lu-has-dropdown").addClass('hidden');
    }
}

function pmToggleDropDownButtons(data) {

    if($(".pmCreateButton").hasClass('hidden')){
        $(".lu-has-dropdown").addClass('hidden');
    }else{
        $(".lu-has-dropdown").removeClass('hidden');
    }
}

$(".mg-wrapper").on("change", "select[name='macro']", function () {
    var disable = $(this).val()!='0';
    if(disable){
        $("input[name='sport']").closest(".lu-form-group").addClass("disabled");
        $("input[name='dport']").closest(".lu-form-group").addClass("disabled");
        $("select[name='proto']").closest(".lu-form-group").addClass("disabled");
    }else{
        $("input[name='sport']").closest(".lu-form-group").removeClass("disabled");
        $("input[name='dport']").closest(".lu-form-group").removeClass("disabled");
        $("select[name='proto']").closest(".lu-form-group").removeClass("disabled");
    }
});

mgEventHandler.on('ModalLoaded', 'updateButton', function(id, params){
    if(id != 'updateButton'){
        return;
    }
     $(".mg-wrapper select[name='macro']").trigger('change');

}, 1000);