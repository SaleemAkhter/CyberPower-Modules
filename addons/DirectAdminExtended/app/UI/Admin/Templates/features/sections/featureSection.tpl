{**********************************************************************
* DirectAdminExtended product developed. (2017-10-06)
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

<div class="lu-widget lu-col-md-3 lu-p-r-4x featureBox" name="{$rawObject->getTitle()}" >
    <div class="lu-widget__header">
            <div class="lu-widget__top lu-top">
                <div class="lu-top__title">
                    {if $rawObject->getIcon()}<i class="{$rawObject->getIcon()}"></i>{/if}
                     {$rawObject->getTitle()}
                    
                </div>
          
            <div class="lu-top__toolbar" style="padding-top:15px;">
                {$rawObject->getTooltip()->getHtml()}
            </div>
              </div>
        </div> 
    <div class="lu-widget__body">
        <div class="lu-widget__content" >
            {foreach from=$rawObject->getFields() item=field }
                {$field->getHtml()}
            {/foreach}
        </div>
    </div>
</div>