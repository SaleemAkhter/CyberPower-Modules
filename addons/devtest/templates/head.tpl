<!-- Styling -->
<link href="//fonts.googleapis.com/css?family=Open+Sans:300,400,600|Raleway:400,700" rel="stylesheet">
<link href="{$WEB_ROOT}/templates/{$template}/assets/antler/css/all.min.css?v={$versionHash}" rel="stylesheet">
{assetExists file="custom.css"}
<link href="{$__assetPath__}" rel="stylesheet">
{/assetExists}

<!-- Antler styles -->
<link href="{$WEB_ROOT}/templates/{$template}/assets/antler/fonts/cloudicon/cloudicon.css?v={$versionHash}" rel="stylesheet">
<link href="{$WEB_ROOT}/templates/{$template}/assets/antler/fonts/opensans/opensans.css?v={$versionHash}" rel="stylesheet">
{*<link href="{$WEB_ROOT}/templates/{$template}/assets/antler/fonts/fontawesome/css/all.css" rel="stylesheet" onload="if(media!='all')media='all'">*}

{*<link href="{$WEB_ROOT}/templates/{$template}/assets/antler/css/owl.carousel.css?v={$versionHash}" rel="stylesheet">*}
<link href="{$WEB_ROOT}/templates/{$template}/assets/antler/css/bootstrap.min.css" rel="stylesheet">
<link href="{$WEB_ROOT}/templates/{$template}/assets/antler/css/filter.css" rel="stylesheet">
<link href="{$WEB_ROOT}/templates/{$template}/assets/antler/css/style.min.css" rel="stylesheet">

<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
  <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
<![endif]-->

<script type="text/javascript">
    var csrfToken = '{$token}',
        markdownGuide = '{lang|addslashes key="markdown.title"}',
        locale = '{if !empty($mdeLocale)}{$mdeLocale}{else}en{/if}',
        saved = '{lang|addslashes key="markdown.saved"}',
        saving = '{lang|addslashes key="markdown.saving"}',
        whmcsBaseUrl = "{\WHMCS\Utility\Environment\WebHelper::getBaseUrl()}",
        requiredText = '{lang|addslashes key="orderForm.required"}',
        recaptchaSiteKey = "{if $captcha}{$captcha->recaptcha->getSiteKey()}{/if}";
</script>
<script src="{$WEB_ROOT}/templates/{$template}/assets/antler/js/jquery.min.js"></script>
<script src="{$WEB_ROOT}/templates/{$template}/assets/antler/js/custom.js?v={$versionHash}"></script>
<script src="{$WEB_ROOT}/templates/{$template}/assets/antler/js/main.js?v={$versionHash}"></script>
<script src="{$WEB_ROOT}/templates/{$template}/assets/antler/js/custom.js"></script>


{if $templatefile == "viewticket" && !$loggedin}
  <meta name="robots" content="noindex" />
{/if}

<!-- Multilingual Condition to RTL & LTR Language -->
{if $language eq 'arabic' || $language eq 'farsi' || $language eq 'hebrew'}<html dir="rtl">
<link href="{$WEB_ROOT}/templates/{$template}/assets/antler/css/auto-rtl/bootstrap-rtl.min.css?v={$versionHash}" rel="stylesheet">
<link href="{$WEB_ROOT}/templates/{$template}/assets/antler/css/auto-rtl/rtl.css?v={$versionHash}" rel="stylesheet">
<link href="{$WEB_ROOT}/templates/{$template}/assets/antler/css/auto-rtl/main-rtl.css?v={$versionHash}" rel="stylesheet">
<script src="{$WEB_ROOT}/templates/{$template}/assets/antler/js/auto-rtl/main-rtl.js?v={$versionHash}"></script>
{else}
<link href="{$WEB_ROOT}/templates/{$template}/assets/antler/css/main.css" rel="stylesheet">
<link href="{$WEB_ROOT}/templates/{$template}/assets/antler/css/custom.css" rel="stylesheet">

<html>
{/if}

<!-- Antler custom color styles -->
<link href="{$WEB_ROOT}/templates/{$template}/assets/antler/css/colors/pink.css" rel="stylesheet" title="pink"/>
<link href="{$WEB_ROOT}/templates/{$template}/assets/antler/css/colors/blue.css" rel="stylesheet" title="blue"/ >
<link href="{$WEB_ROOT}/templates/{$template}/assets/antler/css/colors/green.css" rel="stylesheet" title="green"/>



