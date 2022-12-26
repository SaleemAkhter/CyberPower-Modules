{**********************************************************************
* GoogleCloudVirtualMachines product developed. (2019-04-26)
*
*
*  CREATED BY MODULESGARDEN       ->       http://modulesgarden.com
*  CONTACT                        ->       contact@modulesgarden.com
*
*
* This software is furnished under a license and may be used and copied
* only  in  accordance  with  the  terms  of such  license and with the
* inclusion of the above copyright notice.  This software  or any other
* copies thereof may not be provided or otherwise made available to any
* other person.  No title to and  ownership of the  software is  hereby
* transferred.
*
*
**********************************************************************}

{**
* @author Sławomir Miśkowicz <slawomir@modulesgarden.com>
*}

<script type="text/x-template" id="t-mg-vps-action-button-{$elementId|strtolower}"
        :component_id="component_id"
        :component_namespace="component_namespace"
        :component_index="component_index"
        :status_component_id="status_component_id"
>
    <div class="lu-col-xs-6 lu-col-md-20p">
        <a {foreach $htmlAttributes as $aValue} {$aValue@key}="{$aValue}" {/foreach} class="{$rawObject->getClasses()}"
            title="{$MGLANG->T('tooltip')}"
            :disabled="status==='disabled' || updateInProgress" :hidden="status==='hidden'">
            <div class="lu-i-c-6x">
                <div v-show="loading" class="lu-preloader-container"><div class="lu-preloader lu-preloader--sm"></div></div>
                <img v-show="!loading" src="{$rawObject->getIconFileName()}" alt="">
            </div>
            {if $rawObject->isRawTitle()}
                <span class="lu-tile__title">{$rawObject->getRawTitle()}</span>
            {elseif $rawObject->getTitle()}
                <span class="lu-tile__title">{$MGLANG->T($rawObject->getTitle())}</span>
            {/if}
        </a>
    </div>
</script>
