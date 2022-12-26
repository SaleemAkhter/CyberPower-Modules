mgJsComponentHandler.addDefaultComponent('mg-datatable', {
    template : '#t-mg-datatable',
    props: [
    'component_id',
    'component_namespace',
    'component_index'
    ],
    data : function() {
        return {
            targetUrl : 'clientarea.php?action=productdetails&id=10&mg-page=MoveBetweenResellers&modop=custom&a=management&mg-action=move', //mgUrlParser.getCurrentUrl(),
            dataRows: [],
            dataUserRows:[],
            dataResellerRows:[],
            length: 10,
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
            children: [],
            selectedreseller:'',
            appActionBlockingState: false

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
        initMassActionsOnDatatables(self.component_id);
        initTooltipsForDatatables(self.component_id);
    },
    methods: {
        updateMgData: function(toReloadId){
            var self = this;
            if(self.component_id === toReloadId){
                self.updateProjects();
                self.$nextTick(function() {
                    self.$emit('restartRefreshingState');
                });
            }
        },
        addMassActionsToData : function (formData){
            var self = this;
            if(self.massActionIds){
                for (var key in self.massActionIds) {
                    if (!self.massActionIds.hasOwnProperty(key)) {
                        continue;
                    }
                    formData.set('massActions[' + key + ']', self.massActionIds[key]);
                }
                return formData;
            }else{
                return formData;
            }
        },
        cleanMassActions : function(){
            var self = this;
            if(self.massActionIds || self.massActionTargetCont){
                self.massActionIds = null;
                        //uncheckSelectAllCheck(self.massActionTargetCont);
                        self.$nextTick(function() {
                            self.massActionTargetCont = null;
                        });
                    }
                },
                addUrlComponent : function($name, $value) {
                    var self = this;
                    self.targetUrl += (self.targetUrl.indexOf('?') !== -1 ? '&' : '?') + $name + '=' + encodeURIComponent($value);
                },
                removeSpinner : function(event) {
                    $(event.target).html($(event.target).attr('originall-button-content'));
                    $(event.target).removeAttr('originall-button-content');
                },
                 hideSpinner : function(event) {
                    var self = this;
                    var spinnerTarget = self.getSpinerTarget(event);
                    if ($(event.target).attr('originall-button-content')) {
                        self.removeSpinner(event);
                    } else if (spinnerTarget && spinnerTarget.length > 0 || $(spinnerTarget).tagName === 'I') {
                        $(spinnerTarget).removeAttr('class');
                        $(spinnerTarget).attr('class', $(spinnerTarget).attr('originall-button-icon'));
                        $(spinnerTarget).removeAttr('originall-button-icon');
                    }
                    else {
                        var newTarget = $('#mg-img-' + String(event.timeStamp).replace('.', '-')).closest('a');;
                        if (newTarget.length > 0) {
                            $(newTarget).html($(newTarget).attr('originall-button-content'));
                            $(newTarget).removeAttr('originall-button-content');
                        }
                    }
                },
                runRefreshActions : function(ids, data) {
                    var self = this;
                    var rfIds = (ids && ids.length > 0) ? ids : self.refreshingState;
                    var customParams = (data && typeof data.customParams !== undefined) ? data.customParams : null;
                    if(rfIds && rfIds.length > 0){
                        $.each(rfIds, function (index, Id) {
                            var targetId = Id;
                            self.$nextTick(function() {
                                self.$emit('reloadMgData', targetId, customParams);
                            });
                        });
                    }
                },
                cleanRefreshActionsState : function() {
                    var self = this;
                    self.refreshingState = [];
                },
                addAlert : function(type, message){
                    type = (type === 'error') ? 'danger' : type;
                    layers.alert.create({
                        $alertPosition: 'right-top',
                        $alertStatus: type,
                        $alertBody: message,
                        $alertTimeout: 1000000000
                    });
                },
                addSpinner : function(event) {
                    var elWidth = $(event.target).width();
                    var spinnerClass = $(event.target).hasClass('lu-btn--success') ? 'lu-preloader-success' : ($(event.target).hasClass('lu-btn--danger') ? 'lu-preloader-danger' : '');
                    $(event.target).attr('originall-button-content', $(event.target).html());
                    $(event.target).html('<span class="lu-btn__icon lu-temp-button-loader" style="margin: 0 0 0 0 !important; width: ' + elWidth + 'px;"><i class="lu-btn__icon lu-preloader lu-preloader--sm ' + spinnerClass + '"></i></span>');
                },
                getSpinerParent : function(event) {
                    if($(event.target)[0].tagName === 'A' || $(event.target)[0].tagName === 'BUTTON'){
                        return $(event.target)[0];
                    } else if($(event.target)[0].parents('button').first()){
                        return $(event.target)[0].parents('button').first();
                    } else if($(event.target)[0].parents('a').first()){
                        return $(event.target)[0].parents('a').first();
                    } else {
                        return null;
                    }
                },
                getSpinerTarget : function(event) {
                    if ($(event.target)[0].tagName === 'IMG') {
                        return $(event.target).closest('a');
                    } else if ($(event.target).attr('class') == "lu-tile__title") {
                        return $(event.target).closest('a');
                    } else if($(event.target)[0].tagName === 'I'){
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
                showSpinner : function(event) {
                    var self = this;
                    var spinnerTarget = self.getSpinerTarget(event);
                    if ($(event.target)[0].tagName === 'IMG' || $(event.target).attr('class') == "lu-tile__title") {
                        var elWidth = $(spinnerTarget).width();
                        $(spinnerTarget).attr('originall-button-content', spinnerTarget.html());
                        $(spinnerTarget).html('<span id="mg-img-' + String(event.timeStamp).replace('.', '-') + '" class="lu-btn__icon temp-button-loader" style="margin: 0 0 0 0 !important; width: ' + elWidth + 'px;"><i class="lu-preloader lu-preloader--sm"></i></span>');
                    } else if (spinnerTarget && spinnerTarget.length > 0 || $(spinnerTarget).tagName === 'I') {
                        var isBtnIcon = $(spinnerTarget).hasClass('lu-btn__icon');
                        $(spinnerTarget).attr('originall-button-icon', $(spinnerTarget).attr('class'));
                        $(spinnerTarget).removeAttr('class');
                        $(spinnerTarget).attr('class', (isBtnIcon ? 'lu-btn__icon ' : '') + 'lu-btn__icon lu-preloader lu-preloader--sm');
                    }  else {
                        self.addSpinner(event);
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
                            if( typeof data.records == "undefined"){
                                data.records =[];
                            }
                            if (data.records.length === 0 && self.dataShowing > 0) {
                                self.dataShowing = 0;
                                return self.updateProjects();
                            }
                            $('select').each(function() {
                                if (this.selectize) {
                                    this.selectize.destroy();
                                }
                            });
                            self.dataRows  = data.records;
                            self.dataUserRows  = data.records.data_list;
                            self.dataResellerRows  = data.records.creator;
                            self.runCalbacks(actionData);
                            mgEventHandler.runCallback('DatatableDataLoaded', self.component_id, {data: data.records, datatable: self});
                            self.dataShowing = data.offset;
                            self.dataTo = data.records.length + data.offset;
                            self.dataFrom = data.fullDataLenght;
                            self.addTimeout = false;
                            if(self.addTimeout === true){
                                setTimeout(self.updateProjects, 60000);
                                self.addTimeout = false;
                            }
                            self.noData = data.records.length > 0 ? false : true;
                    // $('select').each(function() {
                    //         $(this).selectize();
                    // });
                }
                self.updatePagination();
                self.loading = false;
            }).fail(function(jqXHR, textStatus, errorThrown) {
                self.loading = false;
                self.dataRows = [];
                self.noData = true;
                mgEventHandler.runCallback('DatatableDataLoadingFailed', self.component_id, {jqXHR: jqXHR, textStatus: textStatus, errorThrown: errorThrown});
                mgPageControler.vueLoader.handleServerError(jqXHR, textStatus, errorThrown);
            });
            uncheckSelectAllCheck(self.tableWrapperId);
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
                console.log(u);
                arr = self.children.filter(e => e !== u); // will return ['A', 'C']
                console.log(arr);
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

            // self.children=[];
        },
        refreshUrl : function() {
            var self = this;
            self.targetUrl = mgUrlParser.getCurrentUrl();
            if(self.targetUrl.indexOf('#') > 0) {
                self.targetUrl = self.targetUrl.substr(0, self.targetUrl.indexOf('#'));
            }
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
                self.addUrlComponent('mg-action', 'move');
                self.addUrlComponent('namespace', getItemNamespace(formTargetId));
                self.addUrlComponent('index', getItemIndex(formTargetId));
                self.addUrlComponent('ajax', '1');
                self.addUrlComponent('mgformtype', $('#'+formTargetId).attr('mgformtype'));
                formData.append('formData[children]',self.children);
                $.ajax({
                    type: "POST",
                    url: self.targetUrl+"&sub=1",
                    data: formData,
                    processData: false,
                    contentType: false})
                .done(function( data ) {
                    data = data;
                    self.hideSpinner(event);
                    if(data.status === 'success'){
                        self.showModal = false;
                        self.runRefreshActions((data && typeof data.refreshIds !== undefined) ? data.refreshIds : null, data);
                        self.cleanMassActions();
                        self.addAlert(data.status, data.message);
                        formCont.updateFieldsValidationMessages([]);

                        setTimeout(function () {
                           window.location.href=data.url;
                        }, 1000);
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

            }
        });
