<div class="lu-col-md-12">
    {include file='assets/css_assets.tpl'}

    {if $isCustomIntegrationCss}
        <link rel="stylesheet" href="{$customAssetsURL}/css/integration.css">
    {/if}

    <div id="layers">
        <div class="lu-app">
            <div class="lu-app-main">
                <div class="lu-app-main__body">
                    {$content}
                </div>
            </div>
        </div>
    </div>

    {include file='assets/js_assets.tpl'}

    <script>
        if (typeof whmcsPostOriginal === 'undefined') {
            var whmcsPostOriginal = {};
            Object.assign(whmcsPostOriginal, WHMCS.http.jqClient);
            function whmcsPostWrapper(t, e, i, n) {
                if (typeof e === "string" && (e.indexOf('modop=suspend') > 0 || e.indexOf('modop=changepackage') > 0 || e.indexOf('modop=unsuspend') > 0
                    || e.indexOf('modop=terminate') > 0 || e.indexOf('modop=create') > 0)) {
                    mgPageControler.vueLoader.$destroy();
                    mgPageControler.vueLoader = false;
                }

                return whmcsPostOriginal.post(t, e, i, n);
            }

            WHMCS.http.jqClient.post = whmcsPostWrapper;
        }

        function mgWaitForAssets(){
            setTimeout(function(){
                if (typeof window.Vue === 'function' && typeof window.mgLoadPageContoler === 'function'
                    && typeof window.initMassActionsOnDatatables === 'function') {
                    if (mgPageControler.vueLoader === false) {
                        mgLoadPageContoler();
                        mgEventHandler.on('AppCreated', null, function (appId, params) {
                            params.instance.$nextTick(function () {
                                initContainerTooltips('layers');
                            });
                        }, 1000);
                    }
                } else {
                    mgWaitForAssets();
                }
            }, 1000);
        }
        mgWaitForAssets();
    </script>
</div>
