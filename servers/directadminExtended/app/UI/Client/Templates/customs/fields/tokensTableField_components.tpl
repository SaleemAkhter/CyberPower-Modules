{**********************************************************************
* DirectAdminExtended product developed. (2018-11-29)
* *
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
* @author Mateusz Pawłowski <mateusz.pa@moduelsagrden.com>
*}

<script type="text/x-template" id="t-mg-tokens-table-{$elementId|strtolower}">
    <div id="tokens-table">
        <div class="lu-input-group" >
            <input class="lu-form-control" autocomplete="off" type="text" placeholder="{$rawObject->getPlaceholder()}" name="{$rawObject->getName()}" v-model="search"
                {if $rawObject->isDisabled()}disabled="disabled"{/if}
               {foreach $htmlAttributes as $aValue} {$aValue@key}="{$aValue}" {/foreach}>

        </div>
        <table class="lu-table lu-table--mob-collapsible dataTable no-footer dtr-column table-striped">
            <thead>
                <tr>
                    <th>{$MGLANG->T("Token")}</th>
                    <th>{$MGLANG->T("Value")}</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(value,option ) in tokens">
                    <td v-text="option"></td>
                    <td v-text="value"></td>
                </tr>
            </tbody>
        </table>
    </div>
</script>
