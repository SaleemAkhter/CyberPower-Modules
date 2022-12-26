{$headerhtml}
{$menu}
<div class="cfcontent">
    <h3 class="cfcontentmargin">{$wgs_lang.cf_plan}</h3>
</div>

<div class="cfinternal">
    {if $error}
        <div class="cfa_error">
            <span></span>
            {$error}
        </div>
    {/if}
    {if $actionsucess}
        <div class="cfa_success">
            <span></span>
            {$actionsucess}
        </div>
    {/if}

    <table class="cfcontenttabletype2">
        <tr>
            <td style="width: 98% !important">
                <h3>{$wgs_lang.cf_current_plan}</h3>
                <div class="cfgraphinfo">
                    <b class="cfgraphskydark">{$wgs_lang.cf_plan}: {$currentplan.legacy_id|@ucfirst}</b>

                    <b class="cfgraphblue">{$wgs_lang.cf_plan_currency}: {$currentplan.currency}</b>
                    {if $currentplan.legacy_id neq "free"}
                        <b class="cfgraphyellow">{$wgs_lang.cf_plan_frequency}: {$currentplan.frequency}</b>
                    {/if}
                </div>
            </td>
        </tr>
    </table>

</div>
{$cffooter}
