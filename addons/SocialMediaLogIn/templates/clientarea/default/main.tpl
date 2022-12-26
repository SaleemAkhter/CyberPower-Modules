<div class="mg-module">
    <input type="hidden" id="product_id" value="{$_params.serviceid}" /> 
    <script type="text/javascript" src="{$assetsURL}/js/mgLibs.js"></script>
    <link href="{$assetsURL}/css/bootstrap.min.css" rel="stylesheet">
    <link href="{$assetsURL}/css/font-awesome.min.css" rel="stylesheet">
    <!--- ################ -->
    <link href="{$assetsURL}/css/layout.css" rel="stylesheet">
    <link href="{$assetsURL}/css/theme.css" rel="stylesheet">

    {if !$WHMCS6}<script src="{$assetsURL}/js/bootstrap.min.js" type="text/javascript"></script>{/if}
    <script src="{$assetsURL}/js/bootsrap.tooltip.js" type="text/javascript"></script>

    <script src="{$assetsURL}/js/validator.js" type="text/javascript"></script>
    <script src="{$assetsURL}/js/app.js" type="text/javascript"></script>
    <script src="{$assetsURL}/js/zxcvbn.js" type="text/javascript"></script>

    <script type="text/javascript">
        JSONParser.create('{$mainJSONURL}');
    </script>
  
    <div id="mg-wrapper" class="module-container">
        <div class="module-content" {if $WHMCS6}style="margin-left: initial;"{/if}>
            <div class="row" id="MGAlerts">
                    {if $error}
                        <div class="alert alert-danger">
                            <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only"></span></button>
                            <p><strong>{$error}</strong></p>
                        </div>
                    {/if}
                    {if $success}
                        <div class="alert alert-success">
                            <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only"></span></button>
                            <p><strong>{$success}</strong></p>
                        </div>
                    {/if}
                    <div style="display:none;" data-prototype="error">
                        <div class="alert alert-danger">
                            <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only"></span></button>
                            <strong></strong>
                            <a style="display:none;" class="errorID" href=""></a>
                        </div>
                    </div>
                    <div style="display:none;" data-prototype="success">
                        <div class="alert alert-success">
                            <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only"></span></button>
                            <strong></strong>
                        </div>
                    </div>
            </div>
            <div id="mg-container">
                {$content}
            </div>
        </div>
        <div class="row-fluid"> 
            <div id="mg-modal">

            </div>
        </div>
    {literal}
        <script type="text/javascript">
            $(window).on('resize', function () {
                var height = $('.module-sidebar').height();
                $('#mg-wrapper').css('min-height', height);
            });
        </script>
    {/literal}
</div>