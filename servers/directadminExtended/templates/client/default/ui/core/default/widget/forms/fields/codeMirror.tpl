<div class="form-group">
    <label class="form-label">
        {if $rawObject->isRawTitle()}{$rawObject->getRawTitle()}{elseif $rawObject->getTitle()}{$MGLANG->T($rawObject->getTitle())}{/if}
        {if $rawObject->getDescription()}
            <i data-title="{$MGLANG->T($rawObject->getDescription())}" data-toggle="tooltip" class="lu-i-c-2x lu-zmdi lu-zmdi-help-outline lu-form-tooltip-helper "></i>
        {/if}
    </label>
    <mg-field
            v-bind:fieldidprop="'{$rawObject->getId()}'"
            v-bind:nameprop="'{$rawObject->getName()}'"
            v-bind:namespaceprop="'{$rawObject->getNamespace()}'"
            v-bind:indexprop="'{$rawObject->getIndex()}'"
            v-bind:valueprop="'{$rawObject->getValue()}'"
            inline-template>
        <md-codemirror class="form-control mg-codemirror" v-model="value" ></md-codemirror>
    </mg-field>
    <input name="{$rawObject->getName()}" id="{$rawObject->getId()}"  type="hidden" />
    <div class="form-feedback form-feedback--icon" hidden="hidden"></div>
</div>
