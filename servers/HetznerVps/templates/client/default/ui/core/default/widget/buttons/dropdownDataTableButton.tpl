{**********************************************************************
* ModuleFramework product developed. (2017-09-05)
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
* @author Michal Zarow <michal.za@modulesgarden.com>
*}

<li class="lu-dropdown__item" {$rawObject->getShowButton()}>
    <a class="lu-dropdown__link" 
       {foreach $htmlAttributes as $aValue} {$aValue@key}="{$aValue}" {/foreach}
 {if $rawObject->isRawTitle()}title="{$rawObject->getRawTitle()}"{elseif $rawObject->getTitle()}title="{$MGLANG->T('button', $rawObject->getTitle())}"{/if}>
        <span class="lu-dropdown__link-icon">
            <i class="{$rawObject->getIcon()}"></i>
        </span> 
       <span class="lu-dropdown__link-text">{if $rawObject->isRawTitle()}{$rawObject->getRawTitle()}{elseif $rawObject->getTitle()}{$MGLANG->T('button', $rawObject->getTitle())}{/if}</span>
    </a>
</li>
