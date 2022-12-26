<?php

$period = (isset($_POST['graph_period']) && !empty($_POST['graph_period'])) ? $_POST['graph_period'] : '-1440';
$peroidtext = $language['cf_analytic_last_24_hours'];
$visitorperoidtext = $language['cf_analytic_unique_visitors_per_hour'];
if ($period == '-10080') {
    $peroidtext = $language['cf_analytic_last_week'];
    $visitorperoidtext = $language['cf_analytic_unique_visitors_per_day'];
} elseif ($period == '-43200') {
    $peroidtext = $language['cf_analytic_last_month'];
    $visitorperoidtext = $language['cf_analytic_unique_visitors_per_day'];
}
$requestSinceData = $requestUntilData = $min = $max = $pageviews = '';
$dashboardArr = [];
$uniquesArr = [];
$result = $CF->wgsCfGetDashboard($period);
if ($result['success'] == 1) {
    foreach ($result['result']['timeseries'] as $value) {
        $sinceDate = date('Y,m,d,H,i,s', strtotime($value['since'] . ' -1 months'));
        $untilDate = date('Y,m,d,H,i,s', strtotime($value['since'] . ' -1 months'));
        $requestCachedData .= "[Date.UTC(" . $sinceDate . "), " . ($value['requests']['cached']) . "],";
        $requestUnchachedData .= "[Date.UTC(" . $sinceDate . "), " . ($value['requests']['uncached']) . "],";
        $bwCachedData .= "[Date.UTC(" . $sinceDate . "), " . round(($value['bandwidth']['cached'] / 1000), 2) . "],";
        $bwUnchachedData .= "[Date.UTC(" . $sinceDate . "), " . round(($value['bandwidth']['uncached'] / 1000), 2) . "],";
        $threatsGraph .= "[Date.UTC(" . $sinceDate . "), " . ($value['threats']['all']) . "],";
        $uniqueVistorsGraph .= "[Date.UTC(" . $sinceDate . "), " . ($value['uniques']['all']) . "],";
        $uniquesArr[] = $value['uniques']['all'];
    }
    $dashboardArr['requests'] = ['cached_graph' => $requestCachedData, 'uncached_graph' => $requestUnchachedData, 'all' => $result['result']['totals']['requests']['all'], 'cached' => $result['result']['totals']['requests']['cached'], 'uncached' => $result['result']['totals']['requests']['uncached']];
    $dashboardArr['bandwidth'] = ['cached_graph' => $bwCachedData, 'uncached_graph' => $bwUnchachedData, 'all' => $CF->wgsCf_formatSizeUnits($result['result']['totals']['bandwidth']['all']), 'cached' => $CF->wgsCf_formatSizeUnits($result['result']['totals']['bandwidth']['cached']), 'uncached' => $CF->wgsCf_formatSizeUnits($result['result']['totals']['bandwidth']['uncached'])];
    $dashboardArr['threats'] = ['all' => $result['result']['totals']['threats']['all'], 'graph' => $threatsGraph, 'top_country' => array_keys($result['result']['totals']['threats']['country'])['0'], 'type' => array_keys($result['result']['totals']['threats']['type'])['0']];
    $dashboardArr['uniques'] = ['all' => $result['result']['totals']['uniques']['all'], 'graph' => $uniqueVistorsGraph, 'max' => max($uniquesArr), 'min' => min($uniquesArr)];

    $vars["success"] = 1;
    $vars["pageviews"] = $pageviews;
    $vars["uniques"] = $uniques;
    $vars["dashboard"] = $dashboardArr;
    $vars["periodtext"] = $peroidtext;
    $vars["visitorperoidtext"] = $visitorperoidtext;
    $var['graph_period'] = $period;
} else {
    $error = $result['messages'];
    $vars["error"] = $error;
}

$templateFile = "template/analyticstab/analytics";
?>