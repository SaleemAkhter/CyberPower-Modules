{**********************************************************************
* ModuleFramework product developed. (2017-08-24)
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
<div class="lu-row">
    <div class="lu-col-md-12">
        <div class="{$class}" id="{$elementId}" {foreach from=$htmlAttributes key=name item=data} {$name}="{$data}"{/foreach}>
            <div class="lu-widget__body" style="padding: 15px;">
                {$MGLANG->absoluteT('csrKeyDesc')}
                <div class="lu-row" style="padding: 10px 5px 0px 5px;">
                    {foreach from=$rawObject->getButtons() item=button}
                        {$button->getHtml()}
                    {/foreach}
                </div>
            </div>
        </div>
    </div>
</div>
