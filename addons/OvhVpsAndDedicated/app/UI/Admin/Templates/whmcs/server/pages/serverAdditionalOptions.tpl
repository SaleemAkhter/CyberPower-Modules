<table>

    <div id="mg-ovh-integration">

        <tr class="mg-server-integration-row">
            <td class="fieldlabel">Location</td>
            <td class="fieldarea">
                <select name="endPoint" id="inputOvhLocation" class="ovhCustomInput">
                    {foreach from=$customTplVars.endPoints item=value}
                        <option value="{$value}" {if $value eq $customTplVars.selectedEndPoint}selected{/if}>{$value}</option>
                    {/foreach}
                </select>
            </td>
        </tr>

        <tr class="mg-server-integration-row">
            <td class="fieldlabel">Country</td>
            <td class="fieldarea">
                <select name="ovhSubsidiary" id="inputOvhCountry" class="ovhCustomInput">
                    {foreach from=$customTplVars.ovhSubsidiaries item=value}
                        <option value="{$value}" {if $value eq $customTplVars.selectedCountry}selected{/if}>{$value}</option>
                    {/foreach}
                </select>
            </td>
        </tr>

        <tr class="mg-server-integration-row">
            <td class="fieldlabel">OVH Server Type</td>
            <td class="fieldarea">
                <select name="ovhServerType" id="inputOvhServerType" class="ovhCustomInput">
                    {foreach from=$customTplVars.ovhServerType item=value}
                        <option value="{$value}" {if $value eq $customTplVars.selectedOvhServerType}selected{/if}>{$MGLANG->T({$value})}</option>
                    {/foreach}
                </select>
            </td>
        </tr>
    </div>
</table>