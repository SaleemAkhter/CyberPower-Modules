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


<ul class="lu-breadcrumb lu-breadcrumb--angle-separator" style="padding: 0px 5px 5px 5px">
    {foreach from=$rawObject->getPages() item=page}
        <li class="lu-breadcrumb__item {if $rawObject->getCurrentAction() eq $page}is-active{/if}">
            <a class="lu-breadcrumb__link" {if $rawObject->getCurrentAction() eq $page}href="javascript:;"{else}href="{$rawObject->getUrl($page)}"{/if}>{$MGLANG->T($page, $rawObject->getTitle())}</a>
        </li>
    {/foreach}
</ul>
