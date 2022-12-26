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

<script type="text/x-template" id="t-mg-date-picker-{$elementId|strtolower}"
    :component_id="component_id"
    :component_namespace="component_namespace"
    :component_index="component_index"
    :predefined_date="predefined_date"
    :date_format="date_format"
>
        <vuejs-datepicker
                placeholder="{$rawObject->getPlaceholder()}"
                name="{$rawObject->getName()}"
                calendar-class="lu-data-picker"
                input-class="lu-form-control"
                :value="date"
                :format="customFormatter"
                {if $rawObject->isDisabled()}disabled='disabled'{/if}
                {foreach $htmlAttributes as $aValue} {$aValue@key}="{$aValue}" {/foreach}
        ></vuejs-datepicker>

</script>
