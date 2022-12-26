{block name=head}
<link href="modules/addons/SocialMediaLogIn/assets/miniProviders/{$miniSet}.css" rel="stylesheet" type="text/css"/>
{/block}

<style>
    .btn-media-mini {
        background-color:{$buttonsColor}; 
        border-color: #e4e4e4; 
    } 
    .btn-media:hover {
        background-color:{$buttonsColorOnHover}; 
        border-color: #e4e4e4; 
    } 
    .fa2 {
        color: {$iconsColor}
    }
</style>

{foreach $buttonsColorsPerProviders as $provider} 
    <style>
        a[href="?module_provider={$provider->provider}"] {
            background-color:{$provider->button_color};
        }
        a[href="?module_provider={$provider->provider}"]:hover {
            background-color:{$provider->button_hover_color};
        }
     </style>
{/foreach}

{foreach $iconsColorsPerProviders as $provider} 
    {assign var=providerColor value=".fa-{$provider->provider}"}
    <style>      
        {$providerColor}{
        color: {$provider->icon_color}
    </style>    
{/foreach}

<div class="row" style="text-align: center;">
    {foreach $providers as $name}
        <a href="?module_provider={$name->getName()}" class="btn btn-media-mini"><i class="{$miniIconSet->getIconName($name->getName())}"></i></a>
    {/foreach}
</div>