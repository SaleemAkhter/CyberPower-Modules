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

    <div class="lu-form-group">
        <label class="lu-form-label">
            {if $rawObject->isRawTitle()}{$rawObject->getRawTitle()}{elseif $rawObject->getTitle()}{$MGLANG->T($rawObject->getTitle())}{/if}
            {if $rawObject->getDescription()}
            <i data-title="{$MGLANG->T($rawObject->getDescription())}" data-toggle="lu-tooltip" class="lu-i-c-2x lu-zmdi lu-zmdi-help-outline lu-form-tooltip-helper "></i>
            {/if}
        </label>
        {if $rawObject->getPrefixText() || $rawObject->getSuffixText()}
        <div class="input-group">
        {/if}
            {if $rawObject->getPrefixText() }

                <div class="input-group-addon">{if !$rawObject->getPrefixTranslated()}{$MGLANG->T($rawObject->getPrefixText())}{else} {$rawObject->getPrefixText()} {/if}</div>
            {/if}
          <input class="lu-form-control" type="text" placeholder="{$rawObject->getPlaceholder()}" name="{$rawObject->getName()}"
          value="{$rawObject->getValue()}" {if $rawObject->isDisabled()}disabled="disabled"{/if}
          {foreach $htmlAttributes as $aValue} {$aValue@key}="{$aValue}" {/foreach}>

          {if $rawObject->getSuffixText() }
              <div class="input-group-addon">{if !$rawObject->getSuffixTranslated()}{$MGLANG->T($rawObject->getSuffixText())}{else} {$rawObject->getSuffixText()} {/if}</div>
          {/if}
        {if $rawObject->getPrefixText() || $rawObject->getSuffixText()}
        </div>
        {/if}

      <div class="lu-form-feedback lu-form-feedback--icon" hidden="hidden">
      </div>
  </div>
