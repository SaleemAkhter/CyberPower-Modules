
<script type="text/x-template" id="t-mg-config-hint-widget-{$elementId|strtolower}"
        :component_id="component_id"
        :component_namespace="component_namespace"
        :component_index="component_index"
        :force_open="force_open"
        :component_is_hidden="component_is_hidden"
>
    <transition name="slide-fade">
        <div class="lu-row lu-row--eq-height" id="{$rawObject->getId()}" v-show="show">
            <div class="lu-col-lg-12">
                <div class="lu-widget">
                    <div class="lu-widget__header">
                        <div class="lu-widget__top lu-top">
                            {if $rawObject->getRawTitle() || $rawObject->getTitle()}
                                <div class="lu-top__title">
                                    {if $rawObject->getIcon()}
                                        <i class="{$rawObject->getIcon()}"></i>
                                    {/if}
                                    {if $rawObject->isRawTitle()}
                                        {$rawObject->getRawTitle()}
                                    {elseif $rawObject->getTitle()}
                                        {$MGLANG->T($rawObject->getTitle())}
                                    {/if}
                                </div>
                                <div class="lu-top__toolbar">
                                    {foreach from=$rawObject->getHintsResults() key=hintName item=hintDetails}
                                        {if $hintName === $rawObject->getAllCompletedText() && !$rawObject->isForceOpened()}
                                            <a @click="hideHintsBox" data-toggle="lu-tooltip" class="lu-btn lu-btn--sm lu-btn--link lu-btn--icon lu-btn--plain lu-btn--default lu-tooltip" data-title="{$MGLANG->translate('hideButtonTitle')}"><i :class="closeButtonIconClass"></i></a>
                                        {/if}
                                    {/foreach}
                                </div>
                            {/if}
                        </div>
                    </div>
                        <div class="lu-widget__body">
                            <div  class="lu-widget__content">
                                {foreach from=$rawObject->getHintsResults() key=hintName item=hintDetails}
                                    {if $hintName !== $rawObject->getAllCompletedText() && $rawObject->isForceOpened()}
                                        <div class="lu-alert lu-hint--{if $hintDetails['isConfigured']}success{else}danger{/if} lu-alert--faded modal-alert-top">
                                            <div class="lu-alert__body">
                                                <h6><i class="lu-btn__icon lu-zmdi lu-zmdi-{if $hintDetails['isConfigured']}check{else}alert-octagon{/if}"></i>{$MGLANG->translate('hintTitle', $hintName)|unescape:'html'}</h6>
                                                <hr>
                                                    {if $hintDetails['isConfigured']}
                                                        {$MGLANG->translate('hintDescriptionPassed', $hintName)|unescape:'html'}
                                                    {else}
                                                        {$MGLANG->translate('hintDescription', $hintName)|unescape:'html'}
                                                    {/if}
                                            </div>
                                        </div>
                                    {/if}
                                    {if $hintName === $rawObject->getAllCompletedText() && !$rawObject->isForceOpened()}
                                        <div class="lu-alert lu-hint--{if $hintDetails['isConfigured']}success{else}danger{/if} lu-alert--faded modal-alert-top">
                                            <div class="lu-alert__body">
                                                <h6><i class="lu-btn__icon lu-zmdi lu-zmdi-{if $hintDetails['isConfigured']}check{else}alert-octagon{/if}"></i>{$MGLANG->translate('hintTitle', $hintName)|unescape:'html'}</h6>
                                                <hr>
                                                {$MGLANG->translate('hintDescription', $hintName)|unescape:'html'}
                                            </div>
                                        </div>
                                    {/if}
                                {/foreach}
                            </div>
                        </div>
                </div>
            </div>
        </div>
    </transition>
</script>
