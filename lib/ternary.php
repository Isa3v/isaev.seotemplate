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
        $arParams = $this->parametersToArray($parameters);
        // Сначала переделываем массив строку без пробелов
        $arParams = trim(implode('', $this->parametersToArray($parameters)));
        // Затем обратно делаем массив с тернарными разделителями
        $arParams =  preg_split("/(\?|\:)/", $arParams);
        $result = (!empty($arParams[0]) ? $arParams[1] : $arParams[2]);
        return $result;
    }
}
