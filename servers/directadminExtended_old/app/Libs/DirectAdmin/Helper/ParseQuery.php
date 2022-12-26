<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 2018-07-17
 * Time: 12:57
 */
namespace ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Helper;


class ParseQuery
{
    private $duplicates;
    private $numericIndices;

    /**
     * @param $str
     * @return array|void
     */
    public function parseInto($str)
    {
        if ($str === '') {
            return;
        }

        $result = [];
        $this->duplicates = false;
        $this->numericIndices = true;
        $decoder = $this->getDecoder();

        foreach (explode('&', $str) as $kvp) {

            $parts = explode('=', $kvp, 2);
            $key = $decoder($parts[0]);
            $value = isset($parts[1]) ? $decoder($parts[1]) : null;

            // Special handling needs to be taken for PHP nested array syntax
            if (strpos($key, '[') !== false) {
                $this->parsePhpValue($key, $value, $result);
                continue;
            }

            if (!isset($result[$key])) {
                $result[$key] = $value;
            } else {
                $this->duplicates = true;
                if (!is_array($result[$key])) {
                    $result[$key] = [$result[$key]];
                }
                $result[$key][] = $value;
            }
        }

        return $result;
    }

    /**
     * Returns a callable that is used to URL decode query keys and values.
     *
     * @return callable|string
     */
    private static function getDecoder()
    {
            return function ($value) {
                return rawurldecode(str_replace('+', ' ', $value));
            };
    }

    /**
     * Parses a PHP style key value pair.
     *
     * @param string      $key    Key to parse (e.g., "foo[a][b]")
     * @param string|null $value  Value to set
     * @param array       $result Result to modify by reference
     */
    private function parsePhpValue($key, $value, array &$result)
    {
        $node =& $result;
        $keyBuffer = '';

        for ($i = 0, $t = strlen($key); $i < $t; $i++) {
            switch ($key[$i]) {
                case '[':
                    if ($keyBuffer) {
                        $this->prepareNode($node, $keyBuffer);
                        $node =& $node[$keyBuffer];
                        $keyBuffer = '';
                    }
                    break;
                case ']':
                    $k = $this->cleanKey($node, $keyBuffer);
                    $this->prepareNode($node, $k);
                    $node =& $node[$k];
                    $keyBuffer = '';
                    break;
                default:
                    $keyBuffer .= $key[$i];
                    break;
            }
        }

        if (isset($node)) {
            $this->duplicates = true;
            $node[] = $value;
        } else {
            $node = $value;
        }
    }

    /**
     * Prepares a value in the array at the given key.
     *
     * If the key already exists, the key value is converted into an array.
     *
     * @param array  $node Result node to modify
     * @param string $key  Key to add or modify in the node
     */
    private function prepareNode(&$node, $key)
    {
        if (!isset($node[$key])) {
            $node[$key] = null;
        } elseif (!is_array($node[$key])) {
            $node[$key] = [$node[$key]];
        }
    }

    /**
     * Returns the appropriate key based on the node and key.
     */
    private function cleanKey($node, $key)
    {
        if ($key === '') {
            $key = $node ? (string) count($node) : 0;
            // Found a [] key, so track this to ensure that we disable numeric
            // indexing of keys in the resolved query aggregator.
            $this->numericIndices = false;
        }

        return $key;
    }
}