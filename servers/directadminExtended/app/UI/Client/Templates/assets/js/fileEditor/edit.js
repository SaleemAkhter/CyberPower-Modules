

function loadFileDataToAuthModal(data, targetId, event)
{
    jQuery("#authForm").find("input[name='filename']").val(jQuery("#filenamelabel").text());
    jQuery("#authForm").find("input[name='filecontent']").val(editor.getDoc().getValue());
}
