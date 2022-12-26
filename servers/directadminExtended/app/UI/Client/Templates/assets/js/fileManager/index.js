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
    $.post(url, {path: params[0]}).done(function (data) {
        vueControler.updateProjects(params[0]);
    });

}

function fileManagerPageBack(vueControler, params, event)
{
    event.preventDefault();
    vueControler.loading = true;
    if (window.location.href.indexOf('#') !== -1)
    {
        var clearUrl = window.location.href.substr(0, window.location.href.indexOf('#'));
    } else
    {
        var clearUrl = window.location.href;
    }
    var url = clearUrl + '&mg-action=updateCurrentPathBack';
    $.post(url, {}).done(function (data) {
        vueControler.updateProjects();
    });

}

function fileManagerPageBackTo(vueControler, params, event)
{
    event.preventDefault();
    vueControler.loading = true;
    if (window.location.href.indexOf('#') !== -1)
    {
        var clearUrl = window.location.href.substr(0, window.location.href.indexOf('#'));
    } else
    {
        var clearUrl = window.location.href;
    }
    var url = clearUrl + '&mg-action=updateCurrentPathBackTo';
    $.post(url, {params: params}).done(function (data) {
        vueControler.updateProjects();
    });

}

function fileManagerHomePage(vueControler, params, event)
{
    event.preventDefault();
    vueControler.loading = true;
    if (window.location.href.indexOf('#') !== -1)
    {
        var clearUrl = window.location.href.substr(0, window.location.href.indexOf('#'));
    } else
    {
        var clearUrl = window.location.href;
    }
    var url = clearUrl + '&mg-action=updateToHomeDir';
    $.post(url, {}).done(function (data) {
        vueControler.updateProjects();
    });

}

function fileManagerDownloadFile(vueControler,params, event,namespace,index)
{
    if (window.location.href.indexOf('#') !== -1)
    {
        var clearUrl = window.location.href.substr(0, window.location.href.indexOf('#'));
    } else
    {
        var clearUrl = window.location.href;
    }
    let fileName;

    if(event.originalTarget === undefined)
    {
        fileName = $(event)[0].srcElement.innerText;
    }
    else
    {
        fileName = event.originalTarget.innerText;
    }

    var url = clearUrl + '&mg-action=getDownloadLink&file=' + fileName;
    window.open(url);
}