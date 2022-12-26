(function(jQuery) { //TODO: na klase
    var $last_obj;
    $(document).ready(function() {
        select2Create();
    });
    
    $(document).delegate('.mg-wrapper :checkbox', 'ifChanged', function() {
        $(this).trigger('change');
    });
    
    $(document).delegate('.mg-wrapper select.select2, .mg-wrapper select.select2x', 'select2-selected', function(e) {
        if($(e.choice.element).data('act'))
            doAction($(e.choice.element));
    });
    
    $(document).delegate('#MGConfirmationModal [data-confirm]', 'click', function(e) {
        doAction($last_obj);
    });
    
    $(document).delegate('.mg-wrapper [data-act]', 'click', function() {
        $last_obj = $(this);
        if($last_obj.data('confirm-body')) {
            if($last_obj.data('confirm-title'))
                $('#MGConfirmationModal .modal-title h1').html($last_obj.data('confirm-title'));
            else 
                $('#MGConfirmationModal .modal-title').html($('#MGConfirmationModal .default-title').html());
            $('#MGConfirmationModal .modal-body').html($last_obj.data('confirm-body'));
            
            var btn_class = $last_obj.attr("class").match(/btn-([\w-]*)\b/);
            if(btn_class)
                $('#MGConfirmationModal .modal-footer button').last().removeClass().addClass('btn').addClass('btn-' + btn_class[1]);
            else 
                $('#MGConfirmationModal .modal-footer button').last().removeClass().addClass('btn').addClass('btn-danger');
            
            $('#MGConfirmationModal').modal();
            $('body > .modal-backdrop').last().css('z-index', '1050');//.css('height', '100vh');
        } else {
            doAction($(this)); 
        }
    });

    function doAction($this) {

        var input_data = [];
        var modalobj = false;
        var validate = $this.data('no-validate') ? false : true;

        if($this.parents('.modal').length > 0) {
            modalobj = $this.parents('.modal').first();
            if(!$this.data('formid')) {
                input_data = $.merge(input_data, $(':input', modalobj).serializeArrayWithTurnedOffCheckboxes());
                if(validate && !isFormValidate(modalobj)) {
                    return 0;
                }
            }
        }

        if($this.data('query')) {
            input_data = $.merge(input_data, queryToArray($this.data('query')));
        }
        
        if($this.data('formid')) {
            var selector = '#' + $this.data('formid');
            if($this.data('dt')) {
                selector = $('#' + $this.data('formid')).DataTable().rows().nodes();
            }
            
            input_data = $.merge(input_data, $(':input', selector).serializeArrayWithTurnedOffCheckboxes());
            
            if(validate && !isFormValidate($('#' + $this.data('formid')))) {
                return 0;
            }
        }
        
        JSONParser.request($this.data('act'), input_data, function(data) {
            runGlobalFunctionFromString($this.data("callback"), data.response, $this);
            runGlobalFunctionFromString($this.data('act') + 'Callback', data.response, $this);

            if(data.modal) {
                if(modalobj) {
                    removeVisibleModal();
                }
                modalobjtemp = $('#MGModal').html(data.modal).find('.modal');
                modalobjtemp.modal('show');
                if(modalobj)
                    modalobj = modalobjtemp;
            }

            if(modalobj) {
                if(data.errors.length || typeof $this.data('do-not-close-the-modal') != 'undefined') {
                    if($('.mg-alerts', modalobj).length == 0) $('.modal-body', modalobj).prepend('<div class="mg-alerts"></div>');
                    showAlerts(data.errors, data.infos, $('.mg-alerts', modalobj).first());
                } else {
                    modalobj.modal('hide');
                    showAlerts(data.errors, data.infos);
                }
            } else {
                showAlerts(data.errors, data.infos);
            }
            
            if(data.refresh_html) {
                removeVisibleModal();
                $('.page-content > .container-fluid').html(data.refresh_html);
            }
            
            select2Create();
            
        });
    }

    function scrollTo($obj) {
        if($obj.parents('.modal').length)
            $obj.parents('.modal').first().find('.modal-body').animate({scrollTop: 0});
        else
            $('html,body').animate({scrollTop: $obj.offset().top - 20});
    }

    function select2Create() {
        try {
            $(".mg-wrapper select.select2").select2({
                containerCssClass: ' tpx-select2-container',
                dropdownCssClass: ' tpx-select2-drop'
            });
        } catch(e) {}
    }
    
    function removeVisibleModal() {
        if($('.modal:visible').length) {
            $('body').removeClass('modal-open').css('padding-right','');
            $('.modal:visible').hide();
            $('.modal-backdrop').remove();
        }
    }

    function isFormValidate($obj) {
        $obj.validator('validate');
        if ($('.has-error', $obj).length) {
            return false;
        }
        return true;
    }

    function runGlobalFunctionFromString(global_function, data, $obj) {
        if(typeof global_function != 'undefined' && typeof (window[global_function]) == "function") {
            window[global_function](data, $obj);
        }
    }

    function showAlerts(errors, infos, $obj) {
        if(typeof $obj == 'undefined') $obj = $('#MGAlerts');
        $.each(errors, function(index, value) {
            $obj.alerts('error',value);
        });
        $.each(infos, function(index, value) {
            $obj.alerts('success',value);
        });
        
        scrollTo($obj);
        
//        if($obj.parents('.modal').length)
//            $obj.parents('.modal').first().find('.modal-body').animate({scrollTop: 0});
//        else
//            $('html,body').animate({scrollTop: $obj.offset().top - 20});
    }

    function queryToArray(query) {
        var queryParameters = [], queryString = query, re = /([^&=]+)=([^&]*)/g, m;

        while (m = re.exec(queryString)) {
            if (typeof m[2] == 'string') {
                m[2] = m[2].replace(/\+/g, " ").replace(/%2b/g, '+');
            }
            queryParameters = $.merge(queryParameters, [{name: decodeURIComponent(m[1]), value: decodeURIComponent(m[2])}]);
        }
        return queryParameters;
    }

    function arrayToQuery(array) {
        return $.param(array);
    }
    
    jQuery.fn.serializeArrayWithTurnedOffCheckboxes = function() {
        var values = jQuery(this).serializeArray();
        values = values.concat(
            jQuery(this).filter('input[type=checkbox]:not(:checked)').map(
                function() {
                    return {"name": this.name, "value": ''};
                }).get()
        );
        return values;
    };
})(jQuery);
//==============================================================================

