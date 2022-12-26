<div class="lu-row">
    <div class="lu-widget" style="box-shadow: none">
        <div class="lu-widget__header">
            {if $rawObject->isRawTitle() || $rawObject->getTitle()}
                <div class="lu-widget__top lu-top">
                    <div class="lu-top__title">
                        {if $rawObject->getIcon()}<i class="{$rawObject->getIcon()}"></i>{/if}
                        {if $rawObject->isRawTitle()}{$rawObject->getRawTitle()}{elseif $rawObject->getTitle()}{$MGLANG->T($rawObject->getTitle())}{/if}
                    </div>
                </div>
            {/if}
            <div class="lu-widget__nav swiper-container swiper-container-horizontal swiper-container-false" data-content-slider="" style="visibility: visible;">
                {assign var="firstArrKey" value=$rawObject->getElements()|array_keys}
                {if $elements}
                    <ul class="lu-nav lu-nav--md lu-nav--h lu-nav--tabs lu-nav--arrow">
                        {foreach from=$rawObject->getElements() key=tplKey item=tplValue}
                            <li {if $tplKey  === $firstArrKey[0]} class="lu-nav__item is-active" {else} class="lu-nav__item" {/if}>
                                <a class="lu-nav__link"  data-toggle="lu-tab" href="#contTab{$tplValue->getId()}">
                                    <span class="lu-nav__link-text">{if $tplValue->isRawTitle()}{$tplValue->getRawTitle()}{elseif $tplValue->getTitle()}{$MGLANG->T($tplValue->getTitle())}{/if}</span>
                                </a>
                            </li>
                        {/foreach}
                    </ul>
                {/if}
            </div>
        </div>
        <div class="lu-widget__body">
            <div class="lu-tab-content">
                {foreach from=$rawObject->getElements() key=tplKey item=tplValue}
                    <div id="contTab{$tplValue->getId()}" class="lu-tab-pane {if $tplKey === $firstArrKey[0]} is-active {/if}" style="background-color: #e9ebf0;">
                        {$tplValue->getHtml()}
                    </div>
                {/foreach}
            </div>
        </div>
    </div>
</div>