$('body').attr('id', 'layers-body');

var mgUrlParser = {
    url: null,
    
    getCurrentUrl: function(){
        if(!this.url){
            if(window.location.href.indexOf('#') > 0){
                this.url = window.location.href.substr(0, window.location.href.indexOf('#'));
            }else{
                this.url = window.location.href;
            }       
        }
        
        return this.url;
    }
};

var mgJsComponentHandler = {
    defaultComponentJs: {},
    customComponentJs: {},
    defaultBasedComponents: {},

    //adds default component js template
    addDefaultComponent: function (componentId, componentObj) {
        this.defaultComponentJs[componentId] = componentObj;
    },

    //returns default component js template by id
    getDefaultComponent: function (componentId) {
        if (this.defaultComponentJs.hasOwnProperty(componentId)) {
            return this.defaultComponentJs[componentId];
        } else {
            return false;
        }
    },

    //adds custom component js
    addCustomComponent: function (componentId, componentObj) {
        this.customComponentJs[componentId] = componentObj;
    },

    //adds a component based on default js template
    addComponentByDefaultTemplate: function (componentId, templateId) {
        this.defaultBasedComponents[componentId] = templateId;
    },

    //register all available components as a Vue components
    registerComponents: function () {
        this.registerDefaultComponents();
        this.registerCustomComponents();
    },

    //register custom components as a Vue components
    registerCustomComponents: function () {
        for (var key in this.customComponentJs) {
            if (!this.customComponentJs.hasOwnProperty(key)) {
                continue;
            }
            var componentObj = this.customComponentJs[key];
            Vue.component('mg-component-body-' + key, componentObj);
        }
    },

    //register default components as a Vue components
    registerDefaultComponents: function () {
        for (var key in this.defaultBasedComponents) {
            if (!this.defaultBasedComponents.hasOwnProperty(key)) {
                continue;
            }
            if (this.defaultComponentJs.hasOwnProperty(this.defaultBasedComponents[key])) {
                var componentObj = Object.assign({}, this.defaultComponentJs[this.defaultBasedComponents[key]]);
                componentObj.template = componentObj.template + '-' + key;
                Vue.component('mg-component-body-' + key, componentObj);
            } else {
                console.log('No default component ' + this.defaultBasedComponents[key] + ' found');
            }
        }
    },

    //register custom Vue component now
    registerNowCustomComponent: function(componentId, componentObj) {
        Vue.component('mg-component-body-' + componentId, componentObj);
    },

    //register Vue component based on default component template now
    registerNowByDefaultTemplate: function(componentId, templateName) {
        if (this.defaultComponentJs.hasOwnProperty(templateName)) {
            var componentObj = Object.assign({}, this.defaultComponentJs[templateName]);
            componentObj.template = componentObj.template + '-' + componentId;
            Vue.component('mg-component-body-' + componentId, componentObj);
        } else {
            console.log('No default component ' + templateName + ' found');
        }
    },
    returnByDefaultTemplate: function(componentId, templateName) {
        if (this.defaultComponentJs.hasOwnProperty(templateName)) {
            var componentObj = Object.assign({}, this.defaultComponentJs[templateName]);
            componentObj.template = componentObj.template + '-' + componentId;
            return { name: 'mg-component-body-' + componentId, obj: componentObj};
        } else {
            console.log('No default component ' + templateName + ' found');
            return null;
        }
    },
    extendRegisterByDefaultTemplate: function(componentId, templateName) {
        if (this.defaultComponentJs.hasOwnProperty(templateName) && typeof $('#' + componentId) !== 'undefined') {
            var componentObj = Object.assign({}, this.defaultComponentJs[templateName]);
            componentObj.template = componentObj.template + '-' + componentId;

            var renderComponent = Vue.extend(componentObj);
            new renderComponent().$mount('#' + componentId);
        } else if($('#' + componentId).length === 0) {
            console.log('No component item ' + componentId + ' found');
            return null;
        } else {
            console.log('No default component ' + templateName + ' found');
            return null;
        }
    }
};

function getScriptWithTemplate(template) {
    var scriptHtml = '';
    var findScript = false;
    var start = -1;
    var end = -1;
    do {
        findScript = false;
        start = template.search('<script');
        end = template.search('</script>');
        if (start != -1 && end != -1) {
            
            scriptHtml += template.substring(start, end + 9);
            template = template.replace(template.substring(start, end + 9), '');
            findScript = true;
        }
    } while (findScript);
    return {template: template, scriptHtml: scriptHtml};
}

