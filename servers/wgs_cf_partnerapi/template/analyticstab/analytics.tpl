{$headerhtml}
{$menu}
{*<script type="text/javascript" src="https://www.google.com/jsapi"></script>*}
<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/series-label.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>

<div class="cfcontent">
    <h3 class="cfcontentmargin">{$wgs_lang.cf_analytic_web_analytics}</h3>
</div>
<div class="cfinternal">
    {if $error}
        <div class="cfa_error">
            <span></span>
            {$error}
        </div>
    {/if}
    {if $actionsucess}
        <div class="cfa_success">
            <span></span>
            {$actionsucess}
        </div>
    {/if}
    <div class="graph-cont">
        <div class="graph-body">
            <div class="selectbox">
                <form name="cfactionform" id="cfactionform" method="post" action="clientarea.php?action=productdetails&id={$serviceid}">
                    <input type="hidden" name="modop" value="custom">
                    <input type="hidden" name="a" value="ManageCf">
                    <input type="hidden" name="cfaction" id="cfaction" value="analytics">
                    <input type="hidden" name="cf_action" id="cfaction" value="manageWebsite">
                    <input type="hidden" name="website" id="cfaction" value="{$domain}">
                    <select name="graph_period" onchange="submit();">
                        <option value="-1440" {if $smarty.post.graph_period eq '-1440'}selected{/if}>{$wgs_lang.cf_analytic_last_24_hours}</option>
                        <option value="-10080" {if $smarty.post.graph_period eq '-10080'}selected{/if}>{$wgs_lang.cf_analytic_last_week}</option>
                        <option value="-43200" {if $smarty.post.graph_period eq '-43200'}selected{/if}>{$wgs_lang.cf_analytic_last_month}</option>
                    </select>
                </form>
            </div>
            <ul class="an-list">
                <li><a class="active-tab" href="javascript:void(0);" onclick="toggleGraph(this, 'requests_cont');">{$wgs_lang.cf_analytic_requests}</a> </li>
                <li><a href="javascript:void(0);" onclick="toggleGraph(this, 'bandwidth_cont');">{$wgs_lang.cf_analytic_bandwidth}</a> </li>
                <li><a href="javascript:void(0);" onclick="toggleGraph(this, 'uniques_cont');">{$wgs_lang.cf_analytic_unique_visitors}</a> </li>
                <li><a href="javascript:void(0);" onclick="toggleGraph(this, 'threats_cont');">{$wgs_lang.cf_analytic_threats}</a> </li>
            </ul>
            <div class="graphs" id="requests_cont">
                <h3>{$wgs_lang.cf_analytic_requests_heading}</h3>
                <ul class="graph-list-box">
                    <li class="box-cl-1"><h4>{$wgs_lang.cf_analytic_total_requests}</h4><small>{$periodtext}</small><h5>{$dashboard.requests.all}</h5></li>
                    <li class="box-cl-2"><h4>{$wgs_lang.cf_analytic_chached_requests}</h4><small>{$periodtext}</small><h5>{$dashboard.requests.cached}</h5></li>
                    <li class="box-cl-3"><h4>{$wgs_lang.cf_analytic_unchached_requests}</h4><small>{$periodtext}</small><h5>{$dashboard.requests.uncached}</h5></li>
                </ul>
                <div id="requests" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
            </div>
            <div class="graphs" id="bandwidth_cont" style="display: none;">
                <h3>{$wgs_lang.cf_analytic_bandwidth}</h3>
                <ul class="graph-list-box">
                    <li class="box-cl-1"><h4>{$wgs_lang.cf_analytic_total_bandwidth}</h4><small>{$periodtext}</small><h5>{$dashboard.bandwidth.all}</h5></li>
                    <li class="box-cl-2"><h4>{$wgs_lang.cf_analytic_chached_bandwidth}</h4><small>{$periodtext}</small><h5>{$dashboard.bandwidth.cached}</h5></li>
                    <li class="box-cl-3"><h4>{$wgs_lang.cf_analytic_unchached_bandwidth}</h4><small>{$periodtext}</small><h5>{$dashboard.bandwidth.uncached}</h5></li>
                </ul>
                <div id="bandwidth" style="min-width: 310px; height: 400px; margin: 0 auto;"></div>
            </div>
            <div class="graphs" id="uniques_cont" style="display: none;">
                <h3>{$wgs_lang.cf_analytic_unique_visitors}</h3>
                <ul class="graph-list-box">
                    <li class="box-cl-1"><h4>{$wgs_lang.cf_analytic_total_unique_visitors}</h4><small>{$periodtext}</small><h5>{$dashboard.uniques.all}</h5></li>
                    <li class="box-cl-2"><h4>{$wgs_lang.cf_analytic_max_unique_visitors}</h4><small>{$visitorperoidtext}</small><h5>{$dashboard.uniques.max}</h5></li>
                    <li class="box-cl-3"><h4>{$wgs_lang.cf_analytic_min_unique_visitors}</h4><small>{$visitorperoidtext}</small><h5>{$dashboard.uniques.min}</h5></li>
                </ul>
                <div id="uniques" style="min-width: 310px; height: 400px; margin: 0 auto;"></div>
            </div>
            <div class="graphs" id="threats_cont" style="display: none;">
                <h3>{$wgs_lang.cf_analytic_threats}</h3>
                <ul class="graph-list-box">
                    <li class="box-cl-1"><h4>{$wgs_lang.cf_analytic_total_threats}</h4><small>{$periodtext}</small><h5>{$dashboard.threats.all}</h5></li>
                    <li class="box-cl-2"><h4>{$wgs_lang.cf_analytic_threats_top_country}</h4><small>{$periodtext}</small><h5>{$dashboard.threats.top_country}</h5></li>
                    <li class="box-cl-3"><h4>{$wgs_lang.cf_analytic_threats_top_threat_type}</h4><small>{$periodtext}</small><h5>{$dashboard.threats.type}</h5></li>
                </ul>
                <div id="threats" style="min-width: 310px; height: 400px; margin: 0 auto;"></div>
            </div>
        </div>
    </div>
    <div class="cfcontenttabletype2">
        {*<div class="">
        <div class="col-sm-6">
        <div class="cfgraphinfo"><b>{$wgs_lang.cf_analytic_total}:</b> {$requestsserved.total} <b class="cfgraphskydark">{$wgs_lang.cf_analytic_cached}:</b> {$requestsserved.cached} <b class="cfgraphred">{$wgs_lang.cf_analytic_uncached}:</b> {$requestsserved.uncached}</div>
        <div id="donutchart_requestserved"></div>
        </div>
        <div class="col-sm-6">
        <div class="cfgraphinfo"><b>{$wgs_lang.cf_analytic_total}:</b> {$bandwidthserved.total} <b class="cfgraphskydark">{$wgs_lang.cf_analytic_cached}:</b> {$bandwidthserved.cached} <b class="cfgraphred">{$wgs_lang.cf_analytic_uncached}:</b> {$bandwidthserved.uncached}</div>
        <div id="donutchart_bandwidthserved"></div>
        </div>
        </div>
        <div class="">
        <div class="col-sm-6">
        <div class="cfgraphinfo"><b class="cfgraphblue">{$wgs_lang.cf_analytic_regular}:</b> {$pageviews.regular} <b class="cfgraphred">{$wgs_lang.cf_analytic_threat}:</b>  {$pageviews.threat} <b class="cfgraphyellow">{$wgs_lang.cf_analytic_crawler}:</b> {$pageviews.crawler}</div>
        <div id="donutchart_pageviews"></div>
        </div>
        <div class="col-sm-6">
        <div class="cfgraphinfo"><b class="cfgraphblue">{$wgs_lang.cf_analytic_regular}:</b> {$uniques.regular} <b class="cfgraphred">{$wgs_lang.cf_analytic_threat}:</b>  {$uniques.threat} <b class="cfgraphyellow">{$wgs_lang.cf_analytic_crawler}:</b> {$uniques.crawler}</div>
        <div id="donutchart_uniques"></div>
        </div>
        </div>*}
    </div>
</div>
{literal}
    <script type="text/javascript">
    {/literal}
    {if $dashboard.requests.cached_graph neq ''}
        {literal}
            Highcharts.chart('requests', {
                title: {
                    text: '{/literal}{$wgs_lang.cf_analytic_requests_heading}{literal}'
                },
                xAxis: {
                    type: 'datetime',
                    dateTimeLabelFormats: {
                        month: '%e. %b',
                        year: '%b',
                        hour: '%l:%P'
                    },
                    title: {
                        text: '{/literal}{$wgs_lang.cf_analytic_time_local}{literal}'
                    }
                },
                yAxis: {
                    title: {
                        text: '{/literal}{$wgs_lang.cf_analytic_no_of_requests}{literal}'
                    },
                    min: 0
                },
                tooltip: {
                    crosshairs: [true, true],
                    headerFormat: '{point.x:%A, %b %e, %Y, %l:%P}<br/>',
                    pointFormat: '<span style="color:{point.color}">\u25CF</span> {series.name}: <b>{point.y}</b><br/>'
                },
                plotOptions: {
                    spline: {
                        marker: {
                            enabled: false
                        }
                    }
                },
                colors: ['#6CF', '#39F', '#06C', '#036', '#000'],
                series: [{
                        type: 'area',
                        name: "{/literal}{$wgs_lang.cf_analytic_cached}{literal}",
                        data: [{/literal}{$dashboard.requests.cached_graph}{literal}],
                        color: '#62a0ca',
                    }, {
                        type: 'areaspline',
                        name: "{/literal}{$wgs_lang.cf_analytic_uncached}{literal}",
                        data: [{/literal}{$dashboard.requests.uncached_graph}{literal}],
                        color: '#aec7e8'
                    }]
            });
        {/literal}
    {/if}
    {if $dashboard.bandwidth.cached_graph neq ''}
        {literal}
            Highcharts.chart('bandwidth', {
                title: {
                    text: '{/literal}{$wgs_lang.cf_analytic_bandwidth}{literal}'
                },
                xAxis: {
                    type: 'datetime',
                    dateTimeLabelFormats: {
                        month: '%e. %b',
                        year: '%b',
                        hour: '%l:%P'
                    },
                    title: {
                        text: '{/literal}{$wgs_lang.cf_analytic_time_local}{literal}'
                    }
                },
                yAxis: {
                    title: {
                        text: '{/literal}{$wgs_lang.cf_analytic_bandwidth}{literal}'
                    },
                    min: 0
                },
                tooltip: {
                    crosshairs: [true, true],
                    headerFormat: '{point.x:%A, %b %e, %Y, %l:%P}<br/>',
                    pointFormat: '<span style="color:{point.color}">\u25CF</span> {series.name}: <b>{point.y} KB</b><br/>'
                },
                plotOptions: {
                    spline: {
                        marker: {
                            enabled: false
                        }
                    }
                },
                colors: ['#6CF', '#39F', '#06C', '#036', '#000'],
                series: [{
                        type: 'area',
                        name: "{/literal}{$wgs_lang.cf_analytic_cached}{literal}",
                        data: [{/literal}{$dashboard.bandwidth.cached_graph}{literal}],
                        color: '#62a0ca',
                    }, {
                        type: 'areaspline',
                        name: "{/literal}{$wgs_lang.cf_analytic_uncached}{literal}",
                        data: [{/literal}{$dashboard.bandwidth.uncached_graph}{literal}],
                        color: '#aec7e8'
                    }]
            });
        {/literal}
    {/if}
    {if $dashboard.uniques.graph neq ''}
        {literal}
            Highcharts.chart('uniques', {
                title: {
                    text: '{/literal}{$wgs_lang.cf_analytic_unique_visitors}{literal}'
                },
                xAxis: {
                    type: 'datetime',
                    dateTimeLabelFormats: {
                        month: '%e. %b',
                        year: '%b',
                        hour: '%l:%P'
                    },
                    title: {
                        text: 'Time (local)'
                    }
                },
                yAxis: {
                    title: {
                        text: '{/literal}{$wgs_lang.cf_analytic_no_of_unique_visitors}{literal}'
                    },
                    min: 0
                },
                tooltip: {
                    crosshairs: [true, true],
                    headerFormat: '{point.x:%A, %b %e, %Y, %l:%P}<br/>',
                    pointFormat: '<span style="color:{point.color}">\u25CF</span> {series.name}: <b>{point.y}</b><br/>'
                },
                plotOptions: {
                    spline: {
                        marker: {
                            enabled: false
                        }
                    }
                },
                colors: ['#6CF', '#39F', '#06C', '#036', '#000'],
                series: [{
                        type: 'area',
                        name: "{/literal}{$wgs_lang.cf_analytic_no_of_unique_visitors}{literal}",
                        data: [{/literal}{$dashboard.uniques.graph}{literal}],
                        color: '#62a0ca',
                    }]
            });
        {/literal}
    {/if}

    {if $dashboard.threats.graph neq ''}
        {literal}
            Highcharts.chart('threats', {
                title: {
                    text: '{/literal}{$wgs_lang.cf_analytic_threats}{literal}'
                },
                xAxis: {
                    type: 'datetime',
                    dateTimeLabelFormats: {
                        month: '%e. %b',
                        year: '%b',
                        hour: '%l:%P'
                    },
                    title: {
                        text: '{/literal}{$wgs_lang.cf_analytic_time_local}{literal}'
                    }
                },
                yAxis: {
                    title: {
                        text: '{/literal}{$wgs_lang.cf_analytic_no_of_threats}{literal}'
                    },
                    min: 0
                },
                tooltip: {
                    crosshairs: [true, true],
                    headerFormat: '{point.x:%A, %b %e, %Y, %l:%P}<br/>',
                    pointFormat: '<span style="color:{point.color}">\u25CF</span> {series.name}: <b>{point.y}</b><br/>'
                },
                plotOptions: {
                    spline: {
                        marker: {
                            enabled: false
                        }
                    }
                },
                colors: ['#6CF', '#39F', '#06C', '#036', '#000'],
                series: [{
                        type: 'area',
                        name: "{/literal}{$wgs_lang.cf_analytic_threats}{literal}",
                        data: [{/literal}{$dashboard.threats.graph}{literal}],
                        color: '#62a0ca',
                    }]
            });
        {/literal}
    {/if}
    {literal}
    </script>
{/literal}

            {$cffooter}