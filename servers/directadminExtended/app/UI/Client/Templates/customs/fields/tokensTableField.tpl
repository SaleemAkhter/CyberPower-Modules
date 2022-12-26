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

<div class="lu-form-group">
    <label class="lu-form-label">
        {$MGLANG->T('Filter Tokens')}
    </label>
    <mg-component-body-{$elementId|strtolower}
        component_id='{$elementId}'
        component_namespace='{$namespace}'
        component_index='{$rawObject->getIndex()}'
        component_tokens='{$rawObject->getTokens()}'
    ></mg-component-body-{$elementId|strtolower}>
    <div class="lu-form-feedback lu-form-feedback--icon" hidden="hidden">
    </div>
</div>