<?php
/**
 * @author Isaev Danil
 * @package Isaev\Seotemplate
 */
namespace Isaev\Seotemplate;

use Bitrix\Main\Localization\Loc;

\Bitrix\Main\Loader::includeModule('iblock');
Loc::loadMessages(__FILE__);
include_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/iblock/lib/template/functions/fabric.php");
class Ontemplategetfunctionclass  extends \Bitrix\Iblock\Template\Functions\FunctionBase
{
    //���������� ������� �� ���� �������� ��� ��������� �������
    public static function eventHandler($event)
    {
        $parameters = $event->getParameters();
        $functionName = $parameters[0];
        if ($functionName === "ternary") {
            //���������� ������ ������� SUCCESS � ��� ������
            return new \Bitrix\Main\EventResult(
                \Bitrix\Main\EventResult::SUCCESS,
                '\\Isaev\\Seotemplate\\Ternary'
            );
        }elseif ($functionName === "minpricesection") {
            //���������� ������ ������� SUCCESS � ��� ������
            return new \Bitrix\Main\EventResult(
                \Bitrix\Main\EventResult::SUCCESS,
                '\\Isaev\\Seotemplate\\Minpricesection'
            );
        
        }elseif ($functionName === "maxpricesection") {
            //���������� ������ ������� SUCCESS � ��� ������
            return new \Bitrix\Main\EventResult(
                \Bitrix\Main\EventResult::SUCCESS,
                '\\Isaev\\Seotemplate\\Maxpricesection'
            );
        }
    }
   
}
