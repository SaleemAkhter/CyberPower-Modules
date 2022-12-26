<li class="lu-nav__item">
    <a {foreach $htmlAttributes as $aValue} {$aValue@key}="{$aValue}" {/foreach} class="lu-nav__link {if $rawObject->isActive()}is-active{/if}">
        {if $rawObject->getIcon()}<i class="{$rawObject->getIcon()}"></i>{/if} 
        <span class="lu-btn__text">{$MGLANG->T($rawObject->getId())}</span>
    </a>
</li>
