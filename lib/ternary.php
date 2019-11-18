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
        $arParamsTrue = false;
        $arParamsFalse = false;
        $arParams[0] = '';
        $arParams[1] = '';
        $arParams[2] = '';
        // —начала переделываем массив строку без пробелов
        foreach($parameters as $k => $param){
            if($param == '?'){
                $arParamsTrue = true;
                continue;
            }
            if($param == ':'){
                $arParamsTrue = false;
                $arParamsFalse = false;
                continue;
            }
            if($arParamsTrue === true){
                $arParams[1] .= $param;
            }elseif($arParamsFalse === true){
                $arParams[2] .= $param;
            }else{
                $arParams[0] .= $param;
            }
        }
        $result = (!empty($arParams[0]) ? $arParams[1] : $arParams[2]);
        return $result;
    }
}
