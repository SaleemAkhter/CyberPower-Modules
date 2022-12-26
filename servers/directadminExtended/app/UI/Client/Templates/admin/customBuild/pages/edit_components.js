mgJsComponentHandler.addDefaultComponent('mg-edit', {
    template : '#t-mg-edit',
    props: [
    'component_id',
    'component_namespace',
    'component_index'
    ],
    data : function() {
        return {
            dataRows: [],
            length: 25,
            iSortCol_0 : '',
            sSortDir_0 : '',
            addTimeout : false,
            sSearch : false,
            sSearchPrevious : false,
            dataShowing : 0,
            dataTo : 0,
            dataFrom : 0,
            curPage : 1,
            allPages : 1,
            pagesMap : [],
            loading : false,
            show : true,
            showModal : false,
            noData : false,
            onOffSwitchEnabled : false,
            settings: [],
            step:3,
            directory:'',
            pid:'',
            eventSource:false,
            updateEvent:false,
            currentSettings:[],
            group:[]
        };
    },
    created: function () {
        var self = this;
        self.addTimeout = true;
        self.updateProjects();
        self.$parent.$root.$on('reloadMgData', this.updateMgData);
    },
    updated: function (){
        var self = this;
        $('#' + self.component_id ).luCheckAll({
            onCheck: function(container, counter) {
                console.log("asdasldlas");
                var massActions = container.find('.lu-t-c__mass-actions');
                massActions.find('.lu-value').html(counter);
                if (counter > 0) {
                    if(counter==1){
                        container.find('.hide-on-multiple').removeClass("disabled");
                    }else{
                        container.find('.hide-on-multiple').addClass("disabled");
                    }
                    massActions.addClass('is-open');
                } else {
                    massActions.removeClass('is-open');
                }

            }
        });
        initTooltipsForDatatables(self.component_id);
    },
    methods: {

      makeDescription:function($text, $popover = false)
      {
        $text = $text.replace(
            '@(https?://([-\w\.]+[-\w])+(:\d+)?(/([\w/_\.#-]*(\?\S+)?[^\.\s])?)?)@',
            "<a href='$1' target='_blank'>$1</a>"
            );
        $warning = $text.indexOf( 'WARNING:');
        if ($warning !== false) {
            if($popover) {
                $text = $text.replace('WARNING:', '<span class="badge btn-danger" data-content="WARNING:') + ">!</span>";
            } else {
                $text = $text.replace('WARNING:', "<strong class='text-danger bold'>WARNING:") + '</strong>';
            }
        }

        return $text;
    },
    getValidationMessage:function($key, $button){

    },

    updateMgData: function(toReloadId){
        var self = this;
        if(self.component_id === toReloadId){
            self.updateProjects();
            self.$nextTick(function() {
                self.$emit('restartRefreshingState');
            });
        }
    },
    updateProjects: function(){
        var self = this;
        self.loading = true;
        var resp = self.$parent.$root.$options.methods.vloadData({loadData : self.component_id, namespace : self.component_namespace, index: self.component_index, iDisplayLength : self.length, iDisplayStart : self.dataShowing, sSearch : (self.sSearch !== false ? self.sSearch : ''), iSortCol_0 : self.iSortCol_0, sSortDir_0 : self.sSortDir_0});
        resp.done(function(data){
            if (data.data.status === 'error') {
                self.noData = true;
                mgPageControler.vueLoader.handleErrorMessage(data.data);
            } else {
                var actionData = data.data.rawData;
                data = data.data.rawData.recordsSet;
                self.settings=actionData.data;

                Object.entries(self.settings).forEach(function(value,key ){
                    var group=[];
                    Object.entries(value[1].opts).forEach(function(v,k){
                        if(v[0] != 'description') {
                            if(v[1].values.length > 0) {
                                group[v[0]] = v[1].current ?? v[1].default;
                            }
                        }
                    });
                    self.currentSettings[value[0]] = group;
                } );
            }
            self.loading = false;
        }).fail(function(jqXHR, textStatus, errorThrown) {
            self.loading = false;
            self.dataRows = [];
            self.noData = true;
            mgEventHandler.runCallback('DatatableDataLoadingFailed', self.component_id, {jqXHR: jqXHR, textStatus: textStatus, errorThrown: errorThrown});
            mgPageControler.vueLoader.handleServerError(jqXHR, textStatus, errorThrown);
        });
        uncheckSelectAllCheck(self.tableWrapperId);
        jQuery(document).on("click",".killprocessbtn",function(){
            self.eventSource.close();
            self.updateProjects();
            self.hideSpinner(self.updateEvent);
            jQuery(".killprocessbtn").addClass("hidden");
            self.appActionBlockingState=false;
        });
    },
    runCalbacks: function(data) {
        var self = this;
        if (typeof data.recordsSet !== 'undefined' && typeof data.recordsSet.records !== 'undefined' && data.recordsSet.records.length > 0)
        {
            $('#loadedTemplates').append(data.template);
            self.$nextTick(function() {
                for (var key in data.registrations) {
                    mgJsComponentHandler.extendRegisterByDefaultTemplate(key.toLowerCase(), data.registrations[key]);
                }
            });
        }
    },
    redirectToUrl: function(targetUrl, event) {
        window.location.href = targetUrl;
    },
    redirectToEditUrl:function(targetUrl, event){
        var actionid= jQuery(event.target).closest('tr').attr("actionid");
        window.location.href = targetUrl+"&actionElementId="+actionid;

    },
    updateLength: function(event){
        var self = this;
        var btnTarget = (typeof $(event.target).attr('data-length') === 'undefined') ? $(event.target).parent() : $(event.target);
        self.length = $(btnTarget).attr('data-length');
        self.dataShowing = 0;
        $(btnTarget).parent().children('.active').removeClass('active');
        $(btnTarget).addClass('active');
        self.updateProjects();
    },
    updateSorting: function(event){
        var self = this;
        var sortTarget = $(event.target)[0].tagName === 'TH' ? $(event.target) : $(event.target).parents('th').first();
        self.iSortCol_0 = $(sortTarget).attr('name');
        self.dataShowing = 0;
        var currentDir = self.getSortDir($(sortTarget), true);
        $(event.target).parents('tr').first().children('.sorting_asc, .sorting_desc').addClass('sorting').removeClass('sorting_asc').removeClass('sorting_desc');
        $(sortTarget).removeClass('sorting').removeClass('sorting_asc').removeClass('sorting_desc').addClass(self.reverseSort(currentDir));
        self.sSortDir_0 = self.getSortDir($(sortTarget), false);
        self.updateProjects();
    },
    reverseSort: function(sort){
        var sortingType = 'sorting_asc';
        if(sort === 'sorting_asc'){
            sortingType = 'sorting_desc';
        }
        return sortingType;
    },
    getSortDir: function(elem, rawClass){
        var sorts = ['sorting_asc', 'sorting_desc', 'sorting'];
        var sorting = '';
        $.each(sorts, function(key, sort) {
            if($(elem).hasClass(sort) === true) {
                sorting = rawClass ? sort : sort.replace('sorting_', '').replace('sorting', '');
                return sorting;
            }
        });
        return sorting;
    },
    searchData: function(event){
        var self = this;
        self.sSearch = $(event.target).val() === '' ? false : $(event.target).val();
        if (self.sSearch !== false) {
            if (self.sSearchPrevious !== false && self.sSearchPrevious !== self.sSearch) {
                self.dataShowing = 0;
            }
            self.sSearchPrevious = self.sSearch;
        } else {
            self.sSearchPrevious = false;
        }
        self.updateProjects();
    },
    updatePagination: function(){
        var self = this;
        self.curPage = (parseInt(self.dataShowing) / parseInt(self.length)) + 1;
        var tempPages = parseInt(parseInt(self.dataFrom) / parseInt(self.length));
        self.allPages = parseInt(tempPages + ((parseInt(self.length) * tempPages) < parseInt(self.dataFrom) ? 1 : 0));
        self.updatePagesMap();
    },
    updatePagesMap: function(){
        var self = this;
        if (self.allPages === 1) {
            self.pagesMap = [1];
            return;
        }
        self.pagesMap = [
        self.curPage -5,
        self.curPage -4,
        self.curPage -3,
        self.curPage -2,
        self.curPage -1,
        self.curPage,
        self.curPage +1,
        self.curPage +2,
        self.curPage +3,
        self.curPage +4,
        self.curPage +5
        ];
        for(i=0; i< self.pagesMap.length; i++){
            if(self.pagesMap[i] < 0 || self.pagesMap[i] > self.allPages){
                self.pagesMap[i] = null;
            }
        }

        if(self.pagesMap.indexOf(self.allPages) === -1) {
            self.pagesMap[self.pagesMap.length-1] = self.allPages;
        }
        if(self.pagesMap.indexOf(1) === -1) {
            self.pagesMap[0] = 1;
        }

        if(self.allPages > 7 && self.curPage > 4) {
            self.pagesMap[self.pagesMap.indexOf(Math.min(
                self.curPage-1 > 1 ? self.curPage-1 : self.curPage,
                self.curPage-2 > 1 ? self.curPage-2 : self.curPage,
                self.curPage-3 > 1 ? self.curPage-3 : self.curPage,
                self.curPage-4 > 1 ? self.curPage-4 : self.curPage
                ))] = '...';
        }

        for(i=self.pagesMap.length-1; i > self.pagesMap.indexOf(self.curPage); i--){
            if(i === 8 && self.pagesMap[i] === self.curPage + 3 && self.pagesMap[i] !== self.allPages) {
                self.pagesMap[i] = null;
            }
        }
        if(self.allPages > 7 && (4 <= self.allPages - self.curPage)) {
            self.pagesMap[self.pagesMap.indexOf(self.allPages) - 1] = '...';
        }
    },
    changePage: function(event) {
        var self = this;
        if($(event.target).parent().hasClass('disabled') === false && $(event.target).hasClass('disabled') === false){
            var newPageNumber = $(event.target).attr('data-dt-idx');
            self.dataShowing = ((newPageNumber < 1) ? 0 : newPageNumber -1) * parseInt(self.length);
            self.updateProjects();
        }
    },
    rowDrow : function(name, DataRow, customFunctionName) {

        if(window[customFunctionName] === undefined) {
            return DataRow[name];
        } else {
            return window[customFunctionName](name, DataRow);
        }
    },
    loadModal: function(event, targetId, namespace, index, params, addSpinner){
        console.log(targetId);
        mgPageControler.vueLoader.loadModal(event, targetId,
            typeof namespace !== 'undefined' ? namespace : getItemNamespace(targetId),
            typeof index !== 'undefined' ? index : getItemIndex(targetId), params, addSpinner);
    },
    ajaxAction: function(event, targetId, namespace, index, params, addSpinner){
        console.log("AJAX ACTION",self.eventSource);
        mgPageControler.vueLoader.ajaxAction(event, targetId,
            typeof namespace !== 'undefined' ? namespace : getItemNamespace(targetId),
            typeof index !== 'undefined' ? index : getItemIndex(targetId), params, addSpinner);
    },
    onOffSwitch: function(event, targetId){
        var switchPostData = $(event.target).is(':checked') ? {'value' : 'on'} : {'value' : 'off'};
        mgPageControler.vueLoader.ajaxAction(event, targetId, getItemNamespace(targetId), getItemIndex(targetId), switchPostData);
    },
    redirect :  function (event, params, targetBlank) {
        mgPageControler.vueLoader.redirect(event, params, targetBlank);
    },
    makeCustomAction :  function (functionName, params, event, namespace, index) {
        mgPageControler.vueLoader.makeCustomAction(functionName, params, event, namespace, index);
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
    commit:function(type,data){
        if(type=="SET_UPDATE_LOG"){
            jQuery('#progressForm').html(data);
            var d = $('#progressForm');
            d.scrollTop(d.prop("scrollHeight"));
        }
        if(type=="SET_UPDATE_PID"){
            self.pid=data;
            jQuery(".killprocessbtn").removeClass("hidden");
        }
    },
    pause:async function(ms){
        await sleep(ms);
    },
    updateBuild:function(targetId,event){
            event.preventDefault();
            var self = this;
            if (self.appActionBlockingState) {
                return true;
            }
            self.appActionBlockingState = true;
            var formTargetId = ($('#'+targetId)[0].tagName === 'FORM') ? targetId : $('#'+targetId).find('form').attr('id');
            if(formTargetId){
                self.loadModal(event,targetId,'ModulesGarden_Servers_DirectAdminExtended_App_UI_Client_Admin_CustomBuild_Buttons_Progress','loadProgressModal',{'loadProgressModal':true},false);
                self.pause(1000);

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
                var software=jQuery("#"+formTargetId).find('input[name="software"]').val();;
                self.addUrlComponent('software', software);

                const source = new EventSource(self.targetUrl,{ withCredentials: true });



                const closeSource = () => {
                    // commit('SET_UPDATE_STATUS', false);
                    // commit('SET_UPDATE_PID', 0);
                    source.close();
                    // if (onDone) {
                    //     dispatch(onDone);
                    // }
                    // resolve(state.updateCommandData.log);
                };
                self.eventSource=source;

                self.updateEvent=event;
                source.addEventListener(
                    'message',
                    (e) => {
                        const { data, finished, pid } = JSON.parse(e.data);
                        if (finished) {
                            closeSource();
                            self.hideSpinner(event);
                            jQuery(".killprocessbtn").addClass("hidden");
                            self.appActionBlockingState=false;
                        }
                        if (pid && pid !== '0') {
                            self.commit('SET_UPDATE_PID', Number(pid));
                        }
                        if (data) {
                            self.commit('SET_UPDATE_LOG', data);
                        }
                    },
                    false
                );

                source.addEventListener(
                    'error',
                    (e) => {
                        if (e.readyState === EventSource.CLOSED) {
                            // commit('SET_UPDATE_STATUS', false);
                            // commit('SET_UPDATE_PID', 0);
                            // reject(new Error('Connection was closed'));
                        } else {
                            // reject(new Error('EventSource Error'));
                        }
                    },
                    false
                );

                    }
                    else {
                        //todo error reporting
                    }
                },
    update:function(targetId,event){
            event.preventDefault();
            var self = this;
            if (self.appActionBlockingState) {
                return true;
            }
            self.appActionBlockingState = true;
            var formTargetId = ($('#'+targetId)[0].tagName === 'FORM') ? targetId : $('#'+targetId).find('form').attr('id');
            if(formTargetId){
                self.loadModal(event,targetId,'ModulesGarden_Servers_DirectAdminExtended_App_UI_Client_Admin_CustomBuild_Buttons_Progress','loadProgressModal',{'loadProgressModal':true},false);
                self.pause(1000);

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
                var software=jQuery("#"+formTargetId).find('input[name="software"]').val();;
                self.addUrlComponent('software', software);

                const source = new EventSource(self.targetUrl,{ withCredentials: true });



                const closeSource = () => {
                    // commit('SET_UPDATE_STATUS', false);
                    // commit('SET_UPDATE_PID', 0);
                    source.close();
                    // if (onDone) {
                    //     dispatch(onDone);
                    // }
                    // resolve(state.updateCommandData.log);
                };
                self.eventSource=source;

                self.updateEvent=event;
                source.addEventListener(
                    'message',
                    (e) => {
                        const { data, finished, pid } = JSON.parse(e.data);
                        if (finished) {
                            closeSource();
                            self.hideSpinner(event);
                            jQuery(".killprocessbtn").addClass("hidden");
                            self.updateProjects();
                        }
                        if (pid && pid !== '0') {
                            self.commit('SET_UPDATE_PID', Number(pid));
                        }
                        if (data) {
                            self.commit('SET_UPDATE_LOG', data);
                        }
                    },
                    false
                );

                source.addEventListener(
                    'error',
                    (e) => {
                        if (e.readyState === EventSource.CLOSED) {
                            // commit('SET_UPDATE_STATUS', false);
                            // commit('SET_UPDATE_PID', 0);
                            // reject(new Error('Connection was closed'));
                        } else {
                            // reject(new Error('EventSource Error'));
                        }
                    },
                    false
                );

                    }
                    else {
                        //todo error reporting
                    }
                },
    editOptions:function(targetId,event){
        event.preventDefault();
        var self=this;

        const diff = {};
        for (var group in self.currentSettings) {
            const vars = self.currentSettings[group];
            const inputs = Object.keys(vars);
            for (key of inputs){
                const selector = `[name="${group}:${key}"]`;
                let el = $(selector);
                if (el.attr('type') === 'radio') {
                    el = $(selector + ':checked');
                }
                if (el) {
                    const value = el.val();
                    if (value && value !== self.currentSettings[group][key]) {
                        if (typeof diff[group] === 'undefined') {
                            diff[group] = {};
                        }
                        diff[group][key] = value;
                    }
                }
            }
        }

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
                data: JSON.stringify(diff),
                processData: false,
                contentType: false,
                dataType: 'json',
            })
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
        }else {

        }


        return false;
        $.ajax({
            url: 'update_command.raw',
            data: JSON.stringify(diff),
            method: 'POST',
            dataType: 'json',
            success: function (data) {
                notifications.empty();

                if (data.messages.length > 0) {
                    for (var i = 0; i < data.messages.length; i++) {
                        var message = data.messages[i];
                        createNotification(message.status, message.message);
                        if(message.status == 'success') {
                            setTimeout(function() {window.location.reload()}, 2000);
                        }
                    }
                } else {
                    createNotification('info', 'No changes have been made');
                }

                Object
                .entries(diff)
                .forEach(
                    ([group, values]) => Object.assign(currentSettings[group], values)
                    );
                button.button('reset');

                $('html, body').animate({
                    scrollTop: notifications.offset().top
                }, 100);
            }
        });

        return false;
    },
    update:function(targetId,event){
        event.preventDefault();
        var self = this;
        if (self.appActionBlockingState) {
            return true;
        }
        self.appActionBlockingState = true;
        var formTargetId = ($('#'+targetId)[0].tagName === 'FORM') ? targetId : $('#'+targetId).find('form').attr('id');
        if(formTargetId){
            self.loadModal(event,targetId,'ModulesGarden_Servers_DirectAdminExtended_App_UI_Client_Admin_CustomBuild_Buttons_Progress','loadProgressModal',{'loadProgressModal':true},false);
            self.pause(2000);

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
            var software=jQuery("#"+formTargetId).find('input[name="software"]').val();;
            self.addUrlComponent('software', software);

            const source = new EventSource(self.targetUrl,{ withCredentials: true });

            const closeSource = () => {
                source.close();

            };
            self.eventSource=source;

            self.updateEvent=event;
            source.addEventListener(
                'message',
                (e) => {
                    const { data, finished, pid } = JSON.parse(e.data);
                    if (finished) {
                        closeSource();
                        self.hideSpinner(event);
                        jQuery(".killprocessbtn").addClass("hidden");
                        self.appActionBlockingState=false;
                    }
                    if (pid && pid !== '0') {
                        self.commit('SET_UPDATE_PID', Number(pid));
                    }
                    if (data) {
                        self.commit('SET_UPDATE_LOG', data);
                    }
                },
                false
                );

            source.addEventListener(
                'error',
                (e) => {
                    if (e.readyState === EventSource.CLOSED) {

                    } else {
                    }
                },
                false
                );

        }else {
        }
    },
}
});
