$('body').attr('id', 'layers-body');

var mgUrlParser = {
    url: null,

    getCurrentUrl: function () {
        if (!this.url) {
            if (window.location.href.indexOf('#') > 0) {
                this.url = window.location.href.substr(0, window.location.href.indexOf('#'));
            } else {
                this.url = window.location.href;
            }
        }

        return this.url;
    }
};

function mgfocusOnModal() {
    var input = jQuery('#mgModalContainer').first().find('form').first().find('input').first();
    if (!input.parent().hasClass('has-items')) {
        input.focus();
    }
}

function initColorPickers() {
    jQuery('.jscolor').each(function () {
        if (!jQuery(this).hasClass('jscolor-active')) {
            new jscolor(this);
        }
    });
}

function getItemNamespace(elId) {
    return jQuery('#' + elId).attr('namespace');
}

function getItemIndex(elId) {
    return jQuery('#' + elId).attr('index');
}

function initMassActionsOnDatatables(elId) {

    $('#' + elId + ' [data-check-container]').luCheckAll({
        onCheck: function (container, counter) {
            var massActions = container.find('.lu-t-c__mass-actions');
            massActions.find('.value').html(counter);
            if (counter > 0) {
                massActions.addClass('is-open');
            } else {
                massActions.removeClass('is-open');
            }
        }
    });
}

function collectTableMassActionsData(elId) {
    var colectedData = {};
    $('#' + elId + ' [data-check-container] tbody input:checkbox.table-mass-action-check:enabled:checked')
            .each(function (index, value) {
                colectedData[index] = $(this).val();
            });

    return colectedData;
}

function uncheckSelectAllCheck(elId) {
    closeAllSelectMassActions();
}

function closeAllSelectMassActions() {
    $('.lu-t-c__mass-actions').removeClass('is-open');
    $('.table-mass-action-check').prop('checked', false);
    $('thead input:checkbox:enabled').prop('checked', false);
}

function initTooltipsForDatatables(elId) {
    $('#' + elId + ' [data-toggle="tooltip"], [data-tooltip]').luTooltip({});
}

function initModalSelects() {
    $('#mgModalContainer select').luSelect();
}


function initDropdownInDataTable() {
    $('table tbody td .has-dropdown button').luDropdown();
}

function initModalTooltips() {
    $('#mgModalContainer [data-toggle="lu-tooltip"], [data-toggle="tooltip"], [data-tooltip]').each(function () {
        if ($(this).attr('data-title') !== undefined && $(this).attr('data-title') !== '') {
            $(this).luTooltip({});
        }
    });
}

function initModalActions() {
    $('#mgModalContainer select').each(function(){
        if(typeof $(this).attr('reload_change') !== "undefined")
        {
            var fname = $(this).attr('reload_change');
            $(this).on('change', function(event){
                if(typeof window[fname] === "function"){
                    window[fname](event);
                }
            });
        }
    });
}


function loadDatatables() {
    $('.vueDatatableTable').each(function () {
        var elId = $(this).attr('id');
        var tablelength = $(this).attr('tablelength');
        if (typeof tablelength != "string") {
            var tablelength = 10;
        }
        Vue.component(('mgdatatablebody' + elId).toLowerCase(), {
            template: '#mg-datatable-template' + elId,
            data: function () {
                return {
                    tableWrapperId: elId,
                    dataRows: [],
                    length: tablelength,
                    iSortCol_0: '',
                    sSortDir_0: '',
                    addTimeout: false,
                    sSearch: false,
                    dataShowing: 0,
                    dataTo: 0,
                    dataFrom: 0,
                    curPage: 1,
                    allPages: 1,
                    pagesMap: [],
                    loading: false,
                    show: true,
                    showModal: false,
                    noData: false,
                    onOffSwitchEnabled: false,
                    additionalData: []
                };
            },
            created: function () {
                var self = this;
                self.addTimeout = true;
                self.updateProjects();
                self.$parent.$root.$on('reloadMgData', this.updateMgData);
            },
            updated: function () {
                initMassActionsOnDatatables(elId);
                initTooltipsForDatatables(elId);
            },
            methods: {
                updateMgData: function (toReloadId) {
                    var self = this;
                    if (self.tableWrapperId === toReloadId) {
                        self.updateProjects();
                        self.$nextTick(function () {
                            self.$emit('restartRefreshingState');
                        });
                    }
                },
                updateProjects: function () {
                    var self = this;
                    self.loading = true;
                    var resp = self.$parent.$root.$options.methods.vloadData({loadData: elId, namespace: getItemNamespace(elId), index: getItemIndex(elId), iDisplayLength: self.length, iDisplayStart: self.dataShowing, sSearch: (self.sSearch !== false ? self.sSearch : ''), iSortCol_0: self.iSortCol_0, sSortDir_0: self.sSortDir_0});
                    resp.done(function (data) {
                        data = data.data.rawData;
                        self.dataRows = data.records;
                        self.dataShowing = data.offset;
                        self.dataTo = data.records.length + data.offset;
                        self.dataFrom = data.fullDataLenght;
                        if (data.additionalData !== undefined) {
                            self.additionalData = data.additionalData;
                        }
                        self.addTimeout = false;
                        if (self.addTimeout === true) {
                            setTimeout(self.updateProjects, 60000);
                            self.addTimeout = false;
                        }
                        self.updatePagination();
                        self.loading = false;
                        self.noData = data.records.length > 0 ? false : true;

                        self.$nextTick(function () {
                            initDropdownInDataTable();
                        });
                    }).fail(function () {
                        self.loading = false;
                        self.dataRows = [];
                        self.noData = true;
                    });
                    uncheckSelectAllCheck(self.tableWrapperId);
                },
                makeCustomActiom: function (functionName, params, event, namespace, index) {
                    var self = this;
                    if (typeof functionName === "function") {
                        functionName(self, params, event, namespace, index);
                    }
                },
                updateLength: function (event) {
                    var self = this;
                    var btnTarget = (typeof $(event.target).attr('data-length') === 'undefined') ? $(event.target).parent() : $(event.target);
                    self.length = $(btnTarget).attr('data-length');
                    self.dataShowing = 0;
                    $(btnTarget).parent().children('.active').removeClass('active');
                    $(btnTarget).addClass('active');
                    self.updateProjects();
                },
                updateSorting: function (event) {
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
                reverseSort: function (sort) {
                    var sortingType = 'sorting_asc';
                    if (sort === 'sorting_asc') {
                        sortingType = 'sorting_desc';
                    }
                    return sortingType;
                },
                getSortDir: function (elem, rawClass) {
                    var sorts = ['sorting_asc', 'sorting_desc', 'sorting'];
                    var sorting = '';
                    $.each(sorts, function (key, sort) {
                        if ($(elem).hasClass(sort) === true) {
                            sorting = rawClass ? sort : sort.replace('sorting_', '').replace('sorting', '');
                            return sorting;
                        }
                    });
                    return sorting;
                },
                searchData: function (event) {
                    if (event.which == "13") {
                        event.preventDefault()
                    }
                    var self = this;
                    self.dataShowing = 0;
                    self.sSearch = $(event.target).val() === '' ? false : $(event.target).val();
                    self.updateProjects();
                },
                updatePagination: function () {
                    var self = this;
                    self.curPage = (parseInt(self.dataShowing) / parseInt(self.length)) + 1;
                    var tempPages = parseInt(parseInt(self.dataFrom) / parseInt(self.length));
                    self.allPages = parseInt(tempPages + ((parseInt(self.length) * tempPages) < parseInt(self.dataFrom) ? 1 : 0));
                    self.updatePagesMap();
                },
                updatePagesMap: function () {
                    var self = this;
                    if (self.allPages === 1) {
                        self.pagesMap = [1];
                        return;
                    }
                    self.pagesMap = [
                        self.curPage - 5,
                        self.curPage - 4,
                        self.curPage - 3,
                        self.curPage - 2,
                        self.curPage - 1,
                        self.curPage,
                        self.curPage + 1,
                        self.curPage + 2,
                        self.curPage + 3,
                        self.curPage + 4,
                        self.curPage + 5
                    ];
                    for (i = 0; i < self.pagesMap.length; i++) {
                        if (self.pagesMap[i] < 0 || self.pagesMap[i] > self.allPages) {
                            self.pagesMap[i] = null;
                        }
                    }

                    if (self.pagesMap.indexOf(self.allPages) === -1) {
                        self.pagesMap[self.pagesMap.length - 1] = self.allPages;
                    }
                    if (self.pagesMap.indexOf(1) === -1) {
                        self.pagesMap[0] = 1;
                    }

                    if (self.allPages > 7 && self.curPage > 4) {
                        self.pagesMap[self.pagesMap.indexOf(Math.min(
                                self.curPage - 1 > 1 ? self.curPage - 1 : self.curPage,
                                self.curPage - 2 > 1 ? self.curPage - 2 : self.curPage,
                                self.curPage - 3 > 1 ? self.curPage - 3 : self.curPage,
                                self.curPage - 4 > 1 ? self.curPage - 4 : self.curPage
                                ))] = '...';
                    }

                    for (i = self.pagesMap.length - 1; i > self.pagesMap.indexOf(self.curPage); i--) {
                        if (i === 8 && self.pagesMap[i] === self.curPage + 3 && self.pagesMap[i] !== self.allPages) {
                            self.pagesMap[i] = null;
                        }
                    }
                    if (self.allPages > 7 && (4 <= self.allPages - self.curPage)) {
                        self.pagesMap[self.pagesMap.indexOf(self.allPages) - 1] = '...';
                    }
                },
                changePage: function (event) {
                    var self = this;
                    if ($(event.target).parent().hasClass('disabled') === false && $(event.target).hasClass('disabled') === false) {
                        var newPageNumber = $(event.target).attr('data-dt-idx');
                        self.dataShowing = ((newPageNumber < 1) ? 0 : newPageNumber - 1) * parseInt(self.length);
                        self.updateProjects();
                    }
                },
                rowDrow: function (name, DataRow, customFunctionName) {
                    if (window[customFunctionName] === undefined) {
                        return DataRow[name];
                    } else {
                        return window[customFunctionName](name, DataRow);
                    }
                },
                loadModal: function (event, targetId, namespace, index) {
                    mgPageControler.vueLoader.loadM2(event, targetId,
                            typeof namespace !== 'undefined' ? namespace : getItemNamespace(targetId),
                            typeof index !== 'undefined' ? index : getItemIndex(targetId));
                },
                onOffSwitch: function (event, targetId) {
                    var switchPostData = $(event.target).is(':checked') ? {'value': 'on'} : {'value': 'off'};
                    mgPageControler.vueLoader.ajaxAction(event, targetId, getItemNamespace(targetId), getItemIndex(targetId), switchPostData);
                },
                redirect: function (event, params) {
                    mgPageControler.vueLoader.redirect(event, params);
                }
            }
        });
    });

}

function loadComponsnts() {
    Vue.component('mg-modal', {
        template: '#mg-modal-wrapper',
        mounted: function () {
            var self = this;
            $('#mgModalContainer').find('.closeModal').click(function (event) {
                event.preventDefault();
                self.$emit('close');
            });
            $('#mgModalContainer').find('.submitForm').click(function (event) {
                event.preventDefault();
                var self = this;
                mgPageControler.vueLoader.submitForm('mgModalContainer', event);
            });
        },
        props: {
            bodydata: String,
        },
        data: function () {
            return {
            };
        }
    });

    Vue.component('mg-emptyContainer', {
        template: '#mg-emptyBodyContainer',
        data: function () {
            return {
                contentLoading: true
            };
        }
    });

    Vue.component('left-category-menu', {
        template: '#mg-category-menu',
        data: function () {
            return {
                tableWrapperId: 'mg-category-menu',
                returnedData: [],
                targetid: null,
                menuLoading: false,
                sSearch: null,
                dataContent: '',
                showModal: false,
                contentContainerName: 'mg-emptyContainer',
                contentLive: false,
                modalBodyContainer: 'mg-modal-body',
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
            updateMgData: function (toReloadId) {
                var self = this;
                if (self.tableWrapperId === toReloadId) {
                    self.loadCategories(true);
                    self.$nextTick(function () {
                        self.$emit('restartRefreshingState');
                    });
                }
            },
            reloadMenuContent: function (categoryId, namespace, index) {
                var self = this;
                self.contentContainerName = 'mg-emptyContainer';
                var resp = self.$parent.$options.methods.vloadData({loadData: categoryId, namespace: namespace, index: index});
                resp.done(function (data) {
                    data = data.data;
                    $('#mg-templateContainer').html('');
                    self.contentLive = false;
                    self.contentLive = true;
                    $('#groupList').find('li.is-active').removeClass('is-active');
                        self.$nextTick(function() {
                            $('#mg-templateContainer').html(data.htmlData);
                            $('#groupList').find('#'+categoryId).addClass('is-active');
                            self.contentContainerName = 'mg-content-container-body';
                            mgPageControler.vueLoader.$nextTick(function() {
                                $('#itemContentContainer [data-content-slider]').luContentSlider();
                            });

                        });

                });
            },
            searchData: function (event) {
                var self = this;
                self.sSearch = $(event.target).val();
                self.loadCategories();
            },
            loadCategories: function (callback) {
                if (!callback) {
                    callback = false;
                }
                var self = this;
                self.menuLoading = true;
                self.targetid = self.$el.attributes.getNamedItem('targetid').value;
                var reqParams = {loadData: self.targetid, namespace: getItemNamespace(self.targetid), index: getItemIndex(self.targetid)};
                if (self.sSearch !== null) {
                    reqParams.sSearch = self.sSearch;
                }
                var resp = self.$parent.$options.methods.vloadData(reqParams);
                resp.done(function (data) {
                    self.returnedData = data.data.rawData;
                    self.menuLoading = false;
                    if (callback) {
                        self.reloadMenuContent(data.data.rawData[0].elId, data.data.rawData[0].namespace, data.data.rawData[0].id);
                    }

                });
            },
            loadModal: function (event, targetId, namespace, index) {//needs refactoring
                event.stopImmediatePropagation();
                mgPageControler.vueLoader.loadM2(event, targetId, namespace, index);
            }
        }
    });

    $(document).on('keypress', function (event) {
        if (event.keyCode === 13 && event.target.tagName !== 'TEXTAREA') {
            event.preventDefault();
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

}
;

function mgVuePageControler(controlerId) {
    this.baseLoaderUrl = mgUrlParser.getCurrentUrl(),
            this.vueLoader = false,
            this.vinit = function () {
                var cthis = this;
                cthis.vueLoader = new Vue({
                    el: '#' + controlerId,
                    data: {
                        targetId: controlerId,
                        targetUrl: mgUrlParser.getCurrentUrl(),
                        pageLoading: false,
                        returnedData: null,
                        loading: false,
                        loaderComponent: '<div class="row"><i class="dataTables_processing"></i></div>',
                        sSearch: null,
                        showModal: false,
                        htmlContent: '',
                        modalBodyContainer: 'mg-modal-body',
                        refreshingState: null,
                        massActionIds: null,
                        massActionTargetCont: null,
                        pagePreLoader: null,
                        appActionBlockingState: false
                    },
                    created: function () {
                        var self = this;
                        loadComponsnts();
                        loadDatatables();
                        $('#' + self.targetId).removeClass('vhidden');
                        self.$on('restartRefreshingState', self.cleanRefreshActionsState());
                    },
                    methods: {
                        vloadData: function (params) {
                            var self = this;
                            self.refreshUrl();
                            for (var propertyName in params) {
                                self.addUrlComponent(propertyName, params[propertyName]);
                            }
                            self.addUrlComponent('ajax', '1');
                            return $.get(self.targetUrl, function (data) {
                                data = data.data;
                                if (data.callBackFunction && typeof window[data.callBackFunction] === "function") {
                                    window[data.callBackFunction](data);
                                }
                            }, 'json').fail(function () {
                                //self.addAlert('danger', 'Action Failed');
                                console.log('Action Failed');
                            });
                        },
                        addUrlComponent: function ($name, $value) {
                            var self = this;
                            self.targetUrl += (self.targetUrl.indexOf('?') !== -1 ? '&' : '?') + $name + '=' + encodeURIComponent($value);
                        },
                        updateUrlParam: function (key, value, event) {
                            var self = this;
                            value = self.updateValueByAttrs(key, value, event);
                            if (self.targetUrl.indexOf(key) === -1) {
                                self.addUrlComponent(key, value);
                            } else {
                                var baseUrlParts = self.targetUrl.split('?');
                                var currentUrlParams = baseUrlParts[1].split('&');
                                for (i = 0; i < currentUrlParams.length; i++) {
                                    if (currentUrlParams[i].indexOf(key) === 0) {
                                        if (value === '') {
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
                        updateValueByAttrs: function (key, value, event) {
                            if (value.indexOf(':') !== 0) {
                                return value;
                            } else {
                                if ($(event.target).attr('data-' + key)) {
                                    return $(event.target).attr('data-' + key);
                                } else if ($(event.target).parents('a').first().attr('data-' + key)) {
                                    return $(event.target).parents('a').first().attr('data-' + key);
                                } else if ($(event.target).parents('button').first().attr('data-' + key)) {
                                    return $(event.target).parents('button').first().attr('data-' + key);
                                } else {
                                    return value;
                                }
                            }
                        },
                        refreshUrl: function () {
                            var self = this;
                            self.targetUrl = mgUrlParser.getCurrentUrl();
                            if (self.targetUrl.indexOf('#') > 0) {
                                self.targetUrl = self.targetUrl.substr(0, self.targetUrl.indexOf('#'));
                            }
                        },
                        loadModal: function (event, targetId, namespace, index) {
                            var self = this;
                            if (self.appActionBlockingState) {
                                return true;
                            }
                            self.appActionBlockingState = true;
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
                            $.get(self.targetUrl, function (data) {
                                data = data.data;
                                if (data.status === 'success') {
                                    self.htmlContent = data.htmlData;
                                    self.showModal = true;
                                    self.$nextTick(function () {
                                        initColorPickers();
                                        initModalSelects();
                                        initModalTooltips();
                                        initModalActions();
                                        mgfocusOnModal();
                                    });
                                }
                                self.$nextTick(function () {
                                    if (data.callBackFunction && typeof window[data.callBackFunction] === "function") {
                                        window[data.callBackFunction](data, event);
                                    }
                                });
                                self.hideSpinner(event);
                                self.appActionBlockingState = false;

                            }, 'json').fail(function () {
                                //self.addAlert('danger', 'Action Failed');
                                console.log('Action Failed');
                                self.hideSpinner(event);
                                self.appActionBlockingState = false;
                            });
                            self.refreshUrl();
                        },
                        loadM2: function (event, targetId, namespace, index) {
                            var self = this;
                            self.loadModal(event, targetId, namespace, index);
                        },
                        showSpinner: function (event) {
                            var self = this;
                            var spinnerTarget = self.getSpinerTarget(event);
                            if ($(event.target)[0].tagName === 'IMG' || $(event.target).attr('class') == "lu-tile__title") {
                                var elWidth = $(spinnerTarget).width();
                                $(spinnerTarget).attr('originall-button-content', spinnerTarget.html());
                                $(spinnerTarget).html('<span id="mg-img-' + String(event.timeStamp).replace('.', '-') + '" class="lu-btn__icon temp-button-loader" style="margin: 0 0 0 0 !important; width: ' + elWidth + 'px;"><i class="lu-preloader lu-preloader--sm"></i></span>');
                            } else if (spinnerTarget.length > 0 || $(spinnerTarget).tagName === 'I') {
                                var isBtnIcon = $(spinnerTarget).hasClass('lu-btn--icon');
                                $(spinnerTarget).attr('originall-button-icon', $(spinnerTarget).attr('class'));
                                $(spinnerTarget).removeAttr('class');
                                $(spinnerTarget).attr('class', (isBtnIcon ? 'lu-btn--icon ' : '') + 'lu-preloader lu-preloader--sm');
                            } else {
                                self.addSpinner(event);
                            }
                        },
                        hideSpinner: function (event) {
                            var self = this;
                            var spinnerTarget = self.getSpinerTarget(event);
                            if ($(event.target).attr('originall-button-content')) {
                                self.removeSpinner(event);
                            } else if (spinnerTarget.length > 0 || $(spinnerTarget).tagName === 'I') {
                                $(spinnerTarget).removeAttr('class');
                                $(spinnerTarget).addClass($(spinnerTarget).attr('originall-button-icon'));
                                $(spinnerTarget).removeAttr('originall-button-icon');
                            } else {
                                var newTarget = $('#mg-img-' + String(event.timeStamp).replace('.', '-')).closest('a');;
                                if (newTarget.length > 0) {
                                    $(newTarget).html($(newTarget).attr('originall-button-content'));
                                    $(newTarget).removeAttr('originall-button-content');
                                }

                            }
                        },
                        removeSpinner: function (event) {
                            $(event.target).html($(event.target).attr('originall-button-content'));
                            $(event.target).removeAttr('originall-button-content');
                        },
                        addSpinner: function (event) {
                            var elWidth = $(event.target).width();
                            var spinnerClass = $(event.target).hasClass('lu-btn--success') ? 'lu-preloader-success' : ($(event.target).hasClass('lu-btn--danger') ? 'lu-preloader-danger' : '');
                            $(event.target).attr('originall-button-content', $(event.target).html());
                            $(event.target).html('<span class="lu-btn__icon temp-button-loader" style="margin: 0 0 0 0 !important; width: ' + elWidth + 'px;"><i class="lu-preloader lu-preloader--sm ' + spinnerClass + '"></i></span>');
                        },
                        getSpinerParent: function (event) {
                            if ($(event.target)[0].tagName === 'A' || $(event.target)[0].tagName === 'BUTTON') {
                                return $(event.target)[0];
                            } else if ($(event.target)[0].parents('button').first()) {
                                return $(event.target)[0].parents('button').first();
                            } else if ($(event.target)[0].parents('a').first()) {
                                return $(event.target)[0].parents('a').first();
                            } else {
                                return null;
                            }
                        },
                        getSpinerTarget: function (event) {
                            if ($(event.target)[0].tagName === 'IMG') {
                                return $(event.target).closest('a');
                            } else if ($(event.target).attr('class') == "lu-tile__title") {
                                return $(event.target).closest('a');
                            } else if ($(event.target)[0].tagName === 'I') {
                                return $(event.target);
                            } else if ($(event.target)[0].tagName === 'SPAN') {
                                var aParents = $(event.target).parents('a');
                                return aParents.length === 0 ? $(event.target).parents('button').first().find('i').first() : $(event.target).parents('a').first().find('i').first();
                            } else if ($(event.target)[0].tagName === 'A') {
                                return $(event.target).find('i').first();
                            } else if ($(event.target)[0].tagName === 'BUTTON') {
                                return $(event.target).find('i').first();
                            } else {
                                return null;
                            }
                        },
                        initMassActions: function (event) {
                            var self = this;
                            self.cleanMassActions();
                            if ($(event.target).parents('.lu-t-c__mass-actions').length === 0)
                            {
                                return;
                            }
                            self.addUrlComponent('isMassAction', '1');
                            var tableContainer = $(event.target).parents('.vueDatatableTable').first().attr('id');
                            self.massActionTargetCont = tableContainer;
                            self.massActionIds = collectTableMassActionsData(tableContainer);
                        },
                        addMassActionsToData: function (formData) {
                            var self = this;
                            if (self.massActionIds) {
                                formData.massActions = self.massActionIds;
                                return formData;
                            } else {
                                return formData;
                            }
                        },
                        cleanMassActions: function () {
                            var self = this;
                            if (self.massActionIds || self.massActionTargetCont) {
                                self.massActionIds = null;
                                //uncheckSelectAllCheck(self.massActionTargetCont);
                                self.$nextTick(function () {
                                    self.massActionTargetCont = null;
                                });
                            }
                        },
                        initRefreshActions: function (event, targetId) {
                            var self = this;
                            var fileManager = ['createDirectoryButton', 'uploadButton'];
                            if (fileManager.indexOf(targetId) > -1)
                            {
                                self.refreshingState = 'fileManagerPage';
                                return;
                            }
                            var menuReloading = ['addCategoryButton', 'editCategoryButton', 'deleteCategoryButton'];
                            if (menuReloading.indexOf(targetId) > -1)
                            {
                                self.refreshingState = 'mg-category-menu';
                                return;
                            }
                            var tableContainer = $(event.target).parents('.vueDatatableTable').first();
                            self.refreshingState = $(tableContainer).attr('id');
                        },
                        runRefreshActions: function () {
                            var self = this;
                            if (self.refreshingState !== null) {
                                self.$nextTick(function () {
                                    self.$emit('reloadMgData', self.refreshingState);
                                });
                            }
                        },
                        cleanRefreshActionsState: function () {
                            var self = this;
                            self.refreshingState = null;
                        },
                        getActionId: function (event) {
                            var self = this;
                            var tableActions = $(event.target).parents("td.mgTableActions");
                            var widgetActionComponent = $(event.target).parents("div.widgetActionComponent");
                            var dropdownComponent = $(event.target).parents("ul.dropdown__menu");
                            if (tableActions.length === 1) {
                                var row = $(tableActions[0]).parent('tr');
                                var actionElementId = row.attr("actionid");
                                if (actionElementId) {
                                    self.addUrlComponent('actionElementId', actionElementId);
                                }
                            } else if (widgetActionComponent.length === 1) {
                                var actionElementId = $(widgetActionComponent[0]).attr("actionid");
                                if (actionElementId) {
                                    self.addUrlComponent('actionElementId', actionElementId);
                                }
                            } else if (dropdownComponent.length === 1) {
                                var actionElementId = dropdownComponent.attr("actionid");
                                if (actionElementId) {
                                    self.addUrlComponent('actionElementId', actionElementId);
                                }
                            }
                        },
                        submitForm: function (targetId, event) {
                            var self = this;
                            if ($('#' + targetId).find('form').attr('action') !== undefined) {
                                self.showSpinner(event);
                                $('#' + targetId).find('form').first().submit();
                            } else {
                                var formTargetId = ($('#' + targetId)[0].tagName === 'FORM') ? targetId : $('#' + targetId).find('form').attr('id');
                                if (formTargetId) {
                                    self.showSpinner(event);
                                    var formCont = new mgFormControler(formTargetId);
                                    var formData = formCont.getFieldsData();
                                    formData = self.addMassActionsToData(formData);
                                    self.refreshUrl();
                                    self.addUrlComponent('loadData', formTargetId);
                                    self.addUrlComponent('namespace', getItemNamespace(formTargetId));
                                    self.addUrlComponent('index', getItemIndex(formTargetId));
                                    self.addUrlComponent('ajax', '1');
                                    self.addUrlComponent('mgFormAction', $('#' + formTargetId).attr('mgformaction'));
                                    if (formCont.upload === true)
                                    {
                                        self.submitFormWithUpload(formCont, formData, event);
                                    } else
                                    {
                                        $.post(self.targetUrl, formData, {},'json')
                                                .done(function (data) {
                                                    data = data.data;
                                                    self.hideSpinner(event);
                                                    self.$nextTick(function () {
                                                        if (data.callBackFunction && typeof window[data.callBackFunction] === "function") {
                                                            window[data.callBackFunction](data, event);
                                                        }
                                                    });
                                                    if (data.status === 'success') {
                                                        self.showModal = false;
                                                        self.runRefreshActions();
                                                        self.cleanMassActions();
                                                        self.addAlert(data.status, data.message);
                                                        formCont.updateFieldsValidationMessages([]);
                                                    } else if (data.rawData !== undefined && data.rawData.FormValidationErrors !== undefined) {
                                                        formCont.updateFieldsValidationMessages(data.rawData.FormValidationErrors);
                                                    } else if (data.htmlData !== undefined && data.htmlData.refreshState){
                                                        self.addAlert(data.status, data.message);
                                                        self.showModal = false;
                                                        uncheckSelectAllCheck(self.tableWrapperId);
                                                        window['refreshTable'](data);
                                                     } else {
                                                        formCont.updateFieldsValidationMessages([]);
                                                        self.addAlert(data.status, data.message);
                                                    }
                                                });
                                    }
                                } else {
                                    //todo error reporting
                                }
                            }
                        },
                        submitFormWithUpload: function (formCont, formData, event)
                        {
                            var self = this;
                            var data = formCont.loadFormFieldsForUpload(formData.formData);
                            $.ajax({
                                url: self.targetUrl,
                                type: "POST",
                                data: data,
                                processData: false,
                                contentType: false,
                                dataType: 'json'
                            }).done(function (data) {
                                data = data.data;
                                self.hideSpinner(event);
                                self.$nextTick(function () {
                                    if (data.callBackFunction && typeof window[data.callBackFunction] === "function") {
                                        window[data.callBackFunction](data, event);
                                    }
                                });
                                if (data.status === 'success') {
                                    self.showModal = false;
                                    self.runRefreshActions();
                                    self.cleanMassActions();
                                    self.addAlert(data.status, data.message);
                                    formCont.updateFieldsValidationMessages([]);
                                } else if (data.rawData !== undefined && data.rawData.FormValidationErrors !== undefined) {
                                    formCont.updateFieldsValidationMessages(data.rawData.FormValidationErrors);
                                } else {
                                    formCont.updateFieldsValidationMessages([]);
                                    self.addAlert(data.status, data.message);
                                }
                            });
                        },
                        ajaxAction: function (event, targetId, namespace, index, postData) {
                            var self = this;
                            self.refreshUrl();
                            self.initRefreshActions(event, targetId);
                            self.addUrlComponent('loadData', targetId);
                            self.addUrlComponent('namespace', namespace);
                            self.addUrlComponent('index', index);
                            self.getActionId(event);
                            self.addUrlComponent('ajax', '1');
                            $.post(self.targetUrl, postData, {},'json')
                                    .done(function (data) {
                                        data = data.data;
                                        self.addAlert(data.status, data.message);
                                        self.$nextTick(function () {
                                            if (data.callBackFunction && typeof window[data.callBackFunction] === "function") {
                                                window[data.callBackFunction](data, event);
                                            }
                                        });
                                        if (data.status === 'success') {

                                        }
                                    }, 'json');
                            self.refreshUrl();
                        },
                        updateSorting: function (order, loadData, namespace)
                        {
                            var self = this;

                            self.refreshUrl();
                            self.addUrlComponent('loadData', loadData);
                            self.addUrlComponent('namespace', namespace);
                            self.addUrlComponent('ajax', '1');
                            self.addUrlComponent('mgFormAction', "reorder");

                            var formData = {order: order}
                            $.post(self.targetUrl, {formData: formData}, {},'json')
                                .done(function (data){
                                data = data.data;
                                self.addAlert(data.status, data.message);
                                self.pageLoading = false;
                                self.$nextTick(function () {
                                    if (data.callBackFunction && typeof window[data.callBackFunction] === "function") {
                                        window[data.callBackFunction](data);
                                    }
                                });
                                if (data.status === 'success')
                                {
                                    //Dispaly alert?
                                } else
                                {
                                    //TODO: Dispaly alert
                                }
                            });
                        },
                        addAlert: function (type, message) {
                            type = (type === 'error') ? 'danger' : type;
                            layers.alert.create({
                                $alertPosition: 'right-top',
                                $alertStatus: type,
                                $alertBody: message,
                                $alertTimeout: 10000
                            });
                        },
                        makeCustomActiom: function (functionName, params, event, namespace, index) {
                            var self = this;
                            if (typeof window[functionName] === "function")
                            {
                                window[functionName](self, params, event, namespace, index);
                            }

                        },
                        redirect: function (event, params) {
                            var self = this;
                            var tempUrl = self.targetUrl;
                            if (params.rawUrl !== undefined) {
                                self.targetUrl = params.rawUrl;
                            }
                            if (params.actionElementId !== undefined) {
                                self.getActionId(event);
                            }
                            $.each(params, function (key, value) {
                                if (key === 'rawUrl' || key === 'actionElementId') {
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
}
;

function mgFormControler(targetFormId) {
    this.fields = null;
    this.upload = false;
    this.data = {};
    this.formId = targetFormId;

    this.loadFormFields = function () {
        var that = this;

        jQuery('#' + this.formId).find('input,select,textarea').each(function () {
            if (!jQuery(this).is(':disabled')) {
                var name = jQuery(this).attr('name');

                var value = null;

                if (name !== undefined) {
                    var type = jQuery(this).attr('type');
                    var regExp = /([a-zA-Z_0-9]+)\[([a-zA-Z_0-9]+)\]/g;
                    var regExpLg = /([a-zA-Z_0-9]+)\[([a-zA-Z_0-9]+)\]\[([a-zA-Z_0-9]+)\]/g;

                    if (type === 'checkbox') {
                        var value = 'off';
                        jQuery('#' + that.formId).find('input[name="' + name + '"]').each(function () {
                            if (jQuery(this).is(':checked')) {
                                value = jQuery(this).val();
                            }
                        });
                    } else if (type === 'file') {
                        that.upload = true;
                        var value = jQuery(this)[0].files[0];
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
                        } else if (result = regExp.exec(name)) {
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
    },
            this.loadFormFieldsForUpload = function (data) {
                var formData = new FormData;
                $.each(data, function (key, value) {
                    if (key !== 'action')
                    {
                        formData.append(key, value);
                    }
                });

                return formData;
            };

    this.getFieldsData = function () {
        this.loadFormFields();

        return {formData: this.data};
    };

    this.updateFieldsValidationMessages = function (errorsList) {
        jQuery('#' + this.formId).find('input,select,textarea').each(function () {
            if (!jQuery(this).is(':disabled')) {
                var name = jQuery(this).attr('name');
                if (name !== undefined && errorsList[name] !== undefined)
                {
                    if (!jQuery(this).parents('.lu-form-group').first().hasClass('lu-is-error')) {
                        jQuery(this).parents('.lu-form-group').first().addClass('lu-is-error');
                    }

                    var messagePlaceholder = jQuery(this).parents('.lu-form-group').first().children('.lu-form-feedback');
                    if (jQuery(messagePlaceholder).length > 0)
                    {
                        jQuery(messagePlaceholder).html(errorsList[name].slice(-1)[0]);
                        if (jQuery(messagePlaceholder).attr('hidden')) {
                            jQuery(messagePlaceholder).removeAttr('hidden');
                        }
                    }
                } else if (name !== undefined) {
                    if (jQuery(this).parents('.lu-form-group').first().hasClass('lu-is-error')) {
                        jQuery(this).parents('.lu-form-group').first().removeClass('lu-is-error');
                    }
                    var messagePlaceholder = jQuery(this).next('.lu-form-feedback');
                    if (jQuery(messagePlaceholder).length > 0) {
                        jQuery(messagePlaceholder).html('');
                        if (!jQuery(messagePlaceholder).attr('hidden')) {
                            jQuery(messagePlaceholder).attr('hidden', 'hidden');
                        }
                    }
                }
            }
        });
    };
}
;



// CUSTOM FUNCTIONS

//this is example custom action, use it for non-ajax actions
function custAction1(vueControler, params, event) {
    console.log('custAction1', vueControler, params, event);
}

//this is example custom action, use it for ajax actions
function custAction2(vueControler, params, event) {
    console.log('custAction2', vueControler, params, event);
}

function mgEmptyToPause(name, row) {
    if (!row[name] || row[name] === '') {
        return '-';
    } else {
        return row[name];
    }
}

function download(vueControler, params, event)
{
    var link = $($(event.target).parents('tr').first().attr('actionid')).attr('data-link');
    //window.location.assign(link);
    window.open(link);
    vueControler.updateProjects();
}

function showMxRecords(vueControler, params, event)
{
    if (window.location.href.indexOf('#') !== -1)
    {
        var clearUrl = window.location.href.substr(0, window.location.href.indexOf('#'));
    } else
    {
        var clearUrl = window.location.href;
    }
    var domain = $(event.target).parents('tr').first()[0].textContent;
    var url = clearUrl + '&mg-action=recordTable&domain=' + domain;
    var params = {
        rawUrl: url,
        actionElementId: domain

    };

    vueControler.redirect(event, params);
}

function showDelegatesList(vueControler, params, event)
{
    if (window.location.href.indexOf('#') !== -1)
    {
        var clearUrl = window.location.href.substr(0, window.location.href.indexOf('#'));
    } else
    {
        var clearUrl = window.location.href;
    }
    var list = $(event.target).parents('tr').first().attr('actionid');
    var url = clearUrl + '&mg-action=showDelegatesList&list=' + list;
    var params = {
        rawUrl: url,
    };

    vueControler.redirect(event, params);
}

function downloadPartial(vueControler, params, event)
{
    var link = $(event.target).parents('a').first().attr('data-link');
    // window.location.assign(link);
    window.open(link);
}

function disableFieldByName(vueControler, params, event)
{
//    console.log(event);
}

function calculatePasswordStr()
{
    var input = $('input[name="password"]');
    var result = zxcvbn(input.val());
    var score = parseInt(result.score);
    var score_to_color = ['#c9302c', '#d9534f', '#ec971f', '#5cb85c', '#449d44'];
    input.parent().css('border-bottom', '2px solid' + score_to_color[score]);
}

function generateRandomPassword() {
    var length = 12,
            charset = "abcdefghijklnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()",
            password = "";
    var input = $('input[name="password"]');
    for (var i = 0, n = charset.length; i < length; ++i) {
        password += charset.charAt(Math.floor(Math.random() * n));
    }

    input.val(password);
    input.parent().css('border-bottom', '2px solid #449d44');
}

function fileManagerNextPage(vueControler, params, event)
{
    vueControler.loading = true;
    if (window.location.href.indexOf('#') !== -1)
    {
        var clearUrl = window.location.href.substr(0, window.location.href.indexOf('#'));
    } else
    {
        var clearUrl = window.location.href;
    }

    var url = clearUrl + '&mg-action=updateCurrentPathNext';
    $.post(url, {path: params[0]}, {},'json')
        .done(function (data) {
        vueControler.updateProjects(params[0]);
    });

}

function fileManagerPageBack(vueControler, params, event)
{
    vueControler.loading = true;
    if (window.location.href.indexOf('#') !== -1)
    {
        var clearUrl = window.location.href.substr(0, window.location.href.indexOf('#'));
    } else
    {
        var clearUrl = window.location.href;
    }
    var url = clearUrl + '&mg-action=updateCurrentPathBack';
    $.post(url, {}, {},'json')
        .done(function (data) {
        vueControler.updateProjects();
    });

}

function fileManagerHomePage(vueControler, params, event)
{
    vueControler.loading = true;
    if (window.location.href.indexOf('#') !== -1)
    {
        var clearUrl = window.location.href.substr(0, window.location.href.indexOf('#'));
    } else
    {
        var clearUrl = window.location.href;
    }
    var url = clearUrl + '&mg-action=updateToHomeDir';
    $.post(url, {}, {},'json')
        .done(function (data) {
        vueControler.updateProjects();
    });

}

function fileManagerDownloadFile(vueControler, params, event, namespace, index)
{
    var targetId = params[0];
    var fileName = $(event.target).attr('name');
    var vueControler = mgPageControler.vueLoader;
    vueControler.refreshUrl();
    vueControler.initRefreshActions(event, targetId);
    vueControler.addUrlComponent('loadData', targetId);
    vueControler.addUrlComponent('namespace', namespace);
    vueControler.addUrlComponent('index', index);
    vueControler.getActionId(event);
    vueControler.addUrlComponent('ajax', '1');
    vueControler.showSpinner(event);
    $.post(vueControler.targetUrl, {name: fileName}, {},'json')
        .done(function (data) {
        data = data.data
        var linkToDownload = data.data;
        //window.location.assign(linkToDownload);
        window.open(linkToDownload);
        vueControler.hideSpinner(event);
        vueControler.addAlert(data.status, data.message);
    });
}

function setCronSelects(event)
{
    var options = $(event.target).val().split(' ');
    var selects = $(event.target).parent().parent().find('select');
    if ($(event.target).val() !== '--')
    {
        $.each(selects, function (key, select) {
            $(select[0]).val(options[(key - 1)]);
            if (key !== 0)
            {
                $($(select).parent().find('div')[0]).attr('disabled', 'disabled');
                //$($(select).parent().find('div').parent()).hide();
            }
        });
    } else
    {
        $.each(selects, function (key, select) {
            $(select[0]).val(options[(key - 1)]);
            if (key !== 0)
            {
                $($(select).parent().find('div')[0]).removeAttr('disabled');
                // $($(select).parent().find('div').parent()).show();
            }
        });
    }

}

function fillWithMe(inputs, event)
{
    var text = $(event.target).val();
    $.each(inputs, function (key, input) {
        if (input.name == 'subdomain')
        {
            var textArr = text.split('.');
            $(input).val(textArr[0]);
        } else
        {
            $(input).val(text);
        }

    });
    // need change
    $('input[name="dir"]').val('/public_html/' + text);
}

function oneClickLoginRedirect(vueControler, params, event)
{
    event.preventDefault();
    window.open(params[0]);
}

function reverseHideByName(name, event)
{
    var isChecked = event.target.checked;
    if (isChecked === true)
    {
        $($('input[name="' + name + '"]').parent()).hide();
        hideInput = true;
    } else
    {
        $($('input[name="' + name + '"]').parent()).show();
        hideInput = false;
    }
}

function hideInputByName(name, event, reverse)
{
    var isChecked = event.target.checked;
    if (reverse)
    {
        if (isChecked === false)
        {
            $($($('input[name="' + name + '"]').parent().parent())[0]).css('display', 'none');
            hideInput = true;
        } else
        {
            $($($('input[name="' + name + '"]').parent().parent())[0]).css('display', 'inline')
            hideInput = false;
        }
    } else

    if (isChecked === true)
    {
        $($('input[name="' + name + '"]').parent()).show();
        hideInput = false;
    } else
    {
        $($('input[name="' + name + '"]').parent()).hide();
        hideInput = true;
    }
}

function showCustomInput(inputName, valueForChange, event)
{
    var inputValue = event.target.value;
    if (inputValue == valueForChange)
    {
        $($($('input[name="' + inputName + '"]').parent())[0]).css('display', 'inline');
    } else
    {
        $($($('input[name="' + inputName + '"]').parent())[0]).css('display', 'none');
    }
}

function redirectAfterInstallApp()
{
    var urlArray = window.location.href.split('&');
    urlArray.forEach(function (val, key) {
        if (String(val).indexOf('mg-action=') === 0 || String(val).indexOf('ver=') === 0 || String(val).indexOf('sid=') === 0)
        {
            delete urlArray[key];
        }
    });

    var newUrl = $.grep(urlArray, function (n) {
        return(n);
    }).join('&');
    if (newUrl.indexOf('#') !== -1)
    {
        newUrl = window.location.href.substr(0, window.location.href.indexOf('#'));
    }

    window.location.href = newUrl;
}

$('[id*="Primary_Sidebar-mg-oneclick"]').click(function (event) {
    event.preventDefault();
    var url = event.target.href;
    window.open(url);
});

function refreshTable(data)
{
    mgPageControler.vueLoader.refreshingState = data.htmlData.refreshState;
    mgPageControler.vueLoader.runRefreshActions();
}

function vpsActions(vueObj, params, event, namespace, index) {

    vueObj.showSpinner(event);
    vueObj.refreshUrl();
    vueObj.addUrlComponent('loadData', index);
    vueObj.addUrlComponent('namespace', namespace);
    vueObj.addUrlComponent('index', index);
    vueObj.addUrlComponent('ajax', '1');

    var data = params;
    $.post(vueObj.targetUrl, data, {},'json')
            .done(function (data) {
                var data = data.data;
                vueObj.hideSpinner(event);
                vueObj.addAlert(data.status, data.message);
                if (data.htmlData) {
                    if (typeof window[data.callBackFunction] == "function") {
                        window[data.callBackFunction](data.htmlData.refreshState);
                    }
                }
            }
            );
}


function hideInputIfValue(event)
{
    var selectedValue = event.target.value;

    if(selectedValue === 'icmp')
    {
        $($('input[name="port"]').parent()).hide();
    }
    else
    {
        $($('input[name="port"]').parent()).show();
    }
}

function updateValuesAfterAppChange() {
    let el = document.getElementsByName('apps')[0];

    let possibleVals = {
        alltcp: {protocol: 'tcp', ports: 'all'},
        alludp: {protocol: 'udp', ports: 'all'},
        ssh: {protocol: 'tcp', ports: 22},
        http: {protocol: 'tcp', ports: 80},
        https: {protocol: 'tcp', ports: 443},
        mysql: {protocol: 'tcp', ports: 3306},
        dnstcp: {protocol: 'tcp', ports: 53},
        dnsudp: {protocol: 'udp', ports: 53},
        icmp: {protocol: 'icmp', ports: ''},
        custom: {protocol: 'tcp', ports: ''}
    };

    let val = el.options[el.selectedIndex].value;
    let protocolSelect = document.getElementsByName('protocol')[1];

    protocolSelect.options[0].value = possibleVals[val].protocol;

    let visualRepresentationItem = null;
    let protocols = ['icmp','tcp','udp'];
    for(let i in protocols)
    {
        visualRepresentationItem = document.querySelector(`div.item[data-value="${protocols[i]}"]`);
        if(visualRepresentationItem)
        {
            visualRepresentationItem.innerHTML = possibleVals[val].protocol.toUpperCase();
            visualRepresentationItem.setAttribute('data-value', possibleVals[val].protocol);
        }
    }

    document.getElementsByName('port')[0].value = possibleVals[val].ports;
}
