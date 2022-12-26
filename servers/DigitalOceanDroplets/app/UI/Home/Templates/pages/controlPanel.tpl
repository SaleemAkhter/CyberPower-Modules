{if $rawObject->getButtons()}
<div class="h4 lu-m-b-3x lu-m-t-3x">{$MGLANG->absoluteT('serverCA','home','manageHeader')}</div>
<div class="lu-tiles lu-row lu-row--eq-height">
    {foreach from=$rawObject->getButtons() key=setting item=dataElement}
        <div class="lu-col-sm-20p" style="justify-content: center;">
            <a class="lu-tile lu-tile--btn  {$rawObject->getClasses()}" {foreach $dataElement->getHtmlAttributes() as $aValue} {$aValue@key}="{$aValue}" {/foreach}
                data-title="{$MGLANG->absoluteT('buttons','actions', $dataElement->getTitle())}"
               >
                <div class="lu-i-c-6x">
                    <img src="{$dataElement->getImage()}"  alt="{$MGLANG->absoluteT('serverCA' , 'iconTitle' ,$dataElement->getTitle())}" />
                </div>
                <div class="lu-tile__title">{$MGLANG->absoluteT('serverCA' , 'iconTitle' ,$dataElement->getTitle())}</div>
            </a>
        </div>
    {/foreach}
</div>
{/if}