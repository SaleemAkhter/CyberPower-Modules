{block name=head}
<link href="{$urlSystem}modules/addons/SocialMediaLogIn/assets/providers/{$set}.css" rel="stylesheet" type="text/css"/>
{/block}


<style>
    .btn-media {
        background-color:{$buttonsColor}; 
        border-color: #e4e4e4; 
    } 
    .btn-media:hover {
        background-color:{$buttonsColorOnHover}; 
        border-color: #e4e4e4; 
    } 
    .btn-info {
        color:{$iconsColor}; 
    } 
    .btn-info:hover {
        color:{$iconsColor}; 
    } 
    .fa2 {
        color: {$iconsColor};
    }
    .row-centered {
        {if $smarty.server.SCRIPT_NAME|strstr:"cart.php"}
            width: 100%;
        {else}
            {if $template != "six"}
                width: 48%;
            {/if}
        {/if}
    }
</style>

{foreach $buttonsColorsPerProviders as $provider} 
    <style>
        a[href="?module_provider={$provider->provider}"] {
            background-color:{$provider->button_color};
            color:{$provider->icon_color};
        }
        a[href="?module_provider={$provider->provider}"]:hover {
            background-color:{$provider->button_hover_color};
            color:{$provider->icon_color};
        }
   </style>
{/foreach}

{foreach $iconsColorsPerProviders as $provider} 
    {assign var=providerColor value=".fa-{$provider->provider}"}
    
    {if $provider->provider == 'microsoft' || $provider->provider == 'azure'}
         {assign var=providerColor value=".fa-windows"}  
    {/if}
    
    <style>   
        {$providerColor}{
        color: {$provider->icon_color}
    </style>    
{/foreach}
 
<div class="row row-media row-centered" id="asd">
    {foreach $providers as $name} 
        <a href="?module_provider={$name->getName()}" class="btn btn-info btn-media"><i class="{$iconSet->getIconName($name->getName())}"></i>{if $name->getConfiguration()->custom_name neq ""}{$name->getConfiguration()->custom_name}{else}{$name->getFriendlyName()}{/if}</a>
    {/foreach}
</div>

