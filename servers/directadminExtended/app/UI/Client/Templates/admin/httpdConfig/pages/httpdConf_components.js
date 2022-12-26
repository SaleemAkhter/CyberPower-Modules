mgJsComponentHandler.addDefaultComponent('mg-fileEdit', {
    template : '#t-mg-fileEdit',
    props: [
    'component_id',
    'component_namespace',
    'component_index',
    'filedata',
    'apidata',
    'templates'
    ],
    data : function() {
        return {
            data: {
                file:'',
                FILEDATA:''

            },
            loading : false,
            loading_state: false,
            authanticated:false,
            rootpass:'',
            require_auth:false,
            theme:'Default',
            mode:'plain/text',
            editor:'',
            templatelist:''

            // filedata:''
        };
    },
    created: function () {
        var self = this;
        self.$nextTick(function() {
            self.loadAjaxData();
        });

    },
    mounted:function(){
        var self = this;
        jQuery('input').on('ifChecked', function(e){
            if(!$(this).val().length){
                self.data[$(this).attr('name')]="on";
            }else{
                self.data[$(this).attr('name')]=$(this).val();
            }

        });
        jQuery('input').on('ifUnchecked', function(e){
            self.data[$(this).attr('name')]="";
        });

    },
    methods: {
        hasField:function(field){
            var self=this;
               return self.data.optionsactivefields.find(x => x === field);
        },
        checkallusers:function(event){
             var self=this;
        },
        isUserChecked:function(user){
            var self=this;
        },
        loadModal: function(event, targetId, namespace, index, params, addSpinner){
            mgPageControler.vueLoader.loadModal(event, targetId,
                typeof namespace !== 'undefined' ? namespace : getItemNamespace(targetId),
                typeof index !== 'undefined' ? index : getItemIndex(targetId), params, addSpinner);
        },
        ajaxAction: function(event, targetId, namespace, index, params, addSpinner){
            mgPageControler.vueLoader.ajaxAction(event, targetId,
                typeof namespace !== 'undefined' ? namespace : getItemNamespace(targetId),
                typeof index !== 'undefined' ? index : getItemIndex(targetId), params, addSpinner);
        },
        selectChangeAction:function(value,v){

        },
        initOptionsSelect: function() {
            var self = this;
            var component_id="mode";
            if ($('#' + component_id).hasClass('selectized')) {
                var selectizedInstance = $('#' + component_id).selectize();
                selectizedInstance[0].selectize.destroy();
            }

            $('#' + component_id).on('change', function(value,v){
                self.selectChangeAction(value,self.mode);
                editor.setOption("mode", self.mode);
            });
            var component_id="theme";
            if ($('#' + component_id).hasClass('selectized')) {
                var selectizedInstance = $('#' + component_id).selectize();
                selectizedInstance[0].selectize.destroy();
            }
            $('#' + component_id).on('change', function(value,v){
                self.selectChangeAction(value,self.theme);
                editor.setOption("theme", self.theme);
            });
            setTimeout(() => {
                $('input').iCheck('update');
            }, "800");
        },
        loadAjaxData: function() {
            var self = this;
            self.loading_state = true;

            var requestParams = {
                loadData: self.component_id,
                namespace: self.component_namespace,
                index: self.component_index
            };
            self.editor= editor=CodeMirror(document.querySelector('#filedata'), {
                  lineNumbers: true,
                  tabSize: 2,
                  mode:self.mode,
                  value:self.filedata
                });

            var response = mgPageControler.vueLoader.vloadData(requestParams);
            return response.done(function(data){
                self.data = data.data.rawData.data;

                self.dataResellerRows=self.data.data_list;
                if(self.data.REQUIRE_ROOT_AUTH=="yes"){
                    self.require_auth=true;
                }
                self.initOptionsSelect();
                self.loading_state = false;
                self.templatelist=self.data.templates;
            }).fail(function(){
                self.loading_state = false;
            });

        },
        selectUser:function (user,reseller){
            if(jQuery('[parentreseller="'+reseller+'"]').not(":checked").length==0){
                jQuery('input[value="'+reseller+'"]').prop('checked','checked');
            }else{
                jQuery('input[value="'+reseller+'"]').prop('checked','');
            }
            //
        },
        changeReseller:function(){
            var self=this;
            jQuery('[parentreseller="'+self.selectedreseller+'"]:checked').each(function(){
                jQuery(this).prop('checked','');
                var u=jQuery(this).val();
                arr = self.children.filter(e => e !== u);
                self.children=arr;

            });
        },
        selectResellerUsers:function(reseller){
            var self=this;
            if(jQuery("input[value='"+reseller+"']").is(":checked")){
                jQuery('[parentreseller="'+reseller+'"]').each(function(){
                jQuery(this).prop('checked','checked');
            });
            }else{
                jQuery('[parentreseller="'+reseller+'"]').each(function(){
                    jQuery(this).prop('checked','');
                });
            }
        },
        changeWildcard:function(){
            var self=this;
            self.data.wildcard=!self.data.wildcard;
            if(self.data.wildcard){
                self.subdomains=self.data.wildcard_subdomains;
            }
        },
        showDnsProviderFields:function (){
            jQuery("#dnsModal").modal("show");
        },
        hideSpinner : function(event) {
            var self = this;
            var spinnerTarget = self.getSpinerTarget(event);
            if ($(event.target).attr('originall-button-content')) {
                self.removeSpinner(event);
            } else if (spinnerTarget.length > 0 || $(spinnerTarget).tagName === 'I') {
                $(spinnerTarget).removeAttr('class');
                $(spinnerTarget).attr('class', $(spinnerTarget).attr('originall-button-icon'));
                $(spinnerTarget).removeAttr('originall-button-icon');
            }
        },
        removeSpinner : function(event) {
            $(event.target).html($(event.target).attr('originall-button-content'));
            $(event.target).removeAttr('originall-button-content');
        },
        addSpinner : function(event) {
            var elWidth = $(event.target).width();
            // console.log($(event.target).html('<span class="lu-btn__icon lu-temp-button-loader" style="margin: 0 0 0 0 !important; width: ' + elWidth + 'px;"><i class="lu-btn__icon lu-preloader lu-preloader--sm ' + spinnerClass + '"></i></span>'));
            var spinnerClass = $(event.target).hasClass('lu-btn--success') ? 'lu-preloader-success' : ($(event.target).hasClass('lu-btn--danger') ? 'lu-preloader-danger' : '');
            $(event.target).attr('originall-button-content', $(event.target).html());
            $(event.target).html('<span class="lu-btn__icon lu-temp-button-loader" style="margin: 0 0 0 0 !important; width: ' + elWidth + 'px;"><i class="lu-btn__icon lu-preloader lu-preloader--sm ' + spinnerClass + '"></i></span>');
        },
        showSpinner : function(event) {
            var self = this;
            var spinnerTarget = self.getSpinerTarget(event);

            if (spinnerTarget.length > 0 || $(spinnerTarget).tagName === 'I') {
                var isBtnIcon = $(spinnerTarget).hasClass('lu-btn__icon');
                $(spinnerTarget).attr('originall-button-icon', $(spinnerTarget).attr('class'));
                $(spinnerTarget).removeAttr('class');
                $(spinnerTarget).attr('class', (isBtnIcon ? 'lu-btn__icon ' : '') + 'lu-btn__icon lu-preloader lu-preloader--sm');
            } else {
                self.addSpinner(event);
            }
        },
        getSpinerTarget : function(event) {
            if($(event.target)[0].tagName === 'I'){
                return $(event.target);
            } else if($(event.target)[0].tagName === 'SPAN'){
                var aParents = $(event.target).parents('a');
                return aParents.length === 0 ? $(event.target).parents('button').first().find('i').first() : $(event.target).parents('a').first().find('i').first();
            } else if($(event.target)[0].tagName === 'A'){
                return $(event.target).find('i').first();
            } else if($(event.target)[0].tagName === 'BUTTON'){
                return $(event.target).find('i').first();
            } else {
                return null;
            }
        },
        addMassActionsToData : function (formData){
            var self = this;
            if(self.massActionIds){
                formData.massActions = self.massActionIds;
                return formData;
            }else{
                return formData;
            }
        },
        refreshUrl : function() {
            var self = this;
            self.targetUrl = mgUrlParser.getCurrentUrl();
            if(self.targetUrl.indexOf('#') > 0) {
                self.targetUrl = self.targetUrl.substr(0, self.targetUrl.indexOf('#'));
            }
        },
        addUrlComponent : function($name, $value) {
            var self = this;
            self.targetUrl += (self.targetUrl.indexOf('?') !== -1 ? '&' : '?') + $name + '=' + encodeURIComponent($value);
        },
        pause:async function(ms){
            await sleep(ms);
        },
        showtemplatedata:function(targetId,event,data){
            event.preventDefault();
            var self = this;
            if (self.appActionBlockingState) {
                return true;
            }
            self.appActionBlockingState = true;
            self.showSpinner(event);
            self.loadModal(event,targetId,'ModulesGarden_Servers_DirectAdminExtended_App_UI_Client_Admin_HttpdConfig_Buttons_Detail','loadProgressModal',[{'name':'modalHtml','value':data.name_short}],false);
            self.pause(10000);

            setTimeout(() => {
                jQuery('#detailForm').closest("#mgModalContainer").find('.lu-modal__top span').text(data.name_short);
                jQuery('#detailForm').html('<div id="conftemplatedata"></div>');
                self.templateeditor= editor=CodeMirror(document.querySelector('#conftemplatedata'), {
                  lineNumbers: true,
                  tabSize: 2,
                  mode:self.mode,
                  value:data.data
                });
                self.hideSpinner(event);
            }, "2000")

            self.appActionBlockingState = false;
        },
        showtokens:function(targetId,event){
            event.preventDefault();
            var self = this;
            if (self.appActionBlockingState) {
                return true;
            }
            self.appActionBlockingState = true;
            self.showSpinner(event);
            self.loadModal(event,targetId,'ModulesGarden_Servers_DirectAdminExtended_App_UI_Client_Admin_HttpdConfig_Buttons_Tokens','loadProgressModal','',false);
            setTimeout(() => { self.hideSpinner(event); }, 4000);

            self.appActionBlockingState = false;
        },
        addAlert : function(type, message){
            type = (type === 'error') ? 'danger' : type;
            layers.alert.create({
                $alertPosition: 'right-top',
                $alertStatus: type,
                $alertBody: message,
                $alertTimeout: 10000
            });
        },
        handleErrorMessage: function(data) {
            var self = this;
            self.addAlert(data.status,(data.message ? data.message :
                    (data.data.errorCode ? 'Error Code: ' + data.data.errorCode + ' <br> ' : '')
                    + (data.data.errorToken ? 'Error Token: ' + data.data.errorToken + ' <br> ' : '')
                    + (data.data.errorTime ? 'Error Time: ' + data.data.errorTime + ' <br> ' : '')
                    + (data.data.errorMessage ? 'Message: ' + data.data.errorMessage : '')
            ));
            if (typeof data.data !== undefined && typeof data.data.errorCode !== undefined)
            {
                console.log(data.data);
            }
        },
    },
});
