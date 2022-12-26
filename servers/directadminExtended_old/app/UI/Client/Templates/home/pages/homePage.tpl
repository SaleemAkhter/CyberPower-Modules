{assign var="assetsUrl" value=$rawObject->getAssetsUrl()}
{if $rawObject->isEnabledShowIP()}
    <div class="lu-alert lu-alert--outline lu-alert--info">
        <div class="lu-alert__body">
            <b>{$MGLANG->absoluteT('dedicatedIPAdressIs')}</b> {$rawObject->getDedicatedIp()}
        </div>
    </div>
{/if}
{foreach from=$rawObject->getSectionHeaders() key=section item=header}
<div class="lu-h4 lu-m-b-3x lu-m-t-2x">{$MGLANG->absoluteT('addonCA','homePage',$header)}</div>
<div class="lu-tiles lu-row lu-row--eq-height">
    {foreach from=$rawObject->getSection($section) key=setting item=controller}
        <div class="lu-col-xs-6 lu-col-md-20p">
            <a class="lu-tile lu-tile--btn" href="{$rawObject->getRedirectUrl($controller)}">
                <div class="lu-i-c-6x">
                    <img src="{$assetsUrl}/img/directadmin/icon-{$controller}.png" alt="">
                </div>
                <div class="lu-tile__title">{$MGLANG->absoluteT('addonCA' , 'homeIcons' ,$controller)}</div>
            </a>
        </div>
    {/foreach}
</div>
{/foreach}

{if $rawObject->getOneClickLoginButtons()}
    <div class="lu-h4 lu-m-b-3x lu-m-t-2x">{$MGLANG->absoluteT('addonCA','homePage','oneclickLogin')}</div>
    <div class="lu-tiles lu-row lu-row--eq-height">
        {foreach from=$rawObject->getOneClickLoginButtons() item=$button}
            {$button->getHtml()}
        {/foreach}
    </div>
{/if}


