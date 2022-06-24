<?php
/**
 * @author Isaev Danil
 * @package Isaev\Seotemplate
 */

namespace Isaev\Seotemplate;

/**
 * {=ternary {this.Name} "?" {this.Name} "!empty" ":" "empty"}
 * {=ternary {this.Name} "??" "empty"}
 */
class Ternary extends \Bitrix\Iblock\Template\Functions\FunctionBase
{
    public function calculate($parameters)
    {
        $this->parametersToArray($parameters);
        $parameters     = array_values($parameters);              // У них и так ключи - цифры, но не помешает
        $result         = null;                                   // Если мы ничего не высчитаем
        $trueKey        = array_search('?', $parameters, true);   // Ищем оператор ?
        $falseKey       = array_search(':', $parameters, true);   // Ищем оператор :
        $nullCoalescing = array_search('??', $parameters, true);  // Ищем оператор ??

        if ($falseKey !== false && $trueKey !== false && $nullCoalescing === false) {
            $arParams['function']   = implode(array_slice($parameters, 0, $trueKey)); // Полчаем параметры до оператора "?" и объединяет элементы массива в строку
            $arParams['true']       = implode(array_slice($parameters, ++$trueKey, ($falseKey - $trueKey))); // Полчаем параметры до оператора ":" и объединяет элементы массива в строку
            $arParams['false']      = implode(array_slice($parameters, ++$falseKey)); // Полчаем параметры посде оператора ":" и объединяет элементы массива в строку
            // Высчитываем
            $result = (!empty($arParams['function']) ? $arParams['true'] : $arParams['false']);
        } elseif ($nullCoalescing !== false) {
            $arParams['function']   = implode(array_slice($parameters, 0, $nullCoalescing));
            $arParams['false']      = implode(array_slice($parameters, ++$nullCoalescing));
            // Высчитываем
            $result = (!empty($arParams['function']) ? $arParams['function'] : $arParams['false']); // Не факт, что будут использовать на PHP 7+, потому так
        }
        // Возвращаем результат
        return $result;
    }
}
