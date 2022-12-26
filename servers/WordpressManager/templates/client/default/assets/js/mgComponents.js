function getItemNamespace (elId) {
    return jQuery('#' + elId).attr('namespace');
}

function getItemIndex (elId) {
    return jQuery('#' + elId).attr('index');
}

function initMassActionsOnDatatables(elId){
    $('#' + elId + ' [data-check-container]').luCheckAll({
        onCheck: function(container, counter) {
            var massActions = container.find('.lu-t-c__mass-actions');
            massActions.find('.lu-value').html(counter);
            if (counter > 0) {
                massActions.addClass('is-open');
            } else {
                massActions.removeClass('is-open');
            }
        }
    });
}

function collectTableMassActionsData(elId){
    var colectedData = {};
    $('#' + elId + ' [data-check-container] tbody input:checkbox.table-mass-action-check:enabled:checked')
        .each(function(index, value){
            colectedData[index] = $(this).val();
    });

    return colectedData;
}

function uncheckSelectAllCheck(elId){
    closeAllSelectMassActions();
}

function closeAllSelectMassActions(){
    $('.lu-t-c__mass-actions').removeClass('is-open');
    $('.table-mass-action-check').prop('checked', false);
    $('thead input:checkbox:enabled').prop('checked', false);
}

function initTooltipsForDatatables(elId) {
    $('#' + elId + ' [data-toggle="lu-tooltip"], [data-tooltip]').luTooltip({});    
}

function initModalSelects(){
    $('#mgModalContainer select:not(.ajax)').each(function(){
        if ($(this).attr('bi-event-change')) {
            var biEventAction = $(this).attr('bi-event-change');
            $(this).selectize({
                plugins: ['remove_button'],
                onItemAdd : function(value){
                    if (biEventAction && typeof mgPageControler[biEventAction] === "function") {
                        setTimeout(function(){
                            mgPageControler[biEventAction]();
                        }, 500);
                    }
                    else if (biEventAction && typeof mgPageControler.vueLoader[biEventAction] === "function") {
                        setTimeout(function(){
                            mgPageControler.vueLoader[biEventAction]();
                        }, 500);
                    } else if (biEventAction && typeof window[biEventAction] === "function") {
                        setTimeout(function(){
                            window[biEventAction]();
                        }, 500);
                    }
                }
            });
        } else {
            $(this).luSelect({});
        }
    });
}

function initModalSwitchEvents(){
    $('#mgModalContainer :checkbox').each(function(){
        if ($(this).attr('bi-event-change')) {
            var biEventAction = $(this).attr('bi-event-change');
            $(this).change(function() {
                    if (biEventAction && typeof mgPageControler[biEventAction] === "function") {
                        setTimeout(function(){
                            mgPageControler[biEventAction]();
                        }, 500);
                    }
                    else if (biEventAction && typeof mgPageControler.vueLoader[biEventAction] === "function") {
                        setTimeout(function(){
                            mgPageControler.vueLoader[biEventAction]();
                        }, 500);
                    } else if (biEventAction && typeof window[biEventAction] === "function") {
                        setTimeout(function(){
                            window[biEventAction]();
                        }, 500);
                    }
            });
        }
    });
}

function initModalTooltips(){
    $('#mgModalContainer [data-toggle="lu-tooltip"], [data-tooltip]').luTooltip({});
}

