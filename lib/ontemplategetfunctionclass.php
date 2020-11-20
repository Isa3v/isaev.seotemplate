<?php
/**
 * @author Isaev Danil
 * @package Isaev\Seotemplate
 */
namespace Isaev\Seotemplate;

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Loader;

\Bitrix\Main\Loader::includeModule('iblock');
Loc::loadMessages(__FILE__);
include_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/iblock/lib/template/functions/fabric.php");
class Ontemplategetfunctionclass extends \Bitrix\Iblock\Template\Functions\FunctionBase
{
    //Обработчик события на вход получает имя требуемой функции
    public static function eventHandler($event)
    {
        $parameters = $event->getParameters();
        $functionName = $parameters[0];
        if ($functionName === "ternary") {
            //обработчик должен вернуть SUCCESS и имя класса
            return new \Bitrix\Main\EventResult(
                \Bitrix\Main\EventResult::SUCCESS,
                '\\Isaev\\Seotemplate\\Ternary'
            );
        } elseif ($functionName === "minpricesection") {
            //обработчик должен вернуть SUCCESS и имя класса
            if (Loader::includeModule("catalog") && Loader::includeModule('currency') && Loader::includeModule('iblock')) {
                return new \Bitrix\Main\EventResult(
                    \Bitrix\Main\EventResult::SUCCESS,
                    '\\Isaev\\Seotemplate\\Minpricesection'
                );
            }
        } elseif ($functionName === "maxpricesection") {
            //обработчик должен вернуть SUCCESS и имя класса
            if (Loader::includeModule("catalog") && Loader::includeModule('currency') && Loader::includeModule('iblock')) {
                return new \Bitrix\Main\EventResult(
                    \Bitrix\Main\EventResult::SUCCESS,
                    '\\Isaev\\Seotemplate\\Maxpricesection'
                );
            }
        } elseif ($functionName === "availablegoods") {
            //обработчик должен вернуть SUCCESS и имя класса
            if (Loader::includeModule("catalog") && Loader::includeModule('currency') && Loader::includeModule('iblock')) {
                return new \Bitrix\Main\EventResult(
                    \Bitrix\Main\EventResult::SUCCESS,
                    '\\Isaev\\Seotemplate\\Availablegoods'
                );
            }
        } elseif ($functionName === "activegoods") {
            //обработчик должен вернуть SUCCESS и имя класса
            if (Loader::includeModule("catalog") && Loader::includeModule('currency') && Loader::includeModule('iblock')) {
                return new \Bitrix\Main\EventResult(
                    \Bitrix\Main\EventResult::SUCCESS,
                    '\\Isaev\\Seotemplate\\Activegoods'
                );
            }
        } elseif ($functionName === "strpos") {
            //обработчик должен вернуть SUCCESS и имя класса
            return new \Bitrix\Main\EventResult(
                \Bitrix\Main\EventResult::SUCCESS,
                '\\Isaev\\Seotemplate\\Strpos'
            );
        } elseif ($functionName === "str_replace") {
            //обработчик должен вернуть SUCCESS и имя класса
            return new \Bitrix\Main\EventResult(
                \Bitrix\Main\EventResult::SUCCESS,
                '\\Isaev\\Seotemplate\\Strreplace'
            );
        } elseif ($functionName === "strip") {
            //обработчик должен вернуть SUCCESS и имя класса
            return new \Bitrix\Main\EventResult(
                \Bitrix\Main\EventResult::SUCCESS,
                '\\Isaev\\Seotemplate\\Strip'
            );
        } elseif ($functionName === "gt") {
            //обработчик должен вернуть SUCCESS и имя класса
            return new \Bitrix\Main\EventResult(
                \Bitrix\Main\EventResult::SUCCESS,
                '\\Isaev\\Seotemplate\\Gt'
            );
        } elseif ($functionName === "ge") {
            //обработчик должен вернуть SUCCESS и имя класса
            return new \Bitrix\Main\EventResult(
                \Bitrix\Main\EventResult::SUCCESS,
                '\\Isaev\\Seotemplate\\Ge'
            );
        } elseif ($functionName === "eq") {
            //обработчик должен вернуть SUCCESS и имя класса
            return new \Bitrix\Main\EventResult(
                \Bitrix\Main\EventResult::SUCCESS,
                '\\Isaev\\Seotemplate\\Eq'
            );
        }
        
    }
}
