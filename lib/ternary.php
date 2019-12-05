<?php
/**
 * @author Isaev Danil
 * @package Isaev\Seotemplate
 */
namespace Isaev\Seotemplate;

/**
 * {=ternary this.Name "?" "!Empty"}
 * {=ternary this.Name "?" this.Name "!empty" ":" "empty"}
 * {=ternary this.test "?" "!empty" ":" "empty"}
 */
class Ternary extends \Bitrix\Iblock\Template\Functions\FunctionBase
{
    public function calculate($parameters)
    {
        $this->parametersToArray($parameters);
        
        $isTrue = false;
        $isFalse = false;
        $arParams = [];
        
        foreach ($parameters as $k => $param) {
            if ($param == '?') {
                $isTrue = true;
                $isFalse = false;
            } elseif ($param == ':') {
                $isTrue = false;
                $isFalse = true;
            } else {
                if ($isTrue === true) {
                    $arParams['true'] .= $param;
                } elseif ($isFalse === true) {
                    $arParams['false'] .= $param;
                } else {
                    $arParams['function'] .= $param;
                }
            }
        }
        $result = (!empty($arParams['function']) ? $arParams['true'] : $arParams['false']);
        return $result;
    }
}