function mgFormControler(targetFormId) {
    this.fields = null;
    this.data = {};
    this.formId = targetFormId;
    
    this.loadFormFields = function(){
        var that = this;
       
        jQuery('#'+this.formId).find('input,select,textarea').each(function () {
            if (!jQuery(this).is(':disabled')) {
                var name = jQuery(this).attr('name');

                var value = null;

                if (name !== undefined) {
                    var type = jQuery(this).attr('type');
                    var regExp = /([a-zA-Z_0-9]+)\[([a-zA-Z_0-9]+)\]/g;
                    var regExpLg = /([a-zA-Z_0-9]+)\[([a-zA-Z_0-9]+)\]\[([a-zA-Z_0-9]+)\]/g;
                    
                    if (type === 'checkbox') {
                        var value = 'off';
                        if(jQuery('#'+that.formId).find('input[name="'+name+'"]').length>1){
                            if (jQuery(this).is(':checked')) {
                                value = jQuery(this).val();
                            }
                        }else{
                            jQuery('#'+that.formId).find('input[name="'+name+'"]').each(function () {
                                if (jQuery(this).is(':checked')) {
                                    value = jQuery(this).val();
                                }
                            });
                        }

                    } else if (type === 'radio') {
                        if (jQuery(this).is(':checked')) {
                            var value = jQuery(this).val();
                        }
                    } else {
                        var value = jQuery(this).val();
                    }
                    if (value !== null) {
                        if (result = regExpLg.exec(name)) {
                            if (that.data[result[1]] === undefined) {
                                that.data[result[1]] = {};
                            }
                            if (that.data[result[1]][result[2]] === undefined) {
                                that.data[result[1]][result[2]] = {};
                            }
                            that.data[result[1]][result[2]][result[3]] = value;
                        }else if (result = regExp.exec(name)) {
                            if (that.data[result[1]] === undefined) {
                                that.data[result[1]] = {};
                            }
                            that.data[result[1]][result[2]] = value;
                        } else {
                            if(typeof that.data[name] !== 'undefined'){
                                if(!Array.isArray(that.data[name])){
                                    var oldval=that.data[name];
                                    that.data[name]=[];
                                    that.data[name].push(oldval);
                                }
                                that.data[name].push(value);
                            }else{
                                that.data[name] = value;
                            }

                        }
                    }
                }
            }
        });
    };
    
    this.getFieldsData = function() {
        this.loadFormFields();
        
        return {formData : this.data};
    };
    
    this.updateFieldsValidationMessages = function(errorsList) {
        jQuery('#'+this.formId).find('input,select,textarea').each(function () {
            if (!jQuery(this).is(':disabled')) {
                var name = jQuery(this).attr('name');
                if(name !== undefined && errorsList[name] !== undefined)
                {
                    if(!jQuery(this).parents('.lu-form-group').first().hasClass('lu-is-error')) {
                        jQuery(this).parents('.lu-form-group').first().addClass('lu-is-error');
                    }
                    
                    var messagePlaceholder = jQuery(this).parents('.lu-form-group').first().children('.lu-form-feedback');
                    if(jQuery(messagePlaceholder).length > 0)
                    {    
                        jQuery(messagePlaceholder).html(errorsList[name].slice(-1)[0]);
                        if(jQuery(messagePlaceholder).attr('hidden')){
                            jQuery(messagePlaceholder).removeAttr('hidden');
                        }
                    }
                }else if(name !== undefined) {
                    if(jQuery(this).parents('.lu-form-group').first().hasClass('lu-is-error')) {
                        jQuery(this).parents('.lu-form-group').first().removeClass('lu-is-error');
                    }
                    var messagePlaceholder = jQuery(this).next('.lu-form-feedback');
                    if(jQuery(messagePlaceholder).length > 0){
                        jQuery(messagePlaceholder).html('');
                        if(!jQuery(messagePlaceholder).attr('hidden')){
                            jQuery(messagePlaceholder).attr('hidden', 'hidden');
                        }                        
                    }
                }
            }
        });
    };
};

//Sortable
function tldCategoriesSortableController() 
{
    var helperHeight = 0;

    //Add sortable for parent categories
    if (! $('#groupList.vSortable').hasClass('ui-sortable'))
    {
        $("#groupList.vSortable").sortable(
        {
            items: "li:not(.lu-nav--sub li, .sortable-disabled)",
            start: function(event, ui)
            {
                $(ui.item).find("ul").hide();
                $("#groupList").attr("isBeingSorted", "true"); 
            },
            stop: function(event, ui)
            {
                var order = [];
                $("#groupList .nav__item").each(function(index, element)
                {
                    if($(element).hasClass("ui-sortable-placeholder"))
                    {
                        return;
                    }

                    var catId = $(element).attr("actionid");
                    order.push(catId);
                });

                mgPageControler.vueLoader.updateSorting(order, 'addCategoryForm', 'ModulesGarden_WordpressManager_App_UI_Widget_DoeTldConfigComponents_CategoryForms_AddCategoryForm');
                $(ui.item).css("height", helperHeight);
                $(ui.item).find("a").css("height", 32);
                $(ui.item).find("ul").show();
            },
            sort: function(event, ui)
            {
                $( "#groupList" ).sortable( "refreshPositions" );
            },
            helper: function(event, li)
            {
                helperHeight = $(li).css("height");
                $(li).css("height", 32);
                return li;
            },
        });
    }
    
    //Add sortable for children - this has to be refreshed per catego content load
    $("#groupList .nav--sub").sortable(
    {        
        stop: function(event, ui)
        {
            var order = [];
            $(this).find(".nav__item").each(function(index, element)
            { 
                if($(element).hasClass("ui-sortable-placeholder"))
                {
                    return;
                }
                
                var catId = $(element).attr("actionid");
                order.push(catId);
            });
            
            mgPageControler.vueLoader.updateSorting(order, 'addCategoryForm', 'ModulesGarden_WordpressManager_App_UI_Widget_DoeTldConfigComponents_CategoryForms_AddCategoryForm');
        },
    });
    
    //Add Sortable on table
    $('#itemContentContainer tbody.vSortable').sortable(
    {
        stop: function(event, ui)
        {
            var order = [];
            $("#itemContentContainer tbody").find("tr").each(function(index, element)
            { 
                if($(element).hasClass("ui-sortable-placeholder"))
                {
                    return;
                }
                
                var catId = $(element).attr("actionid");
                order.push(catId);
            });
            mgPageControler.vueLoader.updateSorting(order, 'assignTldForm', 'ModulesGarden_WordpressManager_App_UI_Widget_DoeTldConfigComponents_CategoryForms_AssignTldForm');
        },
        helper: function(e, tr)
        {
            var $originals = tr.children();
            var $helper = tr.clone();
            $helper.children().each(function(index)
            {
                $(this).width($originals.eq(index).width()+100);
            });
            
            return $helper;
        },
    });
    
}


// CUSTOM FUNCTIONS

//this is example custom action, use it for non-ajax actions
function custAction1(vueControler, params, event){
    console.log('custAction1', vueControler, params, event);
}

//this is example custom action, use it for ajax actions
function custAction2(vueControler, params, event){
    console.log('custAction2', vueControler, params, event);
}

function mgEmptyToPause(name, row) {
    if (!row[name] || row[name] === '') {
        return '-';
    }
    else {
        return row[name];
    }
}

function newCall(data) {
    console.log(data);
}

function buildOptionTag(text, value, selected, disabled) {
    var option = document.createElement("option");
    option.text = text;
    option.value = value;
    if (selected) {
        option.setAttribute('selected', 'selected');
    }
    if (disabled) {
        option.setAttribute('disabled', 'disabled');
    }
    
    return option;
}

