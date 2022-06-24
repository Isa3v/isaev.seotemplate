<?php
/**
 * @author Isaev Danil
 * @package Isaev\Seotemplate
 */

namespace Isaev\Seotemplate;

/**
 * {=strpos this.Name "Имя"}
 * {=ternary {=strpos this.Name "Имя"} "?" this.Name "!empty" ":" "empty"}
 */
class Strpos extends \Bitrix\Iblock\Template\Functions\FunctionBase
{
    public function calculate($parameters)
    {
        $this->parametersToArray($parameters);
        if (strpos($parameters[0], $parameters[1]) !== false) {
            return $parameters[0];
        } else {
            return;
        }
    }
}
