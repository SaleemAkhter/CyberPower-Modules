<link rel="stylesheet" href="../modules/addons/wgs_cloudflare/assets/css/style.css">
<link rel="stylesheet" href="../modules/addons/wgs_cloudflare/assets/css/fonts.css">
<!--<script type="text/javascript" src="../modules/addons/wgs_cloudflare/assets/js/script.js"></script>-->
<ul class="nav nav-pills">
    <li><a href="<?php echo $modulelink; ?>"><i class="wgs-flat-icon flaticon-line-graph" aria-hidden="true"></i> <?php echo $_lang['Dashboard']; ?></a></li>
    <li><a href="<?php echo $modulelink; ?>&action=settings"><i class="wgs-flat-icon flaticon-settings" aria-hidden="true"></i> <?php echo $_lang['config_Setting']; ?></a></li>
    <li><a href="<?php echo $modulelink; ?>&action=product"><i class="wgs-flat-icon flaticon-file"></i> <?php echo $_lang['product_Setting']; ?></a></li>
    <li class="active"><a href="<?php echo $modulelink; ?>&action=zoneslist"><i class="wgs-flat-icon flaticon-file"></i> <?php echo $_lang['zone_list']; ?></a></li>
</ul>
<div class="container-fluid links">
    <div class="col-md-12  ">
        <div class="col-md-10">
            <div class="n-base">
                <a href="<?php echo $modulelink; ?>"> <?php echo $_lang['Dashboard']; ?></a>&nbsp;/&nbsp; <?php echo $_lang['zone_list']; ?>&nbsp;/&nbsp;
            </div>
        </div>
        <!-- <div class="col-md-2">
             <a href="addonmodules.php?module=wgs_cloudflare" class="btn btn-info pull-right" id="btnback"><?php echo $_lang['btn-go_back']; ?></a>
        </div> -->
    </div>
</div>

<div class="container-fluid" id="product-container">
    <div class="row">
        <div id="response_data"></div>
    </div>

    <div class="row tbl">

        <table class="table table-striped table-bordered text-capitalize" id="datatbl">
            <thead>
                <tr>
                    <th width="10%"><?php echo $_lang['zonename']; ?></th>
                    <th width="12%"><?php echo $_lang['zonestatus']; ?></th>
                    <th width="11%"><?php echo $_lang['development_mode']; ?></th>
                    <th width="10%"><?php echo $_lang['name_servers']; ?></th>
                    <th width="8%"><?php echo $_lang['original_name_servers']; ?></th>
                    <th width="10%"><?php echo $_lang['plan']; ?></th>
                </tr>
            </thead>
            <tbody>
                <script>
                    $(document).ready(function() {
                        $('#datatbl').DataTable({
                            "iDisplayLength": 10,
                            "bLengthChange": false,
                            "ajax": "<?php echo $modulelink; ?>&action=zoneajax"
                        });
                    });
                </script>
            </tbody>
        </table>
    </div>
</div>
<!--<script>
    $(document).ready(function () {
        $('#datatbl').DataTable({
            "oLanguage": {
                "sLengthMenu": "Display _MENU_ records",
            }
        });
    });
</script>-->