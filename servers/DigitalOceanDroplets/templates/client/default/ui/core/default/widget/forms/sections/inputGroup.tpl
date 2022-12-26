{**********************************************************************
* ModuleFramework product developed. (2018-03-26)
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

<div class="lu-form-group">
    <label class="lu-form-label">
        {if $rawObject->isRawTitle()}{$rawObject->getRawTitle()}{elseif $rawObject->getTitle()}{$MGLANG->T($rawObject->getTitle())}{/if}
    </label>
        <div class="lu-input-group">
            {foreach from=$rawObject->getFields() item=field}
                {$field->getHtml()}
            {/foreach}
        </div>
    <div class="lu-form-feedback lu-form-feedback--icon" hidden="hidden">
    </div>    
</div>
