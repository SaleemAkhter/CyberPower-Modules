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

<i class="lu-zmdi lu-zmdi-folder" v-if="dataRow.type == 'directory'"></i>
<i class="lu-zmdi lu-zmdi-file-text" v-else></i>
<a {foreach $htmlAttributes as $aValue} {$aValue@key}="{$aValue}" {/foreach}
    v-if="dataRow.type == 'directory'"
    @click="makeCustomAction('fileManagerNextPage', [dataRow.name],$event)"
    v-html="dataRow.name" >
</a>
<a {foreach $htmlAttributes as $aValue} {$aValue@key}="{$aValue}" {/foreach}
    v-else
    @click="makeCustomAction('fileManagerDownloadFile', ['{$rawObject->getId()}'], $event,'{$rawObject->getNamespace()}' , '{$rawObject->getIndex()}')"
    v-html="dataRow.name"
    {literal}:name="dataRow.name"{/literal}
    >
</a>