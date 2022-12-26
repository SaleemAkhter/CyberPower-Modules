<div id="accountsChart" class="box light">
    <div class="box-title">
        <div class="caption">
            <i class="fa fa-bar-chart font-red-thunderbird"></i>
            <span class="caption-subject bold font-thunderbird">
                {$MGLANG->T('TableTitleAccountsChart')}
            </span>
        </div>               
    </div>
    <div class="box-body">
        <div class="graphView row">
            <div class="col-md-12">
                <div id="accountsChart-income-chart">
                    <canvas height='300'></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    {include file='accountsChart/controller.js'}
</script>