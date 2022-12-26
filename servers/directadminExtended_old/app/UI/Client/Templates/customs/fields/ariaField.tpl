<div class="lu-list-group lu-list-group--collapse lu-list-group--simple">
    <div class="lu-list-group__item">
        <div class="lu-list-group__top lu-top" data-toggle="lu-collapse" data-target="#collapse{$rawObject->getName()}" data-parent="#accordion{$rawObject->getName()}" aria-expanded="false">
            <span class="lu-collapse-icon"></span>
            <div class="lu-top__title lu-type-6">{if $rawObject->getRawTitle()}{$rawObject->getRawTitle()}{else}{$MGLANG->T($rawObject->getTitle())}{/if}</div>
        </div>
        <div class="lu-collapse" id="collapse{$rawObject->getName()}" >
            <div class="lu-list-group__content lu-p-l-6x">
                <div class="lu-according-inner">
                    <div class="lu-tiles lu-row lu-row--eq-height lu-neg-m-b-2x">
                            {foreach from=$rawObject->getList() key=k item=v}
                                {$v->getHtml()}
                            {/foreach}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
