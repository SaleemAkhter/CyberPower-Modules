mgJsComponentHandler.addDefaultComponent('mg-backup', {
    template : '#t-mg-backup',
    props: [
    'component_id',
    'component_namespace',
    'component_index'
    ],
    data : function() {
        return {
            data: {
                who:'all',
                selectedDomains:[],
                comment:'',
                fingerprint:'',
                size:'',
                optionsactivefields:[],
                optionsfields:[
                        {'one':'fieldone'}
                ],
                subdomains:[],
                wildcard_subdomains:[],
                applyto:'',
                dataoptions:[],
                selecteddataoptions:[],
                append_to_path:[],
                settingroups:[],
                append_to_pathoption:''
            },
            optionsfields:[],
            optionsactivefields:[],
            selectedDomains:[],
            selectedreseller:[],
            dataResellerRows:[],
            selecteddataoptions:[],
            children: [],
            loading : false,
            loading_state: false,
            step:1,
            settingroups:[]
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
            var attr = $(this).attr('data-check-all');
            if (typeof attr !== 'undefined' && attr !== false) {
                jQuery(this).closest('table').find('tbody .table-mass-action-check').each(function(){
                    jQuery(this).prop("checked","checked");
                    if(jQuery(this).hasClass('domain-check')){
                        self.data.selectedDomains.push(jQuery(this).attr("value"));
                    }

                });
            }
        });
        jQuery('input').on('ifUnchecked', function(e){
            self.data[$(this).attr('name')]="";
            var attr = $(this).attr('data-check-all');
            if (typeof attr !== 'undefined' && attr !== false) {
                jQuery(this).closest('table').find('tbody .table-mass-action-check').each(function(){
                    jQuery(this).prop("checked","");
                    if(jQuery(this).hasClass('domain-check')){
                        var u=jQuery(this).val();
                        arr = self.data.selectedDomains.filter(e => e !== u);
                        self.data.selectedDomains=arr;
                    }
                });


            }
        });
    },
    methods: {
        hasField:function(field){
            var self=this;
               return self.data.optionsactivefields.find(x => x === field);
        },
        checkallusers:function(event){
             var self=this;
             console.log(jQuery('[data="data-check-all"]').is(":checked"));
        },
        isUserChecked:function(user){
            var self=this;
            // selectedusers
        },
        selectChangeAction:function(value,v){

            var self=this;
            var selectedOpt=jQuery("#optionsfields").val();
            self.data.optionsactivefields.push(jQuery("#optionsfields").val());
            var opts=self.data.options;
            delete self.data.options[selectedOpt];
        },
        initOptionsSelect: function() {
            var self = this;
            var component_id="optionsfields";
            if ($('#' + component_id).hasClass('selectized')) {
                var selectizedInstance = $('#' + component_id).selectize();
                selectizedInstance[0].selectize.destroy();
            }

            $('#' + component_id).on('change', function(value,v){
                self.selectChangeAction(value,v);
            });
            var append_to_path="append_to_path";
            if ($('#' + append_to_path).hasClass('selectized')) {
                var selectizedInstance = $('#' + append_to_path).selectize();
                selectizedInstance[0].selectize.destroy();
            }
            setTimeout(() => {
              $('input').iCheck('update');


            }, "800")
            // name="applyto"
        },
        loadAjaxData: function() {
            var self = this;
            self.loading_state = true;

            var requestParams = {
                loadData: self.component_id,
                namespace: self.component_namespace,
                index: self.component_index
            };

            var response = mgPageControler.vueLoader.vloadData(requestParams);
            return response.done(function(data){
                self.data = data.data.rawData.data;

                self.children=self.data.selecteddataoptions;

                self.initOptionsSelect();
                self.loading_state = false;
            }).fail(function(){
             self.loading_state = false;
         });
        },
        selectSetting:function (setting,$event){
            var self=this;

            var group=jQuery($event.target).attr('parentgroup');

            if(jQuery('[parentgroup="'+group+'"]:checked').length==Object.keys(self.data.settings[group].children).length){
                jQuery('input[value="'+group+'"]').prop('checked','checked');
            }else{
                jQuery('input[value="'+group+'"]').prop('checked','');
            }
        },
        changeReseller:function(){
            var self=this;
            jQuery('[parentgroup="'+self.selectedreseller+'"]:checked').each(function(){
                jQuery(this).prop('checked','');
                var u=jQuery(this).val();
                arr = self.children.filter(e => e !== u);
                self.children=arr;

            });
        },
        selectSettingGroup:function(group,$event){
            var self=this;
            if(jQuery($event.target).is(":checked")){
                jQuery('[parentgroup="'+group+'"]').each(function(){
                jQuery(this).prop('checked','checked');
                self.children.push(jQuery(this).attr("value"));
            });
            }else{
                jQuery('[parentgroup="'+group+'"]').each(function(){
                    jQuery(this).prop('checked','');
                    var u=jQuery(this).val();
                    arr = self.children.filter(e => e !== u);
                    self.children=arr;
                });
            }
        },
        selectResellerUsers:function(reseller){
            var self=this;
            if(jQuery("input[value='"+reseller+"']").is(":checked")){
                jQuery('[parentgroup="'+reseller+'"]').each(function(){
                jQuery(this).prop('checked','checked');
            });
            }else{
                jQuery('[parentgroup="'+reseller+'"]').each(function(){
                    jQuery(this).prop('checked','');
                });
            }

            // self.children=[];
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


        createBackup:function(targetId,event){
            event.preventDefault();
            var self = this;
            if (self.appActionBlockingState) {
                return true;
            }
            self.appActionBlockingState = true;
            var formTargetId = ($('#'+targetId)[0].tagName === 'FORM') ? targetId : $('#'+targetId).find('form').attr('id');
            if(formTargetId){
                self.showSpinner(event);
                var formCont = new mgFormControler(formTargetId);
                var formData = formCont.getFieldsData();
                formData = self.addMassActionsToData(formData);

                self.refreshUrl();
                self.addUrlComponent('loadData', formTargetId);
                self.addUrlComponent('namespace', getItemNamespace(formTargetId));
                self.addUrlComponent('index', getItemIndex(formTargetId));
                self.addUrlComponent('ajax', '1');
                self.addUrlComponent('mgformtype', $('#'+formTargetId).attr('mgformtype'));
                $.ajax({
                    type: "POST",
                    url: self.targetUrl+"&sub=1",
                    data: formData,
                    processData: false,
                    contentType: false})
                .done(function( data ) {
                    data = data.data;
                    self.hideSpinner(event);
                    self.$nextTick(function() {
                        if(formTargetId=="mxRecordsSettingsForm"){
                            self.$root.$emit('reloadMgData','dnsRecordsTable');
                        }

                        if (data.callBackFunction && typeof window[data.callBackFunction] === "function") {
                            window[data.callBackFunction](data, targetId, event);
                        }
                    });
                    if(data.status === 'success'){
                        self.showModal = false;
                                // self.runRefreshActions((data && typeof data.refreshIds !== undefined) ? data.refreshIds : null, data);
                                // self.cleanMassActions();
                                self.addAlert(data.status, data.message);
                                formCont.updateFieldsValidationMessages([]);
                            } else if (data.rawData !== undefined && data.rawData.FormValidationErrors !== undefined) {
                                formCont.updateFieldsValidationMessages(data.rawData.FormValidationErrors);
                            } else {
                                formCont.updateFieldsValidationMessages([]);
                                self.handleErrorMessage(data);
                            }
                            self.appActionBlockingState = false;
                        }).fail(function(jqXHR, textStatus, errorThrown) {
                            self.hideSpinner(event);
                            self.handleServerError(jqXHR, textStatus, errorThrown);
                            self.appActionBlockingState = false;
                        });
                    }
                    else {
                        //todo error reporting
                    }
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