var JSONParser = {
    url:            false,
    type:           'post',
    startString:    '<JSONRESPONSE#',
    stopString:     '#ENDJSONRESPONSE>',
    currentPage:    false,
    requestCounter: 0,
    
    create: function(url,type){
        this.url = url;
        if(type !== undefined)
        {
            this.type = type;
        }
    },
    getJSON: function(json,disableError){
            this.requestCounter--;
            if(this.requestCounter == 0)
            {
                jQuery('#MGLoader').loader('hide');
            }
            
            var old = json;
            var start = json.indexOf(this.startString);            
            json = json.substr(start+this.startString.length,json.indexOf(this.stopString)-start-this.startString.length);
                               
            try{
                return jQuery.parseJSON(json);
            }catch(e)
            {
                //alert(old); //TODO: usunąć
                jQuery('#MGAlerts').alerts('error',"Somethings Goes Wrong, check logs, contact admin");
                jQuery('.modal.in').modal('hide');
                return false;
            }
    },
    request: function (action, data, callback, loader, disableErrors) {
        var details = {};
        var that = this;
       
        if(data === undefined){
            data = {};
        }

        var newData = {};

        if($.isArray(data)) {
            for (var prop in data) {
                newData[data[prop].name] = data[prop].value;
            }
            
            data = newData;
            
        }

        if(typeof data === "object"){
            data['mg-action'] = action;
            if(this.currentPage){
                data['mg-page']    = this.currentPage;
            }
        }else if(typeof data ===  "string"){
            data += "&mg-action="+action;
        if(this.currentPage)
              data +="&mg-page=" +this.currentPage;
        }

        if(loader === undefined) {
            jQuery('#MGLoader').loader();
        }else if(loader!="off"){
            jQuery(loader).loader();  
        }

        this.requestCounter++;

        switch(this.type)
        {
            default:
                jQuery.post(this.url,data,function(response){
                    parsed = that.getJSON(response,disableErrors);
                    
                    if(parsed)
                    {
                        if(parsed.success)
                        {
                            jQuery('#MGAlerts').alerts('success',parsed.success);
                        }

                        if(parsed.error)
                        {
                            jQuery('#MGAlerts').alerts('error',parsed.error);
                            jQuery('.modal.in').modal('hide');
                        }
                        
                        if(parsed.data) {
                            callback(parsed.data);
                        } else {
                            callback({});
                        }
                    }
                    else
                    {
                        jQuery('#MGAlerts').alerts('error',"Somethings Goes Wrong, check logs, contact admin");
                        jQuery('.modal.in').modal('hide');
                    }
                }).fail(function(response) {
                    if(response.responseText)
                    {
                        jQuery('#MGAlerts').alerts('error',response.responseText);
                        jQuery('#MGLoader').loader('hide');
                    }
                });
        }
    }
};


