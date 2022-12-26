<div class="lu-col-lg-12" {if $rawObject->getHidden()} style="visibility: hidden" {/if}>
    {if $rawObject->getSections()}
        <div class="lu-row">
        {foreach from=$rawObject->getSections() item=section}
            <div class="lu-col-lg-6"  style="{if $section->getId() === 'leftSide'} padding-left: 0px; {else} padding-right: 0px; {/if}">
                <div class="lu-widget">
                    <div class="lu-widget__header">
                        <div class="lu-widget__top lu-top">
                            <div class="lu-top__title">{$MGLANG->T($section->getTitle())}</div>
                        </div>
                    </div>
                    <div class="lu-widget__body">
                        <div class="lu-widget__content">
                            {$section->getHtml()}
                        </div>
                    </div>
                </div>
            </div>
        {/foreach} 
        </div>
    {/if}
</div>
                        

