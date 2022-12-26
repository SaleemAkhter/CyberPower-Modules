<div class="lu-widget widgetActionComponent{$class}" id="{$elementId}" {foreach from=$htmlAttributes key=name item=data} {$name}="{$data}"{/foreach}>
    <div class="lu-widget__header">
        <div class="lu-widget__top top">
            <div class="lu-top__title">
                {$title}
            </div>
        </div>
        <div class="lu-top__toolbar">

        </div>
    </div>

    <div class="lu-widget__body">  
        <div class="lu-widget__content">  
            {foreach from=$elements key=nameElement item=dataElement}
                <div id="{$dataElement->getId()}" class="lu-row {$dataElement->getClass()}">
                    {$dataElement->getHtml()}
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