// jQuery(document).on("click",".expand",function(){
//     var element=jQuery(this).closest(".lu-widget").find(".lu-widget__body").is(':hidden');
//     console.log(element);
//     jQuery(this).closest(".lu-widget").find(".lu-widget__body").slideToggle(200,function(e) {
//         if (element)
//         {
//             jQuery(this).closest(".lu-widget").find(".expand").find(".lu-zmdi").removeClass("lu-zmdi-chevron-down").addClass("lu-zmdi-chevron-up");
//         }else{
//             jQuery(this).closest(".lu-widget").find(".expand").find(".lu-zmdi").removeClass("lu-zmdi-chevron-up").addClass("lu-zmdi-chevron-down");
//         }
//     });
// });
function toggle_advoptions(ele){
            if ($("#"+ele).is(":hidden")){
                $("#"+ele).slideDown("slow");
                $("#advoptions_toggle_plus").attr("class", "fas fa-minus-square");
            }
            else{
                $("#"+ele).slideUp("slow");
                $("#advoptions_toggle_plus").attr("class", "fas fa-plus-square");
            }
}
function upgrade_plugins(element){
    console.log(jQuery(element).html());
    jQuery(element).html('<span class="lu-btn__icon lu-temp-button-loader" style="margin: 0 0 0 0 !important; width: 140px;"><i class="lu-btn__icon lu-preloader lu-preloader--sm lu-preloader-success"></i></span>');
    $.ajax({
        type: "POST",
        data:{
            'upgrade_plugins':1
        },
        // Checking for error
        success: function(data){
            if(data.success){
                layers.alert.create({
                        $alertPosition: 'right-top',
                        $alertStatus: 'success',
                        $alertBody: data.message,
                        $alertTimeout: 10000
                    });
                jQuery(element).html("Upgrade Plugin(s) Now");
            }
        },
        error: function(data) {
            //alert(data.description);
            return false;
        }
    });
}
function upgrade_themes(element){
    console.log(jQuery(element).html());
    jQuery(element).html('<span class="lu-btn__icon lu-temp-button-loader" style="margin: 0 0 0 0 !important; width: 140px;"><i class="lu-btn__icon lu-preloader lu-preloader--sm lu-preloader-success"></i></span>');
    $.ajax({
        type: "POST",
       data:{
            'upgrade_themes':1
        },
        // Checking for error
        success: function(data){
            if(data.success){
                layers.alert.create({
                        $alertPosition: 'right-top',
                        $alertStatus: 'success',
                        $alertBody: data.message,
                        $alertTimeout: 10000
                    });
                jQuery(element).html("Upgrade Theme(s) Now");
            }
        },
        error: function(data) {
            //alert(data.description);
            return false;
        }
    });

}
function show_backup(){
    try{
        var auto_backup = $("#auto_backup").val();
        $select_region = $('#auto_backup_rotation').selectize();
        select_region = $select_region[0].selectize;
        if(auto_backup == 0){
            $("#auto_backup_rotation").prop("disabled", true);
            select_region.disable();
            $("#custom_autobackup_cron").css("display", "none");
        }else{
            if(auto_backup == "custom"){
                $("#custom_autobackup_cron").css("display", "block");
            }else{
                $("#custom_autobackup_cron").css("display", "none");
            }
            $("#auto_backup_rotation").prop("disabled", false);
            select_region.enable();
        }

    }catch(e){
        //
    }
    return true;
};
jQuery(document).on("click",".collapsablesection",function(){
    jQuery(this).closest(".lu-widget").find('.lu-widget__body').slideToggle();
});
function toggleSectionBody(){
    console.log(e.target,this);
}
function selectversion(){
    var v = 0;
    v = $_("softbranch").value;

    $("#multiver_wait").css("display","");

     $.ajax({
        type: "POST",
        url: "index.raw?act=software&soft="+v+"&tab=install&multi_ver=1&jsnohf=1",
        // Checking for error
        success: function(data){
            $("#multiver_wait").css("display","none");
            var $response=$(data);
            //query the jq object for the values
            var output = $response.find("#install_win").html();
            $("#install_win").html(output);

            //Quick install? this is because now the newhtml has been filled in win_div
            post_install_but();

            softmail();
            show_backup();
            rand_dbprefix();
            check_pass_strength();
            new_theme_funcs_init();

        },
        error: function(data) {
            //alert(data.description);
            return false;
        }
    });
}
softemail = new Object();
function softmail(){
    console.log(softemail);
    try{
        var use_eu_email = "0";
        if(use_eu_email > 0){
            return true;
        }

        var sofdom = "softdomain";
        for(x in softemail){
            if(softemail[x] == true) continue;
            var temp = $_(x).value.split("@");
            if($_(sofdom).value.indexOf("/") > 0){
                var dom_value = $_(sofdom).value.substring(0, $_(sofdom).value.indexOf("/"));
            }else{
                var dom_value = $_(sofdom).value;
            }
            $_(x).value = temp[0] + "@" + dom_value;
        }
    }catch(e){
        return false;
    }
};

function prepend_prefix(prefix){

    // Decide prefix USERNAME, DOMAIN OR USER DEFINED
    if(prefix != "" && prefix == "username") prefix = "arka";
    if(prefix != "" && prefix == "domain") prefix = "cash.dmowebsite.xyz";
    if(prefix != "" && prefix != "domain" && prefix != "username");

    // APPEND OLD USERNAME OR DEFAULT USERNAME
    var old = $_("admin_username").value
    temp = $_("admin_username").value.split("-");
    if (typeof temp[1] == 'string' || temp[1] instanceof String){
        $_("admin_username").value = prefix+"-"+temp[1];
    }else{
        $_("admin_username").value = prefix+"-"+old;
    }
}
function change_admin_prefix(domain){
    if(domain.length){
        jQuery("#admin_email").val("admin@"+domain);
    }
    var admin_prefix = '';
    var random_username = 'true';
    var empty_username = 'true';
    var gl_random_username = 'true';
    if(admin_prefix == "domain" && random_username == "true" && empty_username == "true" && gl_random_username == "true"){
        prepend_prefix(domain);
    }
}
jQuery(document).ready(function(){
    if(jQuery("#softdomain").length){
        jQuery("#softdomain").trigger("change");
    }
});