function ucfirst(string)
{
    return string.charAt(0).toUpperCase() + string.slice(1);
}

jQuery.fn.alerts = function(type,message,configs){        
    configs = jQuery.extend({
        items: null
        ,confirmCallback: null
        ,link: null
    }, configs);
    
    items           = configs.items;
    confirmCallback = configs.confirmCallback;
    
    var container = this;

    var now = new Date().getTime();
    
    var current = new Array();
    
    var count = 0;
    
    var max = 2;
    
    jQuery(container).children('div[data-time]').each(function(){
        var time = new String(jQuery(this).attr('data-time'));
        current[time] = 1;
        count++;
    });
    
    current.sort();
        
    if(count > max)
    {
        for(x in current)
        {
            var set = parseInt(x);
            if(set > 0)
            {
                if( now-set > 10 && count-max > 0)
                {
                    jQuery(container).find('div[data-time="'+set+'"]').remove();
                    count--;
                }
            }
        }
    }
        
    if(type === 'clear')
    {
        jQuery(container).find('div[data-time]').remove();
        return;
    }

    var prototype = jQuery('#MGAlerts').find('div[data-prototype="'+type+'"]').clone();

    prototype.find('strong').append(message);

    var length = message.length;
    if(items != undefined)
    {
        var html = '<ul>';
        for(x in items)
        {
            html += '<li>'+items[x]+'</li>';
        }
        html += '</ul>';
        prototype.append(html);
        length += html.length;
    }   
    
    prototype.find('.close').click(function(){
       jQuery(this).parent().remove(); 
    });
    
    prototype.attr('data-time',now);
    
    if(configs.link)
    {
        prototype.append('<a href="'+configs.link.url+'">'+configs.link.name+'</a>');
    }
            
    prototype.removeAttr('data-prototype');        
    prototype.show();
            
    jQuery(container).append(prototype);
    
    setTimeout(function() {
        prototype.fadeOut('fast', function() {
            $(this).remove();
        });
    }, 5000 + length * 50);
};

jQuery.fn.loader = function(action)
{
    if(action === undefined || action == 'show')
    {
        jQuery('body').css('position','relative');
        jQuery(this).show();
    }
    else
    {
        jQuery(this).hide();
    }
}

jQuery.fn.MGGetForms = function(action)
{
    var that = this;
    var data = {};
    jQuery(this).find('input,select').each(function(){
        if(!jQuery(this).is(':disabled'))
        {
            var name = jQuery(this).attr('name');
            
            var value = null;
            
            if(name !== undefined)
            {
                var type = jQuery(this).attr('type');

                var regExp = /([a-zA-Z_0-9]+)\[([a-zA-Z_0-9]+)\]/g;
                
                if(type == 'checkbox')
                {
                    var value = [];
                    jQuery(that).find('input[name="'+name+'"]').each(function(){
                        if(jQuery(this).is(':checked'))
                        {
                            value.push(jQuery(this).val());
                        }
                    });
                }
                else if(type == 'radio')
                {
                    if(jQuery(this).is(':checked'))
                    {
                        var value = jQuery(this).val();
                    }
                }
                else
                {
                    var value = jQuery(this).val();
                }
                
                if(value !== null)
                {
                    if(result = regExp.exec(name))
                    {
                        if(data[result[1]] === undefined)
                        {
                            data[result[1]] = {}
                        }
                        
                        data[result[1]][result[2]] = value;
                    }
                    else
                    {
                        data[name] = value;
                    }
                }
            }
        }  
    }); 
    return data;
}

