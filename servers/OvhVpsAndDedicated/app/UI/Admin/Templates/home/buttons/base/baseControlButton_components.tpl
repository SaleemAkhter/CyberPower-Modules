<script type="text/x-template" id="t-base-control-button-{$elementId|strtolower}"
        :component_id="component_id"
        :component_namespace="component_namespace"
        :component_index="component_index"
>
        <div class="lu-col-sm-20p" style="justify-content: center;">
            <a class="lu-tile lu-tile--btn  {$rawObject->getClasses()}" {foreach $rawObject->getHtmlAttributes() as $aValue} {$aValue@key}="{$aValue}" {/foreach}
            data-title="{$MGLANG->absoluteT('buttons','actions', $rawObject->getTitle())}"
            >
            <div class="lu-i-c-6x">
                <img src="{$rawObject->getImage()}"  alt="{$MGLANG->absoluteT('serverCA' , 'iconTitle' ,$rawObject->getTitle())}" v-show="!loading_state" />
                <span class="lu-btn__icon lu-temp-button-loader" style="margin: 0 0 0 0 !important;"><i class="lu-btn__icon lu-preloader lu-preloader--sm" v-show="loading_state" ></i></span>
            </div>
            <div class="lu-tile__title">{$MGLANG->absoluteT('serverCA' , 'iconTitle' ,$rawObject->getTitle())}</div>
            </a>
        </div>
</script>
