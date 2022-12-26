{assign var="iconLocation" value="./../../../assets/svg-icons"}
{assign var="iconsPath" value="./../../../../../../templates/{$themeName}/assets/svg-icon"}
{assign var="ilustrationPath" value="./../../../../../../templates/{$themeName}/assets/svg-illustrations"}
{assign var="imagesPath" value="{$whmcsURL}/templates/{$themeName}/assets/img"}
{foreach $template->getAllIllustrations() as $key => $illustration}
    <label class="media__item" data-media-item="{$illustration['name']}" data-toggle="lu-tooltip" data-title="{$illustration['name']}">
        <div class="media__item-icon">
            {include file="{$illustration.path}"}
        </div>
        <input class="media__item-input lagom-icon" type="radio" name="item[illustration]" value="{$illustration['name']}">
        <span class="media__item-border"></span>
        <span class="media__item-label"></span>
    </label>
{/foreach}
<div class="media__no-data msg p-3x is-hidden" data-media-no-data>
    <div class="msg__icon i-c-6x">
        {include file="$iconLocation/no-data.tpl"}
    </div>
    <div class="msg__body">
        <span class="msg__title">No media found</span>
    </div>
</div>