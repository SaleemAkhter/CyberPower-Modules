<div class="panel-group" id="accordion">
    <?php
    if (!$error) {
        foreach ($datacenter as $key => $value) {
    ?>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title dctitle">
                        <a data-toggle="collapse" title="Datacenter" data-parent="#accordion" href="#<?php echo $value->reference->_; ?>" onclick="getDcHostSetting(this, '<?php echo $value->reference->_; ?>', '<?php echo $value->name; ?>');"><?php echo ucfirst($value->name); ?></a>
                    </h4>
                </div>
                <div id="<?php echo $value->reference->_; ?>" class="panel-collapse collapse">
                    <div class="panel-body">
                        <script>
                            //getDcHostSetting('<?php echo $value->reference->_; ?>', '<?php echo $value->name; ?>');
                        </script>
                        <div class="col-sm-12 text-center" id="loader-<?php echo $value->reference->_; ?>"><i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i></div>
                        <!-- <form id="form-<?php echo $value->reference->_; ?>"> -->
                        <div id="dchtml-<?php echo $value->reference->_; ?>"></div>

                        <!-- <div class="col-sm-12 text-center">
                                <button type="button" id="btn-<?php echo $value->reference->_; ?>" class="btn btn-default" onclick="saveHostSetting(this, '<?php echo $value->reference->_; ?>')">Submit</button>
                            </div>
                        </form> -->
                    </div>
                </div>
            </div>
    <?php
        }
    } else {
        echo '<div class="errorbox"><strong><span class="title">Error!</span></strong><br>' . $error . '</div>';
    }
    ?>
</div>

<div id="growls" class="default"></div>