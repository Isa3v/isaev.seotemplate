<?php
/**
 * @author Isaev Danil
 * @package Isaev\Seotemplate
 */
namespace Isaev\Seotemplate;

/**
 * {=str_replace "Цвет" "Разноцвет" {=this.name}
 */
class Strreplace extends \Bitrix\Iblock\Template\Functions\FunctionBase
{
    public function calculate($parameters)
    {
        $this->parametersToArray($parameters);
        return str_replace($parameters[0], $parameters[1], $parameters[2]);
    }
}