jQuery.fn.MGModalActions = function(){    
                var that = this;
                var rowUpdateFunction;

                this.putField = function(modal,name,value){
                    var element = modal.find('*[name="'+name+'"]');
                    
                    if(element.length > 0){
                        switch(element.prop('tagName').toLowerCase())
                        {
                            case 'input':
                                if(element.attr('type') == 'checkbox')
                                {
                                    if(value == 1)
                                    {
                                        element.attr('checked','checked');
                                    }
                                    else
                                    {
                                        element.removeAttr('checked');
                                    }
                                }
                                else if(element.attr('type') == 'radio')
                                {
                                    element.filter('*[value="'+value+'"]').attr('checked','checked');
                                }
                                else
                                {
                                    element.val(value);
                                }
                            break;
                            case 'select':          
                                element.val(value);
                            break;
                        }
                        
                        element.change();
                    }
                    
                    var element = modal.find('*[name="'+name+'[]"]');

                    if(element.length > 0){
                        switch(element.prop('tagName').toLowerCase())
                        {
                            case 'select':          
                                if(element.attr('multiple'))
                                {
                                    element.find('option').removeAttr('selected');
                                    for(x in value)
                                    {
                                        element.find('option[value="'+value[x]+'"]').attr('selected','selected');
                                    }
                                }
                            break;
                            case 'input':
                                if(element.attr('type') == 'checkbox')
                                {
                                    modal.find('input[type=checkbox][name="'+name+'[]"]').removeAttr('checked');
                                    for(x in value)
                                    {
                                        modal.find('input[type=checkbox][name="'+name+'[]"][value="'+value[x]+'"]').attr('checked','checked');
                                    }
                                }
                            break;
                        }
                        element.change();
                    }
                }
                
                this.addErrorField = function(modal,name,error){
                    var element = modal.find('*[name="'+name+'"]');
                    
                    if(element.length == 0){
                        var element = modal.find('*[name="'+name+'[]"]');
                    }
                    
                    var contener = element.closest('div.form-group');
                    
                    contener.addClass('has-error');
                    
                    contener.find('.error-block').text(error).show();
                }
                
                this.clearModalError = function(modal){
                    modal.find('.form-group.has-error').removeClass('has-error');
                    modal.find('.error-block').text('').hide();
                    modal.find('.modal-alerts').alerts('clear');
                }
                
                this.setRowUpdateFunction = function(updatefunction ){
                    this.rowUpdateFunction = updatefunction;
                }
                
                this.on('click','*[data-modal-id]', function(event){
                    event.preventDefault();
                    
                    var target = jQuery(event.currentTarget).attr('data-modal-target');
                    
                    if(!target)
                    {
                        throw "Define target ID (data-modal-target)";
                    }
                                        
                    var modal = jQuery(event.currentTarget).attr('data-modal-id');
                   
                    if(!modal)
                    {
                        throw "Define modal id (data-modal-id)";
                    }
 
                    var action = jQuery('#'+modal).attr('data-modal-load');
                    
                    var functionName = jQuery('#'+modal).attr('data-modal-on-load');

                    var onload = window[functionName];

                    jQuery('#'+modal).find('[data-target]').val(target);

                    that.clearModalError(jQuery('#'+modal));

                    if(action)
                    {

                        jQuery('#'+modal).find('.modal-body').hide();
                        jQuery('#'+modal).find('.modal-loader').show();

                        JSONParser.request(
                            action
                            , {
                                id: target
                            }
                            , function(data) {
                                
                                if(typeof onload === 'function')
                                {
                                    data = onload(data);
                                }
                                
                                if(data.form)
                                {
                                    for(x in data.form)
                                    {
                                        that.putField(jQuery('#'+modal),x,data.form[x]);
                                    }
                                }

                                if(data.error)
                                {
                                    jQuery('#MGAlerts').alerts('success',data.error);
                                    jQuery('#'+modal).find('*[data-modal-action]').attr('disabled','disabled');
                                }
                                else
                                {
                                    jQuery('#'+modal).find('*[data-modal-action]').removeAttr('disabled');
                                }

                                if(data.vars)
                                {
                                    jQuery('#'+modal).find('*[data-modal-var]').each(function(){
                                        var name = jQuery(this).attr('data-modal-var');
                                        if(data.vars[name])
                                        {
                                            jQuery(this).text(data.vars[name]);
                                        }
                                        else
                                        {
                                            jQuery(this).text();
                                        }
                                    });
                                }

                                jQuery('#'+modal).find('.modal-body').show();
                                jQuery('#'+modal).find('.modal-loader').hide();
                            }
                        );
                    }
                    else
                    {
                        jQuery('#'+modal).find('.modal-body').show();
                        jQuery('#'+modal).find('.modal-loader').hide();
                    }

                    jQuery('#'+modal).modal();
                });
                
                this.updateRow = function(rowData){
                    for(var x in rowData)
                    {
                        if(x == 'DT_RowData')
                        {
                            var selector = 'tr';
                            for(var z in rowData['DT_RowData'])
                            {
                                selector += '[data-'+z+'="'+rowData['DT_RowData'][z]+'"]';
                            }
                            var row = that.find(selector);
                        }
                    }
                    if(row)
                    {
                        for(var x in rowData)
                        {
                            if(x == 'DT_RowClass')
                            {
                                jQuery(row).attr('class','');
                                jQuery(row).addClass(rowData[x]);
                            }
                            else if(x != 'DT_RowData')
                            {
                                jQuery(row).find('td:eq('+x+')').html(rowData[x]);
                            }
                        }
                    }
                    row = null;
                }
                
                this.modalAction = function(action,target,data){
                    if(target)
                    {
                        data['id'] = target;
                    }

                    JSONParser.request(
                        action
                        ,data
                        , function(data) {
                            if(data.saved)
                            {
                                if(typeof that.rowUpdateFunction === 'function')
                                {
                                    data = that.rowUpdateFunction(data);
                                }
                                
                                that.updateRow(data.saved);
                            }

                            if(data.deleted)
                            {
                                if(typeof that.rowUpdateFunction === 'function')
                                {
                                    data.deleted = that.rowUpdateFunction(data.deleted);
                                }
                                
                                for(var x in data.deleted)
                                {
                                    if(x == 'DT_RowData')
                                    {
                                        var selector = 'tr';
                                        for(var z in data.deleted['DT_RowData'])
                                        {
                                            selector += '[data-'+z+'="'+data.deleted['DT_RowData'][z]+'"]';
                                        }
                                        var row = that.find(selector);
                                    }
                                }
                                if(row)
                                {
                                    if(data.deletedRowMessage)
                                    {
                                        row.html('<td colspan="'+row.find('td').length+'">'+data.deletedRowMessage+'</td>');
                                    }
                                    else
                                    {
                                        row.remove();
                                    }
                                }
                                row = null;
                            }
                                                        
                            if(data.modalError || data.modalSuccess) 
                            {
                                if(data.modalError)
                                {
                                    jQuery('.modal.in .modal-alerts').alerts('error',data.modalError);
                                }

                                if(parsed.modalSuccess)
                                {
                                    jQuery('.modal.in .modal-alerts').alerts('success',data.modalSuccess);
                                }
                            }
                            else
                            {
                                jQuery('.modal.in').modal('hide');
                            }
                            
                            if(data.modalFieldsErrors)
                            {
                                for(x in data.modalFieldsErrors)
                                {
                                    if(data.modalFieldsErrors[x])
                                    {
                                        that.addErrorField(jQuery('.modal.in'),x,data.modalFieldsErrors[x]);
                                    }
                                }
                            }
                        }
                    );
                }
                
                this.on('click','*[data-modal-action]', function(event){
                    event.preventDefault();
                    
                    var action = jQuery(this).attr('data-modal-action');

                    var target = jQuery(this).attr('data-modal-target');

                    var data = jQuery(this).closest('.modal').MGGetForms();
                    
                    that.modalAction(action,target,data);
                });
                
                return this;
            }