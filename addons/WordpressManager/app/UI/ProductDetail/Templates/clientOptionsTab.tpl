<form id="{$rawObject->getId()}" mgformtype="{$rawObject->getFormType()}" mgformtype="{$rawObject->getFormType()}"   namespace="{$namespace}" index="{$rawObject->getIndex()}">
    <div class="row">
        {foreach from=$rawObject->getSections() item=mainSection }
            {foreach from=$mainSection->getSections() item=section}
                <div class="col-md-6">
                    {assign var="sectionTitle" value="{$section->getName()}{'-title'}"}
                    <div style="margin:20px 0px 15px 8px">
                        <div class="lu-form-check lu-m-b-2x" style="margin-right:8px">
                            <label>
                            <span class="lu-form-text">
                                <b style="font-size:18px">{$MGLANG->T({$sectionTitle})}</b>
                            </span>
                                <div class="lu-switch">
                                    <input type="checkbox" class="lu-switch__checkbox" name="{$section->getName()}" checked @keyup.enter="submitFormByField($event)" >
                                    <span class="lu-switch__container">
                                    <span class="lu-switch__handle"></span>
                                </span>
                                </div>
                            </label>
                        </div>
                    </div>
                    <hr style="margin:0px;margin-bottom:10px">
                    <div id="{$section->getName()}">
                        {$section->getHtml()}
                    </div>
                </div>
            {/foreach}
        {/foreach}
        <div class="lu-app__main-actions" style="margin-bottom: 20px; margin-left: 20px">
                {$rawObject->getSubmitHtml()}
        </div>
    </div>
</form>

<literal>
    <script>
        var managementOptionsNumber = $('#management-section input[type="checkbox"]').length;
        var actionsOptionsNumber = $('#actions-section input[type="checkbox"]').length;

        $( document ).ready(function() {
            controlManagementCheckAllInput();
            controlActionsCheckAllInput();
        });

        $(document).on('change', 'input[name="management-section"]', function() {
            if(this.checked) {
                $('#management-section input[type="checkbox"]').each(function () {
                    $(this).iCheck('check');
                });
            }
            else {
                $('#management-section input[type="checkbox"]').each(function() {
                    $(this).iCheck('uncheck');
                });
            }
        });

        $(document).on('change', 'input[name="actions-section"]', function() {
            if(this.checked) {
                $('#actions-section input[type="checkbox"]').each(function () {
                    $(this).iCheck('check');
                });
            }
            else {
                $('#actions-section input[type="checkbox"]').each(function() {
                    $(this).iCheck('uncheck');
                });
            }
        });



        $(document).on('change', '#management-section input[type="checkbox"]', function() {
            controlManagementCheckAllInput();
        });

        $(document).on('change', '#actions-section input[type="checkbox"]', function() {
            controlActionsCheckAllInput();
        });


        function controlManagementCheckAllInput()
        {
            if($('#management-section input[type="checkbox"]:checked').length < managementOptionsNumber)
                {
                    $('input[name="management-section"]').iCheck('uncheck');
                }else
                {
                    $('input[name="management-section"]').iCheck('check');
                }
        }

        function controlActionsCheckAllInput()
        {
            if($('#actions-section input[type="checkbox"]:checked').length < actionsOptionsNumber)
            {
                $('input[name="actions-section"]').iCheck('uncheck');
            }
            else
            {
                $('input[name="actions-section"]').iCheck('check');
            }
        }

    </script>
</literal>
