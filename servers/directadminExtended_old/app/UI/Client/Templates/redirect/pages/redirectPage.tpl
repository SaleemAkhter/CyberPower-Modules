{**********************************************************************
* ModuleFramework product developed. (2017-10-04)
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
* @author Mateusz Pawłowski <mateusz.pa@modulesgarden.com>
*}

<div class="lu-container {$class}" id="{$elementId}">
    <h6>{if $rawObject->isRawTitle()}{$rawObject->getRawTitle()}{elseif $rawObject->getTitle()}{$MGLANG->T($rawObject->getTitle())}{/if}</h6>
    <div class="lu-row">
        {if $rawObject->getError()}
            <div class="lu-alert lu-alert--outline lu-alert--danger">
                <div class="lu-alert__body text-center">
                    {$MGLANG->absoluteTranslate('cantUseSitePad')}
                </div>
            </div>
        {else}
        <div class="lu-alert lu-alert--outline lu-alert--info">
            <div class="lu-alert__body text-center">
                {$MGLANG->absoluteTranslate('startRedirect')}
                <form action="{$rawObject->getRedirectURL()}" method="POST" id="redirectDirectAdminForm">
                <input type="hidden" name="username" value="{$rawObject->getUsername()}" />
                <input type="hidden" name="password" value="{$rawObject->getPassword()}" />
                <input type="submit" class="lu-btn lu-btn--primary lu-btn--xs" value="{$MGLANG->absoluteTranslate('clickHere')}">
                </form>
               
            </div>
        </div>
     {/if}
    </div>
</div>

