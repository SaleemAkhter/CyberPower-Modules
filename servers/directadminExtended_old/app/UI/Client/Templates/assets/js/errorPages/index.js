function viewPageContent(vue, params, event, namespace, index)
{
    const pageId = index;
    const vueObj = mgPageControler.vueLoader;
    vueObj.showSpinner(event);
    vueObj.refreshUrl();
    vueObj.addUrlComponent('loadData', index);
    vueObj.addUrlComponent('namespace', namespace);
    vueObj.addUrlComponent('ajax', '1');

    var data = params;
    var response;
    data.pageId = pageId;
    $.ajax({
        url: vueObj.targetUrl,
        data: data,
        method: 'POST',
        async:false
    }).done(function (data) {
            response = data.data;
            vueObj.hideSpinner(event);
            if (data.rawData) {
                if (typeof window[data.callBackFunction] == "function") {
                    window[data.callBackFunction](data.rawData.refreshState);
                }
            }
            window.open().document.write(response.rawData.content);
        }
    );
}