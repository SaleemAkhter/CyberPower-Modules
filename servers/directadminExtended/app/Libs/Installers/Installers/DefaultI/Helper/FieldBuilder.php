<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\Libs\Installers\Installers\DefaultI\Helper;

use \ModulesGarden\Servers\DirectAdminExtended\App\Libs\Installers\Installers\Models\Field;
use \ModulesGarden\Servers\DirectAdminExtended\App\Libs\Installers\Installers\Helpers\FieldTypeConstants;

/**
 * Description of FieldBuilder
 *
 * @author Michal Zarow <michal.za@modulesgarden.com>
 */
class FieldBuilder
{

    public function getBasicFields()
    {
        $fields = [];
        $this->generateDatabaseFields($fields);

        return $fields;
    }
    
    /**
     * 
     * @param type $fields
     */
    public function generateDatabaseFields(&$fields)
    {
        $dbName = new Field();
        $dbName->setName('databaseName')
                ->setLabel('Database Name')
                ->setType(FieldTypeConstants::TEXT);

        $dbUser = new Field();
        $dbUser->setName('databaseLogin')
                ->setLabel('Database Login')
                ->setType(FieldTypeConstants::TEXT);

        $dbPass = new Field();
        $dbPass->setName('databasePassword')
                ->setLabel('Database Password')
                ->setType(FieldTypeConstants::PASSWORD);

        $dbPrefix = new Field();
        $dbPrefix->setName('databasePrefix')
                ->setLabel('Table Prefix')
                ->setType(FieldTypeConstants::TEXT);

        $fields[] = $dbName;
        $fields[] = $dbUser;
        $fields[] = $dbPass;
        $fields[] = $dbPrefix;
    }

    public function getFieldsFromUrl($post)
    {
        if (!empty($post["vendor"]) && !empty($post["name"]) && !empty($post["version"]))
        {
            $ch   = curl_init();
            $url  = "http://apscatalog.com/all/" . $post["vendor"] . "/" . $post["name"] . "/";
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $page = curl_exec($ch);
            if (curl_errno($ch))
            {
                curl_close($ch);
                return array("status" => "error", "text" => $lang['api_error_1'] . ": " . curl_error($ch));
            }

            $dom = new \DOMDocument();
            $dom->loadHTML($page);

            $elements = $dom->getElementsByTagName("p");
            foreach ($elements as $elem)
            {
                if ((string) $elem->getAttribute('class') == "versionList")
                {
                    foreach ($elem->getElementsByTagName("a") as $a)
                    {
                        $versions[] = $a->nodeValue;
                    }
                    break;
                }
            }

            if (!isset($versions))
            {
                return array("status" => "error", "text" => $lang['api_error_2']);
            }

            $explVersion = explode(".", $post["version"]);
            $VERSION;

            foreach ($versions as $ver)
            {
                $versionOk = true;
                if (preg_match("/.*-.*/", $ver))
                {
                    $splitted = explode(".", $ver);

                    for ($i = 0; $i < count($explVersion); $i++)
                    {
                        if (!preg_match("/.*-.*/", $splitted[$i]))
                        {
                            if (!$splitted[$i] == $explVersion[$i])
                            {
                                $versionOk = false;
                                break;
                            }
                        }
                        else
                        {
                            $splitted2       = explode("-", $splitted[$i]);
                            $splitted2Int[0] = (int) $splitted2[0];
                            $splitted2Int[1] = (int) $splitted2[1];
                            if ($splitted2Int[0] < $splitted2Int[1])
                            {
                                $from = $splitted2Int[0];
                                $to   = $splitted2Int[1];
                            }
                            else
                            {
                                $from = $splitted2Int[1];
                                $to   = $splitted2Int[0];
                            }
                            if ($from <= (int) $explVersion[$i] && (int) $explVersion[$i] <= $to)
                            {
                                $VERSION = $ver;
                            }
                            else
                            {
                                $versionOk = false;
                            }
                            break;
                        }
                    }

                    if ($versionOk)
                    {
                        $VERSION = $ver;
                    }
                }
                else
                {
                    if ($post["version"] == $ver)
                    {
                        $VERSION = $ver;
                        break;
                    }
                }
            }

            if (!isset($VERSION))
            {
                return array("status" => "error", "text" => $lang['api_error_3']);
            }

            $url  .= $VERSION . "/";
            curl_setopt($ch, CURLOPT_URL, $url);
            $page = curl_exec($ch);
            if (curl_errno($ch))
            {
                curl_close($ch);
                return array("status" => "error", "text" => $lang['api_error_1'] . ": " . curl_error($ch));
            }

            $dom = new \DOMDocument();
            $dom->loadHTML($page);

            $elements = $dom->getElementsByTagName("p");
            foreach ($elements as $elem)
            {
                if ((string) $elem->getAttribute('class') == "packagerList")
                {
                    foreach ($elem->getElementsByTagName("a") as $a)
                    {
                        if ($a->getAttribute('class') == "packager")
                        {
                            $href = $a->getAttribute('href');
                        }
                    }
                    break;
                }
            }

            if (!isset($href))
            {
                return array("status" => "error", "text" => $lang['api_error_4']);
            }

            $versionUrl = $url;
            $url        .= $href;
            curl_setopt($ch, CURLOPT_URL, $url);
            $page       = curl_exec($ch);
            if (curl_errno($ch))
            {
                curl_close($ch);
                return array("status" => "error", "text" => $lang['api_error_1'] . ": " . curl_error($ch));
            }

            $dom = new \DOMDocument();
            $dom->loadHTML($page);

            $elements = $dom->getElementById("meta");
            $href     = $elements->getAttribute('href');
            curl_setopt($ch, CURLOPT_URL, $versionUrl . $href);
            $page     = curl_exec($ch);
            $info     = curl_getinfo($ch);

            if ($info['http_code'] >= 300 && $info['http_code'] < 400 && !empty($info['redirect_url']))
            {
                curl_setopt($ch, CURLOPT_URL, $info['redirect_url']);
                $page = curl_exec($ch);
            }

            if (curl_errno($ch))
            {
                curl_close($ch);
                return array("status" => "error", "text" => $lang['api_error_1'] . ": " . curl_error($ch));
            }

            $result = simplexml_load_string($page); // parse here

            if (empty($result))
            {
                curl_close($ch);
                return array("status" => "error", "text" => $lang['api_error_5']);
            }

            $namespaces = $result->getNamespaces(true);

            $groups       = $group        = $requirements = $informations = array();

            $informations['name']           = (string) $result->name;
            $informations['default_prefix'] = ltrim((string) $result->{'default-prefix'}, '/');
            $informations['version']        = (string) $result->version;
            $informations['release']        = (string) $result->release;
            $informations['packager_uri']   = (string) $result->{'packager-uri'};

            $obj_settings           = isset($result->service->settings) ? $result->service->settings : (isset($result->settings) ? $result->settings : false);
            $obj_requirements['db'] = isset($result->requirements) ? $result->requirements->children($namespaces['db']) : (isset($result->service->requirements) ? $result->service->requirements->children($namespaces['db']) : false);

            if ($obj_settings !== false)
            {
                foreach ($obj_settings->children() as $obj_group)
                {
                    if ($obj_group->getName() == 'group')
                    {
                        foreach ($obj_group->children() as $obj_setting_key => $obj_setting_value)
                        {
                            if ((string) $obj_setting_key == 'name')
                            {
                                $group['name'] = (string) $obj_setting_value;
                            }
                            elseif ((string) $obj_setting_key == 'setting')
                            {
                                unset($setting);
                                $setting['name']        = (string) $obj_setting_value->name;
                                $setting['description'] = (string) $obj_setting_value->description;
                                $is_hidden              = false;
                                foreach ($obj_setting_value->attributes() as $attr_key => $attr_value)
                                {
                                    if ($attr_key == 'visibility' && $attr_value == 'hidden')
                                    {
                                        $is_hidden = true;
                                        break;
                                    }
                                    $setting[(string) str_replace('-', '_', $attr_key)] = (string) $attr_value;
                                }
                                if ($is_hidden === false && isset($obj_setting_value->choice))
                                {
                                    foreach ($obj_setting_value->choice as $obj_choice)
                                    {
                                        foreach ($obj_choice->attributes() as $choice_attr_key => $choice_attr_value)
                                        {
                                            if ((string) $choice_attr_key == 'id')
                                            {
                                                $choice_id = (string) $choice_attr_value;
                                            }
                                        }
                                        $choice_name         = (string) $obj_choice->name;
                                        $setting['choice'][] = array('id'   => $choice_id,
                                            'name' => $choice_name);
                                        unset($choice_id, $choice_name);
                                    }
                                }
                                if ($is_hidden === false)
                                {
                                    $group['settings'][] = $setting;
                                }
                            }
                            elseif ((string) $obj_setting_key == 'group')
                            {
                                foreach ($obj_setting_value->children() as $obj_g_key => $obj_g_value)
                                {
                                    unset($setting);
                                    $setting['name']        = (string) $obj_g_value->name;
                                    $setting['description'] = (string) $obj_g_value->setting->description;
                                    $is_hidden              = false;
                                    foreach ($obj_g_value->attributes() as $attr_key => $attr_value)
                                    {
                                        if ($attr_key == 'visibility' && $attr_value == 'hidden')
                                        {
                                            $is_hidden = true;
                                            break;
                                        }
                                        $setting[(string) str_replace('-', '_', $attr_key)] = (string) $attr_value;
                                    }
                                    if ($is_hidden === false && isset($obj_g_value->choice))
                                    {
                                        foreach ($obj_g_value->choice as $obj_choice)
                                        {
                                            foreach ($obj_choice->attributes() as $choice_attr_key => $choice_attr_value)
                                            {
                                                if ((string) $choice_attr_key == 'id')
                                                {
                                                    $choice_id = (string) $choice_attr_value;
                                                }
                                            }
                                            $choice_name         = (string) $obj_choice->name;
                                            $setting['choice'][] = array('id'   => $choice_id,
                                                'name' => $choice_name);
                                            unset($choice_id, $choice_name);
                                        }
                                    }
                                    if ($is_hidden === false)
                                    {
                                        $group['settings'][] = $setting;
                                    }
                                }
                            }
                        }
                        if (!empty($group))
                        {
                            $groups[] = $group;
                        }
                        unset($group);
                    }
                    elseif ($obj_group->getName() == 'setting')
                    {
                        unset($other_setting);
                        $other_setting['name']        = (string) ($obj_group->name);
                        $other_setting['description'] = (string) $obj_group->description;
                        $is_hidden                    = false;
                        foreach ($obj_group->attributes() as $attr_key => $attr_value)
                        {
                            if ($attr_key == 'visibility' && $attr_value == 'hidden')
                            {
                                $is_hidden = true;
                                break;
                            }
                            $other_setting[(string) str_replace('-', '_', $attr_key)] = (string) $attr_value;
                        }

                        if ($is_hidden === false && isset($obj_group->choice))
                        {
                            foreach ($obj_group->choice as $obj_choice)
                            {
                                foreach ($obj_choice->attributes() as $choice_attr_key => $choice_attr_value)
                                {
                                    if ((string) $choice_attr_key == 'id')
                                    {
                                        $choice_id = (string) $choice_attr_value;
                                    }
                                }
                                $choice_name               = (string) $obj_choice->name;
                                $other_setting['choice'][] = array('id'   => $choice_id,
                                    'name' => $choice_name);
                                unset($choice_id, $choice_name);
                            }
                        }
                        if ($is_hidden === false)
                        {
                            $other_settings[] = $other_setting;
                        }
                    }
                }

                if (!empty($other_settings))
                {
                    $groups[] = array('name' => 'Other Settings', 'settings' => $other_settings);
                }
            }

            if (isset($obj_requirements['db']->db) && !empty($obj_requirements['db']->db))
            {
                foreach ((array) $obj_requirements['db']->db as $requirement_key => $requirement_value)
                {
                    $requirements['db'][(string) str_replace('-', '_', $requirement_key)] = $requirement_value;
                }
            }

            $result_obj = array(
                'informations' => $informations,
                'settings'     => $groups,
                'requirements' => $requirements
            );

            curl_close($ch);

            return array("status" => "ok", "obj" => $result_obj);
        }
        else
        {
            return array("status" => "error", "text" => $lang['api_error_6']);
        }
    }
}
