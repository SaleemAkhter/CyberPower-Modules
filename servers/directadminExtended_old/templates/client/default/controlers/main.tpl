<div class="mg-wrapper body" data-target=".body" data-spy="scroll" data-twttr-rendered="true">
<link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700" rel="stylesheet">


    {if $mainName|strstr:"WordPress Manager"}
    <link rel="stylesheet" href="{$assetsURL}/css/wordPressManager/layers-ui.css">
    <link rel="stylesheet" href="{$assetsURL}/css/wordPressManager/mg_styles.css">
    <link rel="stylesheet" href="{$assetsURL}/css/wordPressManager/module_styles.css">

    <div class="full-screen-module-container" id="layers">
        <div class="lu-app">
            {$content}
        </div>
    </div>
</div>

<script type="text/javascript" src="{$assetsURL}/js/wordPressManager/vue.min.js"></script>
<script type="text/javascript" src="{$assetsURL}/js/wordPressManager/mgComponents.js"></script>
<script type="text/javascript" src="{$assetsURL}/js/wordPressManager/jscolor.min.js"></script>
<script type="text/javascript" src="{$assetsURL}/js/wordPressManager/layers-ui.js"></script>
<script type="text/javascript" src="{$assetsURL}/js/wordPressManager/chart.min.js"></script>
<script type="text/javascript" src="{$assetsURL}/js/wordPressManager/WordpressManagerIntegration.js"></script>

<div class="clear"></div>


{else}
<link rel="stylesheet" href="{$assetsURL}/css/layers-ui.css">
<link rel="stylesheet" href="{$assetsURL}/css/mg_styles.css">
<script type="text/javascript" src="{$assetsURL}/js/vuejs-datapicker.min.js"></script>

{if $isCustomModuleCss}
    <link rel="stylesheet" href="{$customAssetsURL}/css/module_styles.css">
{/if}

<div class="full-screen-module-container" id="layers">
    <div class="lu-app">
        {$content}
    </div>
</div>
</div>

{if $isDebug}
    <script type="text/javascript" src="https://unpkg.com/vue"></script>
{else}
    <script type="text/javascript" src="{$assetsURL}/js/vue.min.js"></script>
{/if}
<script type="text/javascript" src="{$assetsURL}/js/moment.js"></script>
<script type="text/javascript" src="{$assetsURL}/js/mgComponents.js"></script>
<script type="text/javascript" src="{$assetsURL}/js/zxcvbn.js"></script>
<script type="text/javascript" src="{$assetsURL}/js/jscolor.min.js"></script>
<script type="text/javascript" src="{$assetsURL}/js/layers-ui.js"></script>
<script type="text/javascript" src="{$assetsURL}/js/layers-ui-table.js"></script>
<script type="text/javascript" src="{$assetsURL}/js/chart.min.js"></script>

<div class="clear"></div>

{/if}