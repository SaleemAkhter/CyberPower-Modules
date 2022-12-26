//replace url wrapper
var mgUrlParser = {
    oldMgUrlParser: mgUrlParser,

    getCurrentUrl: function () {
        var url = this.oldMgUrlParser.getCurrentUrl();
        return url.replace("action=edit", "action=module-settings").replace("&success=true", "");
    }
};