<div class="lu-app-navbar lu-navbar lu-navbar--responsive lu-m-b-1x">
    <div class="lu-navbar__nav">
        <ul class="lu-nav lu-nav--h lu-is-left">
            {foreach from=$rawObject->getButtons() key=buttonKey item=button}
                {if $button->getButtons()}
                    <li class="lu-nav__item has-dropdown">
                        {$button->getHtml()}
                    </li>
                {else}
                    <li class="lu-nav__item">
                        {$button->getHtml()}
                    </li>
                {/if}
            {/foreach}
        </ul>
    </div>
</div>