{**********************************************************************
* WordpressManager product developed. (2017-09-08)
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

<a {foreach $htmlAttributes as $aValue} {$aValue@key}="{$aValue}" {/foreach} class="{$rawObject->getClasses()}">
    {if $rawObject->getIcon()}
        <span class="btn__icon btn__icon--left">
            <i class="{$rawObject->getIcon()}"></i>
        </span>
    {/if}
    <span class="btn__text">
{if $rawObject->isRawTitle()}{$rawObject->getRawTitle()}{elseif $rawObject->getTitle()}{$MGLANG->controlerContextT('button', $rawObject->getTitle())}{/if}    
</span>
</a>
