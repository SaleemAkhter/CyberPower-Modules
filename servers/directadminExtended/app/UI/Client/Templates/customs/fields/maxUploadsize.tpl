{**********************************************************************
* ModuleFramework product developed. (2017-10-30)
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
* @author Sławomir Miśkowicz <slawomir@modulesgarden.com>
*}
<div class="lu-form-group" {foreach $htmlAttributes as $aValue} {$aValue@key}="{$aValue}" {/foreach}>
    <label class="lu-form-label">
        {if $rawObject->isRawTitle()}{$rawObject->getRawTitle()}{elseif $rawObject->getTitle()}{$MGLANG->T($rawObject->getTitle())}{/if}
        {if $rawObject->getDescription()}
            <i data-title="{$MGLANG->T($rawObject->getDescription())}" data-toggle="lu-tooltip" class="lu-i-c-2x lu-zmdi lu-zmdi-help-outline lu-form-tooltip-helper lu-tooltip "></i>
        {/if}
    </label>
</div>
<div class="row">

    <div class="col-xs-4 col-lg-2">
        <select name="unit" class="form-control" id="unit">
            <option value="B" {if $rawObject->getUnit() eq "B"}selected{/if}>B</option>
            <option value="KB" {if $rawObject->getUnit() eq "KB"}selected{/if}>KB</option>
            <option value="MB" {if $rawObject->getUnit() eq "MB"}selected{/if}>MB</option>
            <option value="GB" {if $rawObject->getUnit() eq "GB"}selected{/if}>GB.</option>
            <option value="TB" {if $rawObject->getUnit() eq "TB"}selected{/if}>TB</option>
        </select>
    </div>
    <div class="col-xs-8 col-lg-3">
        <input class="lu-form-control" type="text" placeholder="{$rawObject->getPlaceholder()}" name="{$rawObject->getName()}"
           value="{$rawObject->getValue()}" {if $rawObject->isDisabled()}disabled="disabled"{/if}>
    </div>
</div>
