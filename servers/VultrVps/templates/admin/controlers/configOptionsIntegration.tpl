{if !$mgWhmcsVersionComparator->isWVersionHigherOrEqual('8.3.0')}<tr>
    <td colspan="2">
        {/if}
        {include file='assets/css_assets.tpl'}

        {if $isCustomIntegrationCss}
            <link rel="stylesheet" href="{$customAssetsURL}/css/integration.css">
        {/if}

        <div id="layers" class="layers-integration">
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
            function mgWaitForAssets(){
                setTimeout(function(){
                    if (typeof window.Vue === 'function' && typeof window.mgLoadPageContoler === 'function' 
                    && typeof window.initMassActionsOnDatatables === 'function') {
                        mgLoadPageContoler();
                        mgEventHandler.on('AppCreated', null, function(appId, params){
                            params.instance.$nextTick(function () {
                                initContainerTooltips('layers');
                            });
                        }, 1000)
                    } else {
                        mgWaitForAssets();
                    }
                }, 1000);
            }
            mgWaitForAssets();
        </script>
        <style>
            .vue-app-main-container {
                margin-left: 16px;
                margin-right: 16px;
                margin-top: 16px;
            }
            .vue-app-main-container .lu-row--eq-height {
                margin-top: 16px
            }
            .lu-app-main__body {
                margin-bottom: 16px !important;

            }
            #divModuleSettings {
                border: 3px solid #e2e7e9;
                border-collapse: separate;
                border-spacing: 2px;
                border-radius: 4px;
                background: #efefef;
                margin-bottom: 10px;
            }
            .lu-app-main__body .alert {
                margin-bottom: 0px !important;
            }
        </style>

        {if !$mgWhmcsVersionComparator->isWVersionHigherOrEqual('8.3.0')}
    </td>
</tr>{/if}
