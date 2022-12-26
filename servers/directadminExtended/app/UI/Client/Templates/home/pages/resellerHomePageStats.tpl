{if $lastupdate}
        <div class="col-sm-10 col-sm-offset-1">
            <div class="col-sm-6">
                <h4>{$LANG.diskSpace}</h4>
                <input type="text" value="{$diskpercent|substr:0:-1}" class="dial-usage" data-width="100" data-height="100" data-min="0" data-readOnly="true" />
                <p>{$diskusage}MB / {$disklimit}MB</p>
            </div>
            <div class="col-sm-6">
                <h4>{$LANG.bandwidth}</h4>
                <input type="text" value="{$bwpercent|substr:0:-1}" class="dial-usage" data-width="100" data-height="100" data-min="0" data-readOnly="true" />
                <p>{$bwusage}MB / {$bwlimit}MB</p>
            </div>
        </div>
        <div class="clearfix">
        </div>
        <p class="text-muted">{$LANG.clientarealastupdated}: {$lastupdate}</p>

        {* <script src="{$BASE_PATH_JS}/jquery.knob.js"></script>
        <script type="text/javascript">
        jQuery(function() {ldelim}
            jQuery(".dial-usage").knob({ldelim}'format':function (v) {ldelim} alert(v); {rdelim}{rdelim});
        {rdelim});
        </script> *}
{/if}
asakjkjkj
