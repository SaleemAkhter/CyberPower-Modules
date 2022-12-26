<div id="timeChart" class="box light">
    <div class="box-title">
        <div class="caption">
            <i class="fa fa-bar-chart font-red-thunderbird"></i>
            <span class="caption-subject bold font-thunderbird">
                {$MGLANG->T('TableTitleTimeChart')}
            </span>
        </div>        
    </div>
    <div class="box-body">
        <div class="graphView row">
            <div class="col-md-12">
                <div id="timeChart-income-chart">
                    <canvas height='450'></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    {include file='timeChart/controller.js'}
</script>

