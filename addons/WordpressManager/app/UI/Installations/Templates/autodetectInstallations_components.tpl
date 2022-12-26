<script type="text/x-template" id="t-mg-wp-autodetectInstallations-{$elementId|strtolower}"
        :component_id="component_id"
        :component_namespace="component_namespace"
        :component_index="component_index"
>

    <div id="mg-wp-autodetectInstallations" class="shadow1">
        <div class="alert alert-warning text-center">Click on the button below to start scanning manual installations and import them into Softaculous.</div>
        <form accept-charset="UTF-8" id="importsoftware" method="post" action="" onsubmit="return checkform();">
            <p align="center">
                <a type="button" name="start_manual_import" id="start_manual_import" @click="import_all($event)" value="Start scanning for installations" class="btn btn-primary">Start scanning for installations</a>
            </p>
    </form>
    <div class="installations-table mb-4 hidden">
        <table width="100%" cellpadding="5" cellspacing="1" class="table table-hover">
            <thead class="sai_head2">
                <tr>
                <th width="45%" align="left" class="sai_head">URL</th><th></th>
                </tr>
            </thead>
            <tbody class="sai_head">

                </tbody>
            </table>

        </div>

    </div>
</script>
