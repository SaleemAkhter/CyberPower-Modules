<div class="mg-sidebar-wrapper">
    <div class="lu-widget">
        <div class="lu-widget__header">
            <div class="lu-widget__top top">
                <div class="lu-top__title">{$MGLANG->T($rawObject->getId())}</div>
            </div>
        </div>
        <div class="lu-widget__body">
            <div class="lu-widget__content">
                <ul class="lu-nav lu-nav--tabs lu-nav--border-left">
                    {foreach from=$rawObject->get() item=sidebarItem}
                        {$sidebarItem->getHtml()}
                    {/foreach}
                </ul>
            </div>
        </div>
    </div>
</div>
