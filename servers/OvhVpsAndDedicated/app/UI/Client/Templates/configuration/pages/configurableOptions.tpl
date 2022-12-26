<div class="lu-widget widgetActionComponent{$class}" id="{$elementId}" {foreach from=$htmlAttributes key=name item=data} {$name}="{$data}"{/foreach}>
    <div class="lu-widget__header">
        <div class="lu-widget__top lu-top">
            <div class="lu-top__title">
                {$title}
            </div>
        </div>
        <div class="lu-top__toolbar">

        </div>
    </div>

    <div class="lu-widget__body">  
        <div class="lu-widget__content ">  
            
            {if $rawObject->getOptions()}
                <div class="lu-row">
                {foreach from=$rawObject->getOptions() key=oKey item=oName}
                    <div class="lu-col-md-6 lu-p-r-4x configOption text-left">
                        <b> {$oKey}|{$oName}</b>  {* - {$MGLANG->T('description', $oKey)} *}
                    </div>
                {/foreach}
                </div>
            {/if}
            {foreach from=$rawObject->getButtons() key=setting item=dataElement}

                <div class="lu-col-md-12 lu-p-r-4x center text-center configOptionButton">
                    <a {foreach $dataElement->getHtmlAttributes() as $aValue} {$aValue@key}="{$aValue}" {/foreach} class="{$dataElement->getClasses()}">
                        {if $dataElement->getIcon()}
                            <span class="lu-btn__icon lu-btn__icon">
                                <i class="{$dataElement->getIcon()}"></i>
                            </span>{/if}
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

                {if $scriptHtml}
                    <script type="text/javascript">
                        {$scriptHtml}
                    </script>
                {/if}
