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

<link rel="stylesheet"
  href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.52.2/codemirror.min.css">
</link>
<link rel="stylesheet" href="https://codemirror.net/5/theme/base16-light.css"></link>
<link rel="stylesheet" href="https://codemirror.net/5/theme/base16-dark.css"></link>
<link rel="stylesheet" href="https://codemirror.net/5/theme/monokai.css"></link>
<link rel="stylesheet" href="https://codemirror.net/5/theme/solarized.css"></link>
<script type="text/javascript"
  src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.52.2/codemirror.min.js">
</script>
<script type="text/javascript" src="https://codemirror.net/5/mode/javascript/javascript.js"></script>
<script type="text/javascript" src="https://codemirror.net/5/mode/perl/perl.js"></script>
<script type="text/javascript" src="https://codemirror.net/5/mode/php/php.js"></script>
<script type="text/javascript" src="https://codemirror.net/5/mode/xml/xml.js"></script>
<script type="text/javascript" src="https://codemirror.net/5/mode/css/css.js"></script>
<script type="text/javascript" src="https://codemirror.net/5/mode/vbscript/vbscript.js"></script>
<script type="text/javascript" src="https://codemirror.net/5/mode/htmlmixed/htmlmixed.js"></script>
<script type="text/javascript" src="https://codemirror.net/5/mode/nginx/nginx.js"></script>
<script type="text/javascript" src="https://codemirror.net/5/mode/sql/sql.js"></script>
<script type="text/javascript" src="https://codemirror.net/5/mode/php/php.js"></script>

{if $isCustomModuleCss}
    <link rel="stylesheet" href="{$customAssetsURL}/css/module_styles.css">
{/if}
<style>
    .message-system{
        position: relative;
    }
    .badge-success {
        margin-left: 5px;
        background: #62CA5F;

    }
</style>
<div class="full-screen-module-container" id="layers">
    <div class="lu-app {$currentPageName}" >

        {$content}
    </div>
</div>
</div>
{if $isDebug}
    {* <script type="text/javascript" src="https://unpkg.com/vue"></script> *}
    <script src="https://cdn.jsdelivr.net/npm/vue@2/dist/vue.js"></script>

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
{if $currentPageName eq 'Home'}
<script src="{$BASE_PATH_JS}/jquery.knob.js"></script>
<script type="text/javascript">
jQuery(function() {ldelim}
    jQuery(".dial-usage").knob({ldelim}'format':function (v) {ldelim} alert(v); {rdelim}{rdelim});
{rdelim});
</script>

{/if}
