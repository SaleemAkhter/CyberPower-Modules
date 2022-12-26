<div class="lu-widget widgetActionComponent{$class}" id="{$elementId}" {foreach from=$htmlAttributes key=name item=data} {$name}="{$data}"{/foreach}>
    <div class="lu-widget__header">
        <div class="lu-widget__top top">
            <div class="lu-top__title">
                {$MGLANG->T($title)}

            </div>
        </div>
        <div class="lu-top__toolbar">

        </div>
    </div>

    <div class="lu-widget__body">  
        <div class="lu-widget__content">  
            {foreach from=$elements key=nameElement item=dataElement}
                <div id="{$dataElement->getId()}" class="{$dataElement->getClass()}">
                    {$dataElement->getHtml()}
                </div>
            {/foreach}

            {foreach from=$rawObject->getButtons() key=setting item=dataElement}
                {*{$dataElement->getHtml()}*}
                <div class="lu-col-md-12 lu-p-r-4x configOptionButton">
                    <a {foreach $dataElement->getHtmlAttributes() as $aValue} {$aValue@key}="{$aValue}" {/foreach} class="{$dataElement->getClasses()}">
                            {if $dataElement->getIcon()}
                                <i class="{$dataElement->getIcon()}"></i>
                            {/if}
                            {if $dataElement->isRawTitle()}
                                <span class="lu-btn__text">{$dataElement->getRawTitle()}</span>
                            {elseif $dataElement->getTitle()}
                                <span class="lu-btn__text">{$MGLANG->T('button', $dataElement->getTitle())}</span>
                            {/if}
                        </a>
                    </div>
            {/foreach}
            </div>
        </div>
    </div>

    {if $scriptHtml}
        <script type="text/javascript">
            {$scriptHtml}
        </script>
    {/if}
