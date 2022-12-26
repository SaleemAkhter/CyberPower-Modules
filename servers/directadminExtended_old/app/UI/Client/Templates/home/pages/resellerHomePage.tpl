    <div class="lu-alert lu-alert--outline lu-alert--info">
        <div class="lu-alert__body">
            <b>{$MGLANG->absoluteT('resellerAccountInformation')}</b>
        </div>
    </div>


{if $rawObject->getOneClickLoginButtons()}
    <div class="lu-h4 lu-m-b-3x lu-m-t-2x">{$MGLANG->absoluteT('addonCA','homePage','oneclickLogin')}</div>
    <div class="lu-tiles lu-row lu-row--eq-height">
        {foreach from=$rawObject->getOneClickLoginButtons() item=$button}
            {$button->getHtml()}
        {/foreach}
    </div>
{/if}

