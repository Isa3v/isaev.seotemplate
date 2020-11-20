<?php
/**
 * @author Isaev Danil
 * @package Isaev\Seotemplate
 */
namespace Isaev\Seotemplate;

/**
 * {=eq "100" "90"}
 * Syntax (100 == 90 ? "1" : "")
 */
class Eq extends \Bitrix\Iblock\Template\Functions\FunctionBase
{
    public function calculate($parameters)
    {
        $this->parametersToArray($parameters);
        return $parameters[0] == $parameters[1] ? true : null;
    }
}
