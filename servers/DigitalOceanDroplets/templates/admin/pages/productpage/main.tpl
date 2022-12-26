<div class="lu-mg-wrapper body" data-target=".body" data-spy="scroll" data-twttr-rendered="true">

    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700" rel="stylesheet">
    <link rel="stylesheet" href="{$assetsURL}/css/layers-ui.css">
    <link rel="stylesheet" href="{$assetsURL}/css/custom-product-page.css">


    <div class="full-screen-module-container" id="layers">
        <div class="clearfix"></div>              
        <div class="page-container">
            <div class="row-fluid" id="MGAlerts">
                {if $error}
                    <div class="lu-alert lu-alert-danger">
                        <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only"></span></button>
                        <p><strong>{$error}</strong></p>
                    </div>
                {/if}
                {if $success}
                    <div class="lu-alert lu-alert-success">
                        <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only"></span></button>
                        <p><strong>{$success}</strong></p>
                    </div>
                {/if}
                <div style="display:none;" data-prototype="error">
                    <div class="lu-alert lu-alert-danger">
                        <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only"></span></button>
                        <strong></strong>
                        <a style="display:none;" class="errorID" href=""></a>
                    </div>
                </div>
                <div style="display:none;" data-prototype="success">
                    <div class="lu-alert lu-alert-success">
                        <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only"></span></button>
                        <strong></strong>
                    </div>
                </div>
            </div>
            <div class="page-content" id="MGPage{$currentPageName}">
                {if ($isDebug eq true AND (count($MGLANG->getMissingLangs()) != 0))}
                    <div class="lu-widget">
                        <div class="lu-widget__body">
                            <div class="lu-widget__content">
                                <div class="lu-row">
                                    {foreach from=$MGLANG->getMissingLangs() key=varible item=value}
                                        <div class="lu-col-md-12"><b>{$varible}</b> = '{$value}';</div>
                                            {/foreach}
                                </div>
                            </div>
                        </div>
                    </div>

                {/if}
                {$content}
            </div>
        </div>
    </div>
</div>

<div class="clear"></div>

{*<script type="text/javascript" src="https://unpkg.com/vue"></script>*}
<script type="text/javascript" src="{$assetsURL}/js/vue.min.js"></script>
<script type="text/javascript">
    var mgGlobalAppConfig = {
        appScope : '#mgVueMainContainer',
        autoInitSelects : false,
        initSelectsAfterAppCreate : true,
        autoInitTooltips : false,
        initTooltipsAfterAppCreate : true        
    };    
    $.getScript('{$assetsURL}/js/mgComponents.js', function (data, textStatus) {
        if (textStatus == "success") {
            function mgLoadPageContoler() {
                mgPageControler = new mgVuePageControler('mgVueMainContainer');
                mgPageControler.vinit();
            };
            if (document.readyState !== "complete") {
                document.onreadystatechange = function () {
                    if (document.readyState === "complete") {
                        mgLoadPageContoler();
                    }
                };
            } else {
                mgLoadPageContoler();
            }
        }
    });
</script>	
<script type="text/javascript" src="{$assetsURL}/js/zxcvbn.js"></script>
<script type="text/javascript" src="{$assetsURL}/js/jscolor.min.js"></script>            
<script type="text/javascript" src="{$assetsURL}/js/layers-ui.js"></script>
<script type="text/javascript" src="{$assetsURL}/js/layers-ui-table.js"></script>   
