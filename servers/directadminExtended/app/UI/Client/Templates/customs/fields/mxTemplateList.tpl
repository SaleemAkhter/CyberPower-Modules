{**********************************************************************
* DirectAdminExtended product developed. (2017-10-30)
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
    <div class="row">
        <div class="{if $rawObject->getClasses()}{$rawObject->getClasses()}{else} lu-form-group {/if}" class="lu-form-group">
            <div class="row">
                <div class="col-lg-6">
                    <label class="lu-form-label">
                        {if $rawObject->isRawTitle()}{$rawObject->getRawTitle()}{elseif $rawObject->getTitle()}{$MGLANG->T($rawObject->getTitle())}{/if}
                        {if $rawObject->getDescription()}
                        <i data-title="{$MGLANG->T($rawObject->getDescription())}" data-toggle="lu-tooltip" class="lu-i-c-2x lu-zmdi lu-zmdi-help-outline lu-form-tooltip-helper"></i>
                        {/if}
                    </label>

                    <select
                    class="lu-form-control"
                    name="{$rawObject->getName()}"
                    {if $rawObject->isDisabled()}disabled="disabled"{/if}
                    {foreach $htmlAttributes as $aValue} {$aValue@key}="{$aValue}" {/foreach}
                    {if $rawObject->isMultiple()}data-options="removeButton:true; resotreOnBackspace:true; dragAndDrop:true; maxItems: null;" multiple="multiple"{/if}
                    >
                    {if $rawObject->getValue()|is_array}
                    {foreach from=$rawObject->getAvailableValues() key=opValue item=option}
                    <option value="{$opValue}" {if $opValue|in_array:$rawObject->getValue()}selected{/if}>{$option}</option>
                    {/foreach}
                    {else}
                    {foreach from=$rawObject->getAvailableValues() key=opValue item=option}
                    <option value="{$opValue}" {if $opValue == $rawObject->getValue()}selected{/if}>{$option}</option>
                    {/foreach}
                    {/if}
                </select>
                <div class="lu-form-feedback lu-form-feedback--icon" hidden="hidden">
                </div>
            </div>
            <div class="col-lg-6">
                <div class="lu-form-check  lu-m-b-2x affectpointers">
                    <label>
                        <span class="lu-form-text">{$MGLANG->T("affectpointers")}</span>
                         <div class="lu-switch">
                            <input type="checkbox" name="affectpointers"  {if $rawObject->getAffectpointersValue() === 'on'}checked{/if} class="lu-switch__checkbox">
                             <span class="lu-switch__container"><span class="lu-switch__handle"></span></span>
                         </div>
                     </label>
                 </div>
            </div>
        </div>

    </div>

</div>
