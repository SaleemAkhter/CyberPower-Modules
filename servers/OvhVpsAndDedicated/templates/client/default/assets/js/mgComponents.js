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
                        jQuery('#'+that.formId).find('input[name="'+name+'"]').each(function () {
                            if (jQuery(this).is(':checked')) {
                                value = jQuery(this).val();
                            }
                        });
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
                            that.data[name] = value;
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
                if(name !== undefined && errorsList !== undefined && Object.keys(errorsList).length > 0 && errorsList[name] !== undefined)
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

                mgPageControler.vueLoader.updateSorting(order, 'addCategoryForm', 'ModulesGarden_OvhVpsAndDedicated_App_UI_Widget_DoeTldConfigComponents_CategoryForms_AddCategoryForm');
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
            
            mgPageControler.vueLoader.updateSorting(order, 'addCategoryForm', 'ModulesGarden_OvhVpsAndDedicated_App_UI_Widget_DoeTldConfigComponents_CategoryForms_AddCategoryForm');
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
            mgPageControler.vueLoader.updateSorting(order, 'assignTldForm', 'ModulesGarden_OvhVpsAndDedicated_App_UI_Widget_DoeTldConfigComponents_CategoryForms_AssignTldForm');
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