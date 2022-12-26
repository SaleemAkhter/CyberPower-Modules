<?php

use Illuminate\Database\Capsule\Manager as Capsule;

function wgsVmware_getAppConfigOptions($app) {
    $configoptions = array();
    $result = Capsule::table('mod_vmware_apps')->where('app_name', $app)->get();

    foreach ($result as $data) {
        $data = (array) $data;
        $setting = $data['setting'];
        $value = $data['value'];
        $configoptions[$setting] = decrypt($value);
    }
    return $configoptions;
}

function wgsVmware_appConfigFieldOutput($values) {
    if (!$values['Value']) {
        $values['Value'] = $values['Default'];
    }


    if ($values['Type'] == "text") {
        $code = "<input type=\"text\" name=\"" . $values['Name'] . "\" size=\"" . $values['Size'] . "\" value=\"" . $values['Value'] . "\" class=\"form-control input-inline\" />";

        if ($values['Description']) {
            $code .= " " . $values['Description'];
        }
    } else {
        if ($values['Type'] == "password") {
            $code = "<input type=\"password\" name=\"" . $values['Name'] . "\" size=\"" . $values['Size'] . "\" value=\"" . $values['Value'] . "\" class=\"form-control input-inline\" />";

            if ($values['Description']) {
                $code .= " " . $values['Description'];
            }
        } else {
            if ($values['Type'] == "yesno") {
                $code = "<label><input type=\"checkbox\" name=\"" . $values['Name'] . "\"";

                if ($values['Value']) {
                    $code .= " checked=\"checked\"";
                }

                $code .= " /> " . $values['Description'] . "</label>";
            } else {
                if ($values['Type'] == "dropdown") {
                    $code = "<select name=\"" . $values['Name'] . "\" class=\"form-control\">";
                    $options = explode(",", $values['Options']);
                    foreach ($options as $tempval) {
                        $code .= "<option value=\"" . $tempval . "\"";

                        if ($values['Value'] == $tempval) {
                            $code .= " selected=\"selected\"";
                        }

                        $code .= ">" . $tempval . "</option>";
                    }

                    $code .= "</select>";

                    if ($values['Description']) {
                        $code .= " " . $values['Description'];
                    }
                } else {
                    if ($values['Type'] == "radio") {
                        $code = "";

                        if ($values['Description']) {
                            $code .= $values['Description'] . "<br />";
                        }

                        $options = explode(",", $values['Options']);

                        if (!$values['Value']) {
                            $values['Value'] = $options[0];
                        }

                        foreach ($options as $tempval) {
                            $code .= "<label><input type=\"radio\" name=\"" . $values['Name'] . "\" value=\"" . $tempval . "\"";

                            if ($values['Value'] == $tempval) {
                                $code .= " checked=\"checked\"";
                            }

                            $code .= " /> " . $tempval . "</label><br />";
                        }
                    } else {
                        if ($values['Type'] == "textarea") {
                            $cols = ($values['Cols'] ? $values['Cols'] : "60");
                            $rows = ($values['Rows'] ? $values['Rows'] : "5");
                            $code = "<textarea name=\"" . $values['Name'] . "\" cols=\"" . $cols . "\" rows=\"" . $rows . "\" class=\"form-control\">" . $values['Value'] . "</textarea>";

                            if ($values['Description']) {
                                $code .= "<br />" . $values['Description'];
                            }
                        } else {
                            $code = $values['Description'];
                        }
                    }
                }
            }
        }
    }

    return $code;
}

?>