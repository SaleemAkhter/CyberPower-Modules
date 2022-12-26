{$headerhtml}
{if $status eq 'Pending'}
    <div class="pending">
        <h3>{$wgs_lang.cf_congratulation}</h3>
        <p class="hometext">
            {$wgs_lang.cf_add_domain_success_msg}
            <br>
            {$wgs_lang.cf_home_ns_text}
        </p>
        <div class="internal">
            <span class="cfblue">{$wgs_lang.cf_home_org_ns}</span>
            <table class="cftable">
                <tbody>
                    {foreach from=$originalnameservers item=orignameserver}
                        <tr>
                            <th>{$wgs_lang.cf_home_ns}</th>
                            <td>{$orignameserver}</td>
                        </tr>
                    {/foreach}
                </tbody>
            </table>
        </div>
        <div>
            <span class="cfblue">{$wgs_lang.cf_home_cf_ns}</span>
            <table class="cftable">
                <tbody>
                    {foreach from=$cloudflarenameservers item=cfnameserver}
                        <tr>
                            <th>NS</th>
                            <td>{$cfnameserver}</td>
                        </tr>

                    {/foreach}
                </tbody>
            </table>
        </div>
        <div class="cfclear"></div>
        <span class="cfinfo">{$wgs_lang.cf_home_24_hours}</span>
    </div>
{/if}
</div>