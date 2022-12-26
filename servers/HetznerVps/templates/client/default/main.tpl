<div class="lu-mg-wrapper body" data-target=".body" data-spy="scroll" data-twttr-rendered="true">

<link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700" rel="stylesheet">
<link rel="stylesheet" href="{$assetsURL}/css/layers-ui.css">
<link rel="stylesheet" href="{$assetsURL}/css/styles.css">

<div class="full-screen-module-container" id="layers">
        {if empty($array) === false}
            <div class="">  
                <div class="top-menu">
                    <div class="page-container" >
                        <div class="nav-menu">
                            <ul class="nav navbar-nav">
                                {foreach from=$menu key=catName item=category}
                                    {if $category.submenu}
                                        <li class="menu-dropdown">
                                            {if $category.disableLink}
                                                <a href="#"  data-hover="dropdown" data-close-others="true" >
                                                    {if $category.icon}<i class="{$category.icon}"></i>{/if}
                                                    {if $category.label}
                                                        {$subpage.label} 
                                                    {else}
                                                        <span class="mg-pages-label">{$MGLANG->T('pagesLabels','label' , $catName)}</span>
                                                    {/if}
                                                    <i class="fa fa-angle-down dropdown-angle"></i>
                                                </a>
                                            {else}   
                                                <a href="{$category.url}" data-hover="dropdown" data-close-others="true">
                                                    {if $category.icon}<i class="{$category.icon}"></i>{/if}
                                                    {if $category.label}
                                                        {$subpage.label}
                                                    {else}
                                                        <span class="mg-pages-label">{$MGLANG->T('pagesLabels','label', $catName)}</span>
                                                    {/if}
                                                    <i class="fa fa-angle-down dropdown-angle"></i>
                                                </a>
                                            {/if}
                                            <ul class="dropdown-menu pull-left">
                                                {foreach from=$category.submenu key=subCatName item=subCategory}
                                                    <li>
                                                        {if $subCategory.externalUrl}
                                                            <a href="{$subCategory.externalUrl}" target="_blank">
                                                                {if $subCategory.icon}<i class="{$subCategory.icon}"></i>{/if} 
                                                                {if $subCategory.label}
                                                                    {$subCategory.label}
                                                                {else}
                                                                    {$MGLANG->T('pagesLabels',$catName,$subCatName)}
                                                                {/if}
                                                            </a>
                                                        {else}
                                                            <a href="{$subCategory.url}">
                                                                {if $subCategory.icon}<i class="{$subCategory.icon}"></i>{/if} 
                                                                {if $subCategory.label}
                                                                    {$subCategory.label}
                                                                {else}
                                                                    {$MGLANG->T('pagesLabels',$catName,$subCatName)}
                                                                {/if}
                                                            </a>
                                                        {/if}
                                                    </li>
                                                {/foreach}
                                            </ul>
                                        </li>
                                    {else}
                                        <li>
                                            <a href="{if $category.externalUrl}{$category.externalUrl}{else}{$category.url}{/if}" {if $catName ==$currentPageName}class="active"{/if} {if $category.externalUrl}target="_blank"{/if}>
                                                {if $category.icon}<i class="{$category.icon}"></i>{/if} 
                                                {if $category.label}
                                                    <span>{$subpage.label}</span>
                                                {else}
                                                    <span>{$MGLANG->T('pagesLabels', 'label', $catName)}</span>
                                                {/if}
                                            </a>
                                        </li>
                                    {/if}
                                {/foreach}
                            </ul>
                        </div> 
                    </div>
                </div>
            </div>
        {/if}
        <div class="clearfix"></div>              
        <div class="page-container">
            <div class="row-fluid" id="MGAlerts">
                {if $error}
                    <div class="alert lu-alert-danger">
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
                        <div class="lu-row">
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
                        </div>
                    {/if}
                    {$content}
            </div>
        </div>
    </div>
</div>
<script type="text/javascript" src="{$assetsURL}/js/vue.min.js"></script>
{*<script type="text/javascript" src="https://unpkg.com/vue"></script>*}
<script type="text/javascript" src="{$assetsURL}/js/mgComponents.js"></script>
<script type="text/javascript" src="{$assetsURL}/js/zxcvbn.js"></script>
<script type="text/javascript" src="{$assetsURL}/js/jscolor.min.js"></script>            
<script type="text/javascript" src="{$assetsURL}/js/layers-ui.js"></script>
<script type="text/javascript" src="{$assetsURL}/js/layers-ui-table.js"></script>
<script type="text/javascript" src="{$assetsURL}/js/chart.min.js"></script>
<script type="text/javascript" src="{$assetsURL}/js/graph.js"></script>

<div class="clear"></div>
