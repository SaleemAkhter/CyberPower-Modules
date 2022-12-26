/*
* Core js fw functions
* Do not edit this file 
*/

/* 
 * Set body Id for Layers js/css
 */
$('body').attr('id', 'layers-body');


/* 
 * Init app on page loaded (supports ie11+)
 */
function mgLoadPageContoler(){
    new Promise(function(resolve, reject) {
        var ret = mgJsComponentHandler.registerComponents();
        if (ret || !ret) {
            resolve(ret);
        }
    }).then(function(resault) {
        var appContainers = document.getElementsByClassName("vue-app-main-container");
        for (var i = 0; i < appContainers.length; i++) {
            if(appContainers[i].getAttribute('mg-module') == 'hetznerVps'){
                ret = mgEventHandler.runCallback('AppsPreLoad', null, {appContainers: {0: appContainers[i].id}});
                return ret;
            }
        }
    }).then(function(resault) {
        var appContainers = document.getElementsByClassName("vue-app-main-container");
        for (var i = 0; i < appContainers.length; i++) {
            if(appContainers[i].getAttribute('mg-module') == 'hetznerVps') {
                mgPageControler = new mgVuePageControler(appContainers[i].id);
                mgPageControler.vinit();
            }

        }
    });
};


/* 
 * Url Helper
 */
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

/*
 * A redirect function for provisioning product configuration page
 */
function redirectToConfigurableOptions()
{
    var url = mgUrlParser.oldMgUrlParser.getCurrentUrl() + '&tab=5';
    url = url.replace("&tab=3", "").replace("&success=true", "");
    window.location.replace(url);
}

document.addEventListener('readystatechange',  function (event) {
    if (document.readyState === "interactive" ) {
        document.removeEventListener('readystatechange', this);
        mgLoadPageContoler();
        
    }
});
