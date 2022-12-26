<div class="h4 lu-m-b-3x lu-m-t-3x">{$MGLANG->absoluteT('serverCA','home','pagesHeader')}</div>
<div class="lu-tiles lu-row lu-row--eq-height">
    {foreach from=$rawObject->getPages() key=controller item=settings}
        <div class="lu-col-sm-20p" style="justify-content: center;">
            <a class="lu-tile lu-tile--btn" href="{$rawObject->getURL($controller)}">
                <div class="lu-i-c-6x">
                    <img src="{$rawObject->getImageUrl($controller)}"  alt="{$MGLANG->absoluteT('serverCA' , 'iconTitle' ,$controller)}" />
                </div>
                <div class="lu-tile__title">{$MGLANG->absoluteT('serverCA' , 'iconTitle' ,$controller)}</div>
            </a>
        </div>
    {/foreach}
</div>

