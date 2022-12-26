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
<div class="lu-input-group__addon lu-input-data-picker"><i class="lu-zmdi lu-zmdi-calendar"></i></div>
<mg-component-body-{$elementId|strtolower}
        component_id='{$elementId}'
        component_namespace='{$namespace}'
        component_index='{$rawObject->getIndex()}'
        predefined_date='{$rawObject->getValue()}'
        date_format = '{$rawObject->getDateFormat()}'
></mg-component-body-{$elementId|strtolower}>
