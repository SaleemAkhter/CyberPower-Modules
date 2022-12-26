<div class="h4 lu-m-b-3x lu-m-t-3x">{$MGLANG->absoluteT('serverCA','home','manageActions')}</div>
<div class="lu-tiles lu-row lu-row--eq-height">
    {foreach from=$rawObject->getButtons() key=setting item=dataElement}
        {$dataElement->getHtml()}
    {/foreach}
</div>
