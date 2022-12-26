<div id="custom_autobackup_cron" class="lu-form-group" {if !$rawObject->getVisibility()}style="display: none;" {/if}>
    <div class="row">
    <div class="col-sm-12 col-xs-12">
        <label for="autobackup_cron_min" class="sai_head mb-2">Automated Backups Cron time</label>
        &nbsp;<a data-toggle="tooltip" data-html="true" title="" data-original-title="Please specify the CRON timings for automated backups"><i class="fas fa-info-circle" style="font-size:1.1em; vertical-align:middle;cursor:pointer;color:#4B4B4B;"></i></a><span class="sai_exp" style="display: none;">Please specify the CRON timings for automated backups</span>
    </div>
    <div class="col-sm-12 col-xs-12 mb-2">
        <div class="row">
            <div class="col-sm-2">
                <label for="autobackup_cron_min" class="sai_head mb-2">Min</label>
                <input type="text" class="lu-form-control" name="autobackup_cron_min" id="autobackup_cron_min" size="2" value="{$rawObject->autobackup_cron_min}">
            </div>
            <div class="col-sm-2">
                <label for="autobackup_cron_hour" class="sai_head mb-2">Hour</label>
                <input type="text" class="lu-form-control" name="autobackup_cron_hour" id="autobackup_cron_hour" size="2" value="{$rawObject->autobackup_cron_hour}">
            </div>
            <div class="col-sm-2">
                <label for="autobackup_cron_day" class="sai_head mb-2">Day</label>
                <input type="text" class="lu-form-control" name="autobackup_cron_day" id="autobackup_cron_day" size="2" value="{$rawObject->autobackup_cron_day}">
            </div>
            <div class="col-sm-2">
                <label for="autobackup_cron_month" class="sai_head mb-2">Month</label>
                <input type="text" class="lu-form-control" name="autobackup_cron_month" id="autobackup_cron_month" size="2" value="{$rawObject->autobackup_cron_month}">
            </div>
            <div class="col-sm-2">
                <label for="autobackup_cron_weekday" class="sai_head mb-2">Weekday</label>
                <input type="text" class="lu-form-control" name="autobackup_cron_weekday" id="autobackup_cron_weekday" size="2" value="{$rawObject->autobackup_cron_weekday}">
            </div>
        </div>
    </div>
    </div>
</div>
