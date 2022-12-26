<?php
$headerhtml = '<script type="text/javascript" src="' . $moduleURL . '/js/cloudflare.js"></script>';
$headerhtml .= '<link type="text/css" rel="stylesheet" href="' . $moduleURL . '/css/cloudflare.css">';
$headerhtml .= '<div class="cf-reseller-module"><div class="cfhead"><!--h2>' . $language['cf_cloudflare_centeral'] . '</h2--><span>' . $domain . '<a href="javascript:void(0);" class="status' . $status . '">' . $status . '</a></span></div>';
?>