mgJsComponentHandler.addDefaultComponent('mg-wp-autodetectInstallations', {
    template: '#t-mg-wp-autodetectInstallations',
    props: [
    'component_id',
    'component_namespace',
    'component_index'
    ],
    data: function() {
        return {
            data: {
                installations: [{'url':'asdkhaksdkjhas'}],
                domain: null,
            },
            loading_state:false
        };
    },
    created: function() {
        console.log(this.data.installations[0].url);
        var self = this;
        self.$nextTick(function() {
            self.loadAjaxData();
        });
    },
    targetUrl:null,
    methods: {
        import_all:function(event){

            var self = this;
            self.loading_state = true;
            self.showSpinner(event);
            self.appActionBlockingState = true;
            var formData={'startautodetect':true};
            $.post(self.targetUrl, formData, {}, 'json')
                .done(function( data ) {
                    data = data.data;
                    self.hideSpinner(event);
                    self.$nextTick(function() {
                        if (data.callBackFunction && typeof window[data.callBackFunction] === "function") {
                            window[data.callBackFunction](data, targetId, event);
                        }
                    });
                    if(data.status === 'success'){
                        self.showModal = false;
                        // self.runRefreshActions((data && typeof data.refreshIds !== undefined) ? data.refreshIds : null);
                        // self.cleanMassActions();
                        self.addAlert(data.status, data.message);
                        var installations=data.list[26];
                        var body='';
                        if(installations.length){
                            jQuery.each(installations,function(i,install){
                                console.log(i,install);
                                body+=`<tr>
                                        <td><a href="`+install.url+`">`+install.url+`</a></td>
                                        <td><img src="/modules/addons/WordpressManager/templates/client/default/assets/img/wordpressmanager/message_success.gif">&nbsp;&nbsp;<a href="index.raw?act=wordpress&amp;insid=26_85421" title="Manage your installation with WordPress Manager" class="someclass">
                                            <img src="/modules/addons/WordpressManager/templates/client/default/assets/img/wordpressmanager/wp-gray.png" height="23" width="23"></a>
                                        </td>
                                </tr>`;
                            });
                            jQuery(".installations-table tbody").html(body);
                            jQuery(".installations-table").removeClass("hidden");
                            jQuery("#importsoftware").addClass("hidden");
                        }


                        // this.data.installations.each(function(i,j){
                        //     console.log(i,j);
                        // });
                        // self.updateInstallationData(data.htmlData.data);
                    } else if (data.rawData !== undefined && data.rawData.FormValidationErrors !== undefined) {
                    } else {
                        self.handleErrorMessage(data);
                    }
                    self.appActionBlockingState = false;
                }).fail(function(jqXHR, textStatus, errorThrown) {
                    // self.hideSpinner(event);
                    self.handleServerError(jqXHR, textStatus, errorThrown);
                    self.appActionBlockingState = false;
                });
        },
        refreshUrl : function() {
            var self = this;
            self.targetUrl = mgUrlParser.getCurrentUrl();
            if(self.targetUrl.indexOf('#') > 0) {
                self.targetUrl = self.targetUrl.substr(0, self.targetUrl.indexOf('#'));
            }
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
            return response.done(function(data) {

                if (typeof data.data !== undefined && typeof data.data.errorCode !== 'undefined')
                    {
                        self.handleErrorMessage(data);
                        console.log(typeof data.data,typeof data.data.errorCode,"asd");
                    }else{
                        self.data = data.data.rawData.data;
                        self.buttons=data.data.rawData.buttons;
                        self.$nextTick(function() {
                            //$('#mg-wp-installation-details [data-toggle="lu-tooltip"], [data-tooltip]').luTooltip({});
                        });
                    }
                    self.loading_state = false;
            }).fail(function() {
                self.loading_state = false;
            });
        },
        handleErrorMessage: function(data) {
                    var self = this;
                    self.addAlert(data.status,(data.message ? data.message : data.data.errorMessage));
                    if (typeof data.data !== undefined && typeof data.data.errorCode !== undefined)
                    {
                        console.log(data.data);
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
        handleServerError: function(jqXHR, textStatus, errorThrown) {
                    var self = this;
                    if (jqXHR.responseText.indexOf('id="mg-sh-h-492318-64534" value="') > 0){
                        var errTokenStart = jqXHR.responseText.indexOf('mg-sh-h-492318-64534') + 20;
                        var errTokenEnd = jqXHR.responseText.indexOf('mg-sh-h-492318-64534-end');
                        var errToken = jqXHR.responseText.substr(errTokenStart, (errTokenEnd - errTokenStart));
                        errToken = errToken.replace('value=', '').replace(/\"/g, '').replace(/\s/g, '');
                        self.addAlert('error', 'Unexpected Error. <br>Error Token: ' + errToken);
                        console.log('Action Failed. Error Token: ' + errToken);
                    } else {
                        console.log('Action Failed');
                    }
                },
        loadModal: function(event, targetId, namespace, index) {
            let ind = $(event.target).closest('.lu-widget').index();
            mgPageControler.vueLoader.loadModal(event, targetId,
                typeof namespace !== 'undefined' ? namespace : getItemNamespace(targetId),
                typeof index !== 'undefined' ? index : getItemIndex(targetId), [{
                    name: 'wpid',
                    value: this.data.installations[ind].id
                }]);
        },
        redirect: function(event, targetId,targetBlank) {
            let ind = $(event.target).closest('.lu-widget').index();
            if (targetBlank) {
                window.open(targetId.rawUrl + '&wpid=' + this.data.installations[ind].id, '_blank');
            } else {
                window.location.assign(targetId.rawUrl + '&wpid=' + this.data.installations[ind].id);
            }

        },
        toggleDbDetails:function(targetId){

        },
        saveSiteInfo:function(targetId){
            console.log(targetId)
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
            console.log($(event.target)[0].tagName);
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
        saveSiteConfig:function(targetId,insid, event){

            event.preventDefault();
            var self = this;
            if (self.appActionBlockingState) {
                return true;
            }
            self.appActionBlockingState = true;
            var formTargetId = "saveSiteConfig_"+insid;
            if(formTargetId){
                // self.showSpinner(event);
                var formCont = new mgFormControler(formTargetId);
                var formData = {'formData[wpid]':insid,'formData[value]':$('#'+targetId).val(),'formData[field]':$('#'+targetId).attr("name")};
                formData = self.addMassActionsToData(formData);
                self.refreshUrl();

                self.addUrlComponent('loadData', formTargetId);
                self.addUrlComponent('namespace', getItemNamespace(formTargetId));
                self.addUrlComponent('index', getItemIndex(formTargetId));
                self.addUrlComponent('ajax', '1');
                self.addUrlComponent('mgformtype', "saveSiteConfig");
                $.post(self.targetUrl, formData, {}, 'json')
                .done(function( data ) {
                    data = data.data;
                    // self.hideSpinner(event);
                    self.$nextTick(function() {
                        if (data.callBackFunction && typeof window[data.callBackFunction] === "function") {
                            window[data.callBackFunction](data, targetId, event);
                        }
                    });
                    if(data.status === 'success'){
                        self.showModal = false;
                        // self.runRefreshActions((data && typeof data.refreshIds !== undefined) ? data.refreshIds : null);
                        // self.cleanMassActions();
                        self.addAlert(data.status, data.message);
                        formCont.updateFieldsValidationMessages([]);
                        // self.updateInstallationData(data.htmlData.data);
                    } else if (data.rawData !== undefined && data.rawData.FormValidationErrors !== undefined) {
                        formCont.updateFieldsValidationMessages(data.rawData.FormValidationErrors);
                    } else {
                        formCont.updateFieldsValidationMessages([]);
                        self.handleErrorMessage(data);
                    }
                    self.appActionBlockingState = false;
                }).fail(function(jqXHR, textStatus, errorThrown) {
                    // self.hideSpinner(event);
                    self.handleServerError(jqXHR, textStatus, errorThrown);
                    self.appActionBlockingState = false;
                });
            }else {
                    }

        },
        saveSiteInfo:function(targetId, event){
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
                $.post(self.targetUrl, formData, {}, 'json')
                .done(function( data ) {
                    data = data.data;
                    self.hideSpinner(event);
                    self.$nextTick(function() {
                        if (data.callBackFunction && typeof window[data.callBackFunction] === "function") {
                            window[data.callBackFunction](data, targetId, event);
                        }
                    });
                    if(data.status === 'success'){
                        self.showModal = false;
                        // self.runRefreshActions((data && typeof data.refreshIds !== undefined) ? data.refreshIds : null);
                        // self.cleanMassActions();
                        self.addAlert(data.status, data.message);
                        formCont.updateFieldsValidationMessages([]);
                        self.updateInstallationData(data.htmlData.data);
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
            }else {
                    }

        },
        updateInstallationData:function(data){
            // let index = this.data.installations.findIndex( inst => inst.id == data.insid);
            // this.data.installations[index].site_name=data.site_name;
            // this.data.installations[index].url=data.url;
        },
        submitForm : function(targetId, event) {
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
                $.post(self.targetUrl, formData, {}, 'json')
                .done(function( data ) {
                    data = data.data;
                    self.hideSpinner(event);
                    self.$nextTick(function() {
                        if (data.callBackFunction && typeof window[data.callBackFunction] === "function") {
                            window[data.callBackFunction](data, targetId, event);
                        }
                    });
                    if(data.status === 'success'){
                        self.showModal = false;
                        // self.runRefreshActions((data && typeof data.refreshIds !== undefined) ? data.refreshIds : null);
                        // self.cleanMassActions();
                        self.addAlert(data.status, data.message);
                        formCont.updateFieldsValidationMessages([]);
                        self.loadAjaxData();
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
            }else {
                    }
        },
        addUrlComponent : function($name, $value) {
            var self = this;
            self.targetUrl += (self.targetUrl.indexOf('?') !== -1 ? '&' : '?') + $name + '=' + encodeURIComponent($value);
        },
            }
        });
