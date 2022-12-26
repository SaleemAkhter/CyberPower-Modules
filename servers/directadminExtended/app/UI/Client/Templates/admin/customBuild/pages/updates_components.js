mgJsComponentHandler.addDefaultComponent('mg-updates', {
    template : '#t-mg-updates',
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
            updates: [],
            step:1,
            directory:'',
            pid:'',
            eventSource:false,
            updateEvent:false
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
        log(item) {
          console.log(item)
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
                    self.updates=actionData.data.records;
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
            mgPageControler.vueLoader.loadModal(event, targetId,
                typeof namespace !== 'undefined' ? namespace : getItemNamespace(targetId),
                typeof index !== 'undefined' ? index : getItemIndex(targetId), params, addSpinner);
        },
        ajaxAction: function(event, targetId, namespace, index, params, addSpinner){
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
        update:function(targetId,event){
            event.preventDefault();
            var self = this;
            if (self.appActionBlockingState) {
                return true;
            }
            self.appActionBlockingState = true;
            var formTargetId = ($('#'+targetId)[0].tagName === 'FORM') ? targetId : $('#'+targetId).find('form').attr('id');
            if(formTargetId){
                self.showSpinner(event);
                self.loadModal(event,targetId,'ModulesGarden_Servers_DirectAdminExtended_App_UI_Client_Admin_CustomBuild_Buttons_Progress','loadProgressModal',{'loadProgressModal':true},false);


                setTimeout(() => {


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
                }, "1000");
                    }
                    else {
                        //todo error reporting
                    }
                },
    }
});
