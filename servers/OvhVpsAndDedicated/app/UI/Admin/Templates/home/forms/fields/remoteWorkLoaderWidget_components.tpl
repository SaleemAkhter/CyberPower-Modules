<script type="text/x-template" id="t-mg-remote-work-loader-{$elementId|strtolower}"
        :component_id="component_id"
        :component_namespace="component_namespace"
        :component_index="component_index">

        <div class="lu-row">

                {if $rawObject->haveInternalAlertMessage()}
                        <div class="lu-alert {if $rawObject->getInternalAlertSize() !== ''}lu-alert--{$rawObject->getInternalAlertSize()}{/if} lu-alert--{$rawObject->getInternalAlertMessageType()} lu-alert--faded modal-alert-top">
                                <div class="lu-alert__body">
                                        {if $rawObject->isInternalAlertMessageRaw()|unescape:'html'}{$rawObject->getInternalAlertMessage()}{else}{$MGLANG->T($rawObject->getInternalAlertMessage())|unescape:'html'}{/if}
                                </div>
                        </div>
                {/if}

                <div class="lu-col-md-12" style="min-height: 60px;">
                        <div class="lu-preloader-container lu-preloader-container--full-screen lu-preloader-container--overlay" v-show="state === 'loading'">
                                <div class="lu-preloader lu-preloader--lg"></div>
                        </div>
                </div>
        </div>
</script>