function initColorPickers(){
    jQuery('.jscolor').each(function(){
        if(!jQuery(this).hasClass('jscolor-active')){
            new jscolor(this);
        }
    });
}

function getItemNamespace (elId) {
    return jQuery('#' + elId).attr('namespace');
}

function getItemIndex (elId) {
    return jQuery('#' + elId).attr('index');
}

function initMassActionsOnDatatables(elId){

    $('#' + elId + ' [data-check-container]').luCheckAll({
        onCheck: function(container, counter) { 
            var massActions = container.find('.t-c__mass-actions');
            massActions.find('.value').html(counter);
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
    $('.t-c__mass-actions').removeClass('is-open');
    $('.table-mass-action-check').prop('checked', false);
    $('thead input:checkbox:enabled').prop('checked', false);
}

function initTooltipsForDatatables(elId) {
    $('#' + elId + ' [data-toggle="tooltip"], [data-tooltip]').luTooltip({});    
}

function initModalSelects(){
    $('#mgModalContainer select:not(.ajax)').luSelect();
}

function initModalTooltips(){
    $('#mgModalContainer [data-toggle="lu-tooltip"], [data-toggle="tooltip"], [data-tooltip]').luTooltip({});
}

function loadDatatables(){
    $('.vueDatatableTable').each(function(){
        var elId = $(this).attr('id');
        Vue.component(('mgdatatablebody'+elId).toLowerCase(), {
            template : '#mg-datatable-template'+elId,
            data : function(){
                return {
                    tableWrapperId: elId,
                    dataRows: [],
                    length: 10,
                    iSortCol_0 : '',
                    sSortDir_0 : '',
                    addTimeout : false,
                    sSearch : false,
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
                    onOffSwitchEnabled : false
                };
            },
            created: function () {
                var self = this;
                self.addTimeout = true;
                self.updateProjects();
                self.$parent.$root.$on('reloadMgData', this.updateMgData);
            },
            updated: function (){
                initMassActionsOnDatatables(elId);
                initTooltipsForDatatables(elId);                
            },
            methods: {
                updateMgData: function(toReloadId){
                    var self = this;
                    if(self.tableWrapperId === toReloadId){
                        self.updateProjects();
                        self.$nextTick(function() {
                            self.$emit('restartRefreshingState');
                        });
                    }
                },
                updateProjects: function(){
                    var self = this;
                    self.loading = true;
                    self.dataShowing = self.sSearch !== false ? 0 : self.dataShowing;
                    var resp = self.$parent.$root.$options.methods.vloadData({loadData : elId, namespace : getItemNamespace(elId), index: getItemIndex(elId), iDisplayLength : self.length, iDisplayStart : self.dataShowing, sSearch : (self.sSearch !== false ? self.sSearch : ''), iSortCol_0 : self.iSortCol_0, sSortDir_0 : self.sSortDir_0});
                    resp.done(function(data){
                        data = data.data.rawData;
                        self.dataRows  = data.records;
                        self.dataShowing = data.offset;
                        self.dataTo = data.records.length + data.offset;
                        self.dataFrom = data.fullDataLenght;
                        self.addTimeout = false;
                        if(self.addTimeout === true){
                            setTimeout(self.updateProjects, 60000);
                            self.addTimeout = false;
                        }
                        self.updatePagination();
                        self.loading = false;
                        self.noData = data.records.length > 0 ? false : true;
                    }).fail(function() {
                        self.loading = false;
                        self.dataRows = [];
                        self.noData = true;
                    });
                    uncheckSelectAllCheck(self.tableWrapperId);
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
                loadModal: function(event, targetId, namespace, index){
                    mgPageControler.vueLoader.loadM2(event, targetId, 
                        typeof namespace !== 'undefined' ? namespace : getItemNamespace(targetId), 
                        typeof index !== 'undefined' ? index : getItemIndex(targetId));  
                },
                onOffSwitch: function(event, targetId){
                    var switchPostData = $(event.target).is(':checked') ? {'value' : 'on'} : {'value' : 'off'};
                    mgPageControler.vueLoader.ajaxAction(event, targetId, getItemNamespace(targetId), getItemIndex(targetId), switchPostData);                  
                },   
                makeCustomActiom : function(functionName, params, event, namespace, index) {
                    mgPageControler.vueLoader.makeCustomActiom(functionName, params, event, namespace, index);
                },             
                redirect :  function (event, params) {
                    mgPageControler.vueLoader.redirect(event, params);
                }
            }
        });
    });
    
}

function loadComponsnts(){
    Vue.component('mg-modal', {
        functional: true,
        render: function(createElement, context) {
            var templateData = getScriptWithTemplate(context.props.bodydata);
            context.template = templateData.template;
            $('#allScript').html(templateData.scriptHtml);
            context.methods = {
                submitForm: function ($event) {
                    mgPageControler.vueLoader.submitForm('mgModalContainer', $event);
                },
                makeCustomActiom: function (functionName, params, event, namespace, index) {
                    mgPageControler.vueLoader.makeCustomActiom(functionName, params, event, namespace, index);
                },
                closeModal: function (event) {
                    mgPageControler.vueLoader.showModal = false;
                },
                ajaxAction : function(event, targetId, namespace, index, postData) {
                    mgPageControler.vueLoader.ajaxAction(event, targetId, namespace, index, postData);
                },
                redirect : function (event, params) {
                    mgPageControler.vueLoader.redirect(event, params);
                },
                reloadSelect: function (name, targetId, namespace, index) {
                    var self = this;
                    $.each(self.$children, function (id, children) {
                        if ($(children.$el).attr('fieldName') == name) {
                            children.$nextTick(function() {
                                children.fieldName = name;
                                children.renderNow($(this.$el) ,targetId, namespace, index);
                            });
                        }
                    });
                }
            };
            return createElement(context);
         },
        props : {
            bodydata : String
        }
    });
    
    Vue.component('mg-field-ajax', {
        data: {
            element: null,
            renderBody: null,
            renderElement: false,
            fieldName: null,
            namespace: '',
            index: '',
            fieldId : this.fieldId,
            isRenderAgain: this.isRenderAgain,
            renderList: this.renderList
        },
        created: function () {
            var self = this;
        },
        mounted: function () {
            var self = this;
            self.$emit('fieldrendernow');
            if (self.renderList) {
                $.each(self.renderList, function (nameField, custopParams) {
                    $('input[name=' + nameField + '],select[name=' + nameField + '],textarea[name=' + nameField + ']').on('change', function () {
                        if (self.isRender()) {
                            self.renderElement = false;
                            self.renderNow(self.element, self.fieldName, self.namespace, self.index, custopParams);
                        }
                    });
                });
            } else {
               $.each(self.$vnode.data.attrs.renderlist, function (nameField, custopParams) {
                    $('input[name=' + nameField + '],select[name=' + nameField + '],textarea[name=' + nameField + ']').on('change', function () {
                        if (self.isRender()) {
                            self.renderElement = false;
                            self.renderNow(self.element, self.fieldName, self.namespace, self.index, custopParams);
                        }
                    });
                }); 
            }
            
        },
        methods: {
            isRender : function () {
                return this.renderElement;
            },
            reloadSelect: function (name, targetId, namespace, index) {
                var self = this;
                self.renderNow($(self.$el) ,targetId, namespace, index);
               
            },
            renderNow : function (element, targetId, namespace, index, customParams) {
                var self = this;
                self.$nextTick(function() {
                    element = $('select[name=' + element.attr('name') + ']').first();
                    if (self.isRender()) {
                        return;
                    }
                    self.element = element;
                    
                    if (!self.renderBody) {
                        element.parent().find('div.selectize-control').remove();
                        self.renderBody = element.parent().clone(true);
                    }
                    self.fieldName  = targetId;
                    self.namespace  = namespace;
                    self.index      = index;
                    var globalPage = mgPageControler.vueLoader;
                    globalPage.refreshUrl();
                    globalPage.addUrlComponent('loadData', targetId);
                    globalPage.addUrlComponent('namespace', namespace);
                    globalPage.addUrlComponent('index', index);
                    globalPage.addUrlComponent('ajax', '1');
                    var formCont = new mgFormControler(element.parents("form").attr('id'));
                    var formData = formCont.getFieldsData();
                    formData.customParams = customParams;
                    $.post(globalPage.targetUrl, formData, {}, 'json')
                        .done(function( data ) {
                            self.renderElement = true;
                            data = data.data;
                            if(data.status === 'success'){
                                var body = self.renderBody.clone(true);
                                $.each(data.rawData, function (index, dataRow) {
                                    body.children('select').first().append(buildOptionTag(dataRow.title, index, dataRow.selected, dataRow.disabled));
                                });
                                var parent = element.parent();
                                parent.children().remove();
                                parent.html(body.html());
                                self.$nextTick(function() {
                                    parent.children('select').first().luSelect();
                                });
                            }
                            globalPage.$nextTick(function() {
                                if (data.callBackFunction && typeof window[data.callBackFunction] === "function") {
                                    window[data.callBackFunction](data, targetId, element);
                                }
                            });
                    }, 'json');
                    globalPage.refreshUrl(); 
                });
            }
        },
        props : {
            fieldId : String,
            isRenderAgain: Boolean,
            renderList: Object
        }
    });
    
    Vue.component('mg-emptyContainer', {
        template : '#mg-emptyBodyContainer',
        data : function(){
            return {
                contentLoading : true
            };
        }
    });     

    Vue.component('left-category-menu', {
        template : '#mg-category-menu',
        data : function(){
            return {
                tableWrapperId: 'mg-category-menu',
                returnedData : [],
                targetid : null,
                menuLoading : false,
                sSearch : null,
                dataContent : '',
                showModal : false,
                contentContainerName : 'mg-emptyContainer',
                modalBodyContainer : 'mg-modal-body',
            };
        },
        mounted: function () {
            this.loadCategories(this.loadCategories);
        },
        created: function () {
            var self = this;
            self.$parent.$root.$on('reloadMgData', this.updateMgData);
        },
        methods: {
            updateMgData : function (toReloadId) {
                var self = this;
                if(self.tableWrapperId === toReloadId){
                    self.loadCategories(true);
                    self.$nextTick(function() {
                        self.$emit('restartRefreshingState');
                    });
                }                
            },
            reloadMenuContent : function(categoryId, namespace, index){
                if($("#groupList").attr("isBeingSorted")) {
                    $("#groupList").removeAttr("isBeingSorted");
                    return;
                }
                var self = this;
                self.contentContainerName = 'mg-emptyContainer';
                var resp = self.$parent.$options.methods.vloadData({loadData : categoryId, namespace : namespace, index : index});
                resp.done(function(data){
                    data = data.data;
                    $('#groupList').find('li.is-active').removeClass('is-active');
                    $('#mg-templateContainer').html(data.htmlData);
                    $('#groupList').find('#'+categoryId).addClass('is-active');
                    self.contentContainerName = 'mg-content-container-body';
                    mgPageControler.vueLoader.$nextTick(function() {
                        $('#itemContentContainer [data-content-slider]').luContentSlider();
                    });
                    self.$nextTick(function() {
                        tldCategoriesSortableController();
                    });
                });
            },
            searchData : function(event){
                var self = this;
                self.sSearch = $(event.target).val();
                self.loadCategories();
            },
            loadCategories : function(callback = false){
                var self = this;
                self.menuLoading = true;
                self.targetid = self.$el.attributes.getNamedItem('targetid').value;
                var reqParams = {loadData : self.targetid , namespace : getItemNamespace(self.targetid), index : getItemIndex(self.targetid)};
                if(self.sSearch !== null){
                    reqParams.sSearch = self.sSearch;
                }
                var resp = self.$parent.$options.methods.vloadData(reqParams);
                resp.done(function(data){
                    self.returnedData = data.data.rawData;
                    self.menuLoading = false;
                    if(callback){
                        self.reloadMenuContent(data.data.rawData[0].elId,data.data.rawData[0].namespace,data.data.rawData[0].id);
                    }
                    self.$nextTick(function() {
                        tldCategoriesSortableController();
                    });                    
                });               
            },
            loadModal : function(event, targetId, namespace, index){//needs refactoring
                event.stopImmediatePropagation();
                mgPageControler.vueLoader.loadM2(event, targetId, namespace, index);
            }
        }
    });

    $(document).on('keypress', function (event) {
        if (event.keyCode === 13) {
            if ($("div.modal div.modal__dialog div.modal__content div.modal__actions button.submitForm").length > 0) {
                $("div.modal div.modal__dialog div.modal__content div.modal__actions button.submitForm").trigger('click');
            }
            
            if ($("form div.app__main-actions a").length > 0) {
                $("form div.app__main-actions a").bind('keyup', function (event1) {
                    mgPageControler.vueLoader.submitForm($("form div.app__main-actions a").parents('form').attr('id'), event1);
                });
                $("form div.app__main-actions a").trigger('keyup');
                $("form div.app__main-actions a").unbind('keyup');
            }
        }
    });
};  

function mgVuePageControler(controlerId) {
    this.baseLoaderUrl = mgUrlParser.getCurrentUrl(),
    this.vueLoader = false,
    this.vinit = function() {
        var cthis = this;
        cthis.vueLoader =  new Vue({
            el: '#'+controlerId,
            data: {
                targetId : controlerId,
                targetUrl : mgUrlParser.getCurrentUrl(),
                pageLoading : false,
                returnedData : null,
                loading : false,
                loaderComponent : '<div class="row"><i class="dataTables_processing"></i></div>',
                sSearch : null,
                showModal : false,
                htmlContent : '',
                modalBodyContainer : 'mg-modal-body',
                refreshingState : null,
                massActionIds : null,
                massActionTargetCont : null,
                pagePreLoader : null
            },
            created: function () {
                var self = this;
                loadComponsnts();
                loadDatatables();
                self.$on('restartRefreshingState', self.cleanRefreshActionsState() );
            },
            methods: {
                vloadData : function (params) {
                    var self = this;
                    self.refreshUrl();
                    for(var propertyName in params) {
                        self.addUrlComponent(propertyName, params[propertyName]);
                    }
                    self.addUrlComponent('ajax', '1');
                    return $.get(self.targetUrl, function(data){
                        data = data.data;
                        if (data.callBackFunction && typeof window[data.callBackFunction] === "function") {
                            window[data.callBackFunction](data);
                        }
                    }, 'json').fail(function() {
                        //self.addAlert('danger', 'Action Failed');
                        console.log('Action Failed');
                    });
                },
                addUrlComponent : function($name, $value) {
                    var self = this;
                    self.targetUrl += (self.targetUrl.indexOf('?') !== -1 ? '&' : '?') + $name + '=' + encodeURIComponent($value);
                },
                updateUrlParam : function(key, value, event){
                    var self = this;
                    value = self.updateValueByAttrs(key, value, event);
                    if(self.targetUrl.indexOf(key) === -1){
                        self.addUrlComponent(key, value);
                    } else {
                        var baseUrlParts = self.targetUrl.split('?');
                        var currentUrlParams = baseUrlParts[1].split('&');
                        for(i=0; i < currentUrlParams.length; i++){
                            if(currentUrlParams[i].indexOf(key) === 0){
                                if(value === ''){
                                    currentUrlParams.splice(i, 1);
                                } else {
                                    currentUrlParams[i] = key + '=' + value;
                                }
                            }
                        }
                        var updatedUrlParams = currentUrlParams.join('&');
                        self.targetUrl = baseUrlParts[0] + '?' + updatedUrlParams;
                    }
                },
                updateValueByAttrs : function(key, value, event){
                    if(value.indexOf(':') !== 0){
                        return value;
                    } else {
                        if($(event.target).attr('data-' + key)) {
                            return $(event.target).attr('data-' + key);
                        } else if( $(event.target).parents('a').first().attr('data-' + key)) {
                            return $(event.target).parents('a').first().attr('data-' + key);
                        } else if( $(event.target).parents('button').first().attr('data-' + key)) {
                            return $(event.target).parents('button').first().attr('data-' + key);
                        } else {
                            return value;
                        }
                    }
                },
                refreshUrl : function() {
                    var self = this;
                    self.targetUrl = mgUrlParser.getCurrentUrl();
                    if(self.targetUrl.indexOf('#') > 0) {
                        self.targetUrl = self.targetUrl.substr(0, self.targetUrl.indexOf('#'));
                    }
                },
                loadModal : function(event, targetId, namespace, index){
                    var self = this;
                    self.showSpinner(event);
                    self.refreshUrl();
                    self.initRefreshActions(event, targetId);
                    self.initMassActions(event);
                    self.addUrlComponent('loadData', targetId);
                    self.addUrlComponent('namespace', namespace);
                    self.addUrlComponent('index', index);
                    self.addUrlComponent('mgFormAction', 'read');
                    self.getActionId(event);
                    self.addUrlComponent('ajax', '1');
                    $.get(self.targetUrl, function(data){
                        data = data.data;
                        if(data.status === 'success'){
                            self.htmlContent = data.htmlData;
                            self.showModal = true;
                            self.$nextTick(function() {
                                initColorPickers();
                                initModalSelects();
                                initModalTooltips();
                            });
                        }
                        self.$nextTick(function() {
                            if (data.callBackFunction && typeof window[data.callBackFunction] === "function") {
                                window[data.callBackFunction](data, targetId, event);
                            }
                        });
                        self.hideSpinner(event);
                    }, 'json').fail(function() {
                        //self.addAlert('danger', 'Action Failed');
                        console.log('Action Failed');
                        self.hideSpinner(event);
                    });
                    self.refreshUrl();
                },
                loadM2 : function(event, targetId, namespace, index) {
                    var self = this;
                    self.loadModal(event, targetId, namespace, index);
                },
                showSpinner : function(event) {
                    var self = this;
                    var spinnerTarget = self.getSpinerTarget(event);
                    if (spinnerTarget.length > 0 || $(spinnerTarget).tagName === 'I') {
                        var isBtnIcon = $(spinnerTarget).hasClass('btn--icon');
                        $(spinnerTarget).attr('originall-button-icon', $(spinnerTarget).attr('class'));
                        $(spinnerTarget).removeAttr('class');
                        $(spinnerTarget).attr('class', (isBtnIcon ? 'btn--icon ' : '') + 'preloader preloader--sm');
                    } else {
                        self.addSpinner(event);
                    }
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
                    var spinnerClass = $(event.target).hasClass('btn--success') ? 'preloader-success' : ($(event.target).hasClass('btn--danger') ? 'preloader-danger' : '');
                    $(event.target).attr('originall-button-content', $(event.target).html());
                    $(event.target).html('<span class="btn__icon temp-button-loader" style="margin: 0 0 0 0 !important; width: ' + elWidth + 'px;"><i class="preloader preloader--sm ' + spinnerClass + '"></i></span>');
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
                initMassActions : function(event){
                    var self = this;
                    self.cleanMassActions();  
                    if($(event.target).parents('.t-c__mass-actions').length === 0)
                    {
                        return;
                    }
                    self.addUrlComponent('isMassAction', '1');
                    var tableContainer = $(event.target).parents('.vueDatatableTable').first().attr('id');
                    self.massActionTargetCont = tableContainer;
                    self.massActionIds = collectTableMassActionsData(tableContainer);
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
                initRefreshActions : function(event, targetId) {
                    var self = this;
                    var menuReloading = ['addCategoryButton', 'editCategoryButton', 'deleteCategoryButton'];
                    if(menuReloading.indexOf(targetId) > -1)
                    {
                        self.refreshingState = 'mg-category-menu';
                        return;
                    }
                    var tableContainer = $(event.target).parents('.vueDatatableTable').first();
                    self.refreshingState = $(tableContainer).attr('id');
                },
                runRefreshActions : function() {
                    var self = this;
                    if(self.refreshingState !== null){
                        self.$nextTick(function() {
                            self.$emit('reloadMgData', self.refreshingState);
                        });
                    }
                },
                cleanRefreshActionsState : function() {
                    var self = this;
                    self.refreshingState = null;
                },                  
                getActionId : function(event) {
                    var self = this;
                    var tableActions = $(event.target).parents("td.mgTableActions");
                    var widgetActionComponent = $(event.target).parents("div.widgetActionComponent");
                    if($(tableActions).length  === 1){
                        var row = $(tableActions[0]).parent('tr');
                        var actionElementId = $(row).attr("actionid");
                        if(actionElementId){
                            self.addUrlComponent('actionElementId', actionElementId);
                        }
                    } else if($(widgetActionComponent).length  === 1){
                        var actionElementId = $(widgetActionComponent[0]).attr("actionid");
                        if(actionElementId){
                            self.addUrlComponent('actionElementId', actionElementId);
                        }                        
                    }                    
                },
                submitForm : function(targetId, event) {
                    var self = this;
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
                        self.addUrlComponent('mgFormAction', $('#'+formTargetId).attr('mgformaction'));
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
                                self.runRefreshActions();
                                self.cleanMassActions();
                                self.addAlert(data.status, data.message);
                                formCont.updateFieldsValidationMessages([]);
                            }else if(data.rawData.FormValidationErrors !== undefined) {
                                formCont.updateFieldsValidationMessages(data.rawData.FormValidationErrors);
                            }
                        });
                    }
                    else{
                        //todo error reporting
                    }
                },
                ajaxAction : function(event, targetId, namespace, index, postData) {
                    var self = this;
                    self.refreshUrl();
                    self.initRefreshActions(event, targetId);
                    self.addUrlComponent('loadData', targetId);
                    self.addUrlComponent('namespace', namespace);
                    self.addUrlComponent('index', index);
                    self.getActionId(event);
                    self.addUrlComponent('ajax', '1');
                    $.post(self.targetUrl, postData, {},  'json')
                        .done(function( data ) {
                            data = data.data;
                            self.addAlert(data.status, data.message);
                            self.$nextTick(function() {
                                if (data.callBackFunction && typeof window[data.callBackFunction] === "function") {
                                    window[data.callBackFunction](data, targetId, event);
                                }
                            });
                            if(data.status === 'success'){

                            }
                    }, 'json');
                    self.refreshUrl();               
                },                
                updateSorting : function(order, loadData, namespace) 
                {
                    var self = this;
                    
                    self.refreshUrl();
                    self.addUrlComponent('loadData', loadData);
                    self.addUrlComponent('namespace', namespace);
                    self.addUrlComponent('ajax', '1');
                    self.addUrlComponent('mgFormAction', "reorder");     
                    
                    var formData = {order : order}
                    $.post(self.targetUrl, {formData: formData}, {}, 'json').done(function( data )
                    {
                        data = data.data;
                        self.addAlert(data.status, data.message);
                        self.pageLoading = false;
                        self.$nextTick(function() {
                            if (data.callBackFunction && typeof window[data.callBackFunction] === "function") {
                                window[data.callBackFunction](data, loadData);
                            }
                        });
                        if(data.status === 'success')
                        {
                            //Dispaly alert?
                        }
                        else  
                        {
                            //TODO: Dispaly alert
                        }
                    });                 
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
                makeCustomActiom : function(functionName, params, event, namespace, index) {
                    var self = this;
                    if (typeof functionName === "function") {
                        functionName(self, params, event, namespace, index);
                    }
                },
                redirect : function (event, params) { 
                    var self = this;
                    var tempUrl = self.targetUrl;
                    if(params.rawUrl !== undefined){
                        self.targetUrl = params.rawUrl;
                    }
                    if(params.actionElementId !== undefined) {
                        self.getActionId(event);
                    }
                    $.each(params, function(key, value){
                        if(key === 'rawUrl' || key === 'actionElementId'){
                            return false;
                        } else {
                            self.updateUrlParam(key.replace('__', '-'), value, event);
                        }
                    });

                    window.location = self.targetUrl;
                }
            }
        });
    };
};

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
                if(name !== undefined && errorsList[name] !== undefined)
                {
                    if(!jQuery(this).parents('.form-group').first().hasClass('is-error')) {
                        jQuery(this).parents('.form-group').first().addClass('is-error');
                    }
                    
                    var messagePlaceholder = jQuery(this).parents('.form-group').first().children('.form-feedback');
                    if(jQuery(messagePlaceholder).length > 0)
                    {    
                        jQuery(messagePlaceholder).html(errorsList[name].slice(-1)[0]);
                        if(jQuery(messagePlaceholder).attr('hidden')){
                            jQuery(messagePlaceholder).removeAttr('hidden');
                        }
                    }
                }else if(name !== undefined) {
                    if(jQuery(this).parents('.form-group').first().hasClass('is-error')) {
                        jQuery(this).parents('.form-group').first().removeClass('is-error');
                    }
                    var messagePlaceholder = jQuery(this).next('.form-feedback');
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
            items: "li:not(.nav--sub li, .sortable-disabled)",
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

                mgPageControler.vueLoader.updateSorting(order, 'addCategoryForm', 'ModulesGarden_ModuleFramework_App_UI_Widget_DoeTldConfigComponents_CategoryForms_AddCategoryForm');
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
            
            mgPageControler.vueLoader.updateSorting(order, 'addCategoryForm', 'ModulesGarden_ModuleFramework_App_UI_Widget_DoeTldConfigComponents_CategoryForms_AddCategoryForm');
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
            mgPageControler.vueLoader.updateSorting(order, 'assignTldForm', 'ModulesGarden_ModuleFramework_App_UI_Widget_DoeTldConfigComponents_CategoryForms_AssignTldForm');
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