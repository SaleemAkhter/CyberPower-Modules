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