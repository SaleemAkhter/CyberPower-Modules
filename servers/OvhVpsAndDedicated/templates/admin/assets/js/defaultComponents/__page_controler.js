/*
* Core js fw functions
* Do not edit this file 
*/

/* 
 * Js page controler
 */
function mgVuePageControler(controlerId) {
    this.baseLoaderUrl = mgUrlParser.getCurrentUrl();
    
    //main app container id
    this.vueLoaderId = controlerId;
    //main app instance
    this.vueLoader = false,
            
    //main app instance init        
    this.vinit = function() {
        var self = this;
        self.vueLoader =  new Vue(self.getVueAppInits());
    },
    
    //prepare main app config object
    this.getVueAppInits = function() {
        var vAppId =  this.vueLoaderId;
        var newVueAppConfig = mgDefauleVueObject;
        
        newVueAppConfig.el = '#' + vAppId;
        newVueAppConfig.data.targetId = vAppId;
        newVueAppConfig.data.targetUrl = mgUrlParser.getCurrentUrl();

        return newVueAppConfig;
    },
    
    //modal data
    this.modalData = {};
    //modal instance
    this.modalInstance = null;
    //modal app container id
    this.modalAppContainerId = controlerId + '_modal'; 
    
    //load modal
    this.initModal = function(id, namespace, index, event, dataLoaded){
        var self = this;
        self.modalData.id = id;
        self.modalData.namespace = namespace;
        self.modalData.index = index;
        self.modalData.event = event;
        self.modalData.dataLoaded = dataLoaded.htmlData;
        
        jQuery('#' + self.modalAppContainerId).html(dataLoaded.htmlData);
        if (typeof dataLoaded.registrations !== 'undefined') {
            $('#loadedTemplates').html(dataLoaded.template);
            for (var key in dataLoaded.registrations) {
                if (!dataLoaded.registrations.hasOwnProperty(key)) {
                    continue;
                }
                mgJsComponentHandler.registerNowByDefaultTemplate(key.toLowerCase(), dataLoaded.registrations[key]);
            }
        }
        
        self.modalInstance =  new Vue(self.getVueModalAppInits());

        mgEventHandler.runCallback('ModalLoaded', self.modalData.id, {containerId: self.modalAppContainerId, modalInstance: self.modalInstance});
    },
        
    this.destructApp = function() {
        var self = this;
        self.vueLoader.$destroy();
        self.vueLoader = null;
        self = null;        
    }              
            
    this.destructModal = function() {
        var self = this;
        var modalId = self.modalData.id;

        self.modalInstance.$destroy();
        self.modalInstance = null;

        //unset modal data
        self.modalData.id = null;
        self.modalData.namespace = null;
        self.modalData.index = null;
        self.modalData.event = null;
        self.modalData.dataLoaded = null;

        //remove old modal content
        jQuery('#' + self.modalAppContainerId).html('');

        mgEventHandler.runCallback('ModalClosed', modalId, {});
    }

    this.reloadVueModal = function() {
        $('#mgModalContainer').append('<div class="lu-preloader-container lu-preloader-container--full-screen lu-preloader-container--overlay" v-show="1"><div class="lu-preloader lu-preloader--sm"></div></div>');
        var self = this;
        var tempData = self.modalData;
        self.modalInstance.$destroy();
        
        self.vueLoader.reloadModalContent(tempData.event, tempData.id, tempData.namespace, tempData.index, null);
    };

    this.getVueModalAppInits = function () {
        var self = this;
        var vmAppId =  self.modalAppContainerId;
        var newVuemAppConfig = mgDefauleVueModalObject;
        
        newVuemAppConfig.el = '#' + vmAppId;
        newVuemAppConfig.data.targetId = self.modalData.id;
        newVuemAppConfig.data.targetUrl = mgUrlParser.getCurrentUrl();
                       
        return newVuemAppConfig;        
    };
    
    this.initModalAdditions = function(){
        initModalSelects();
        initModalTooltips();
    };
}
