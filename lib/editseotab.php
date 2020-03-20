<?php
/**
 * @author Isaev Danil
 * @package Isaev\Seotemplate
 */
namespace Isaev\Seotemplate;

use Bitrix\Main\Localization\Loc;

\Bitrix\Main\Loader::includeModule('iblock');
Loc::loadMessages(__FILE__);
class Editseotab  
{   
    /**
     * Функция работает от события OnAdminTabControlBegin 
     * Вызывается в функции CAdminTabControl::Begin() при выводе в административном интерфейсе формы редактирования
     */
    public function onAdminTabControlBegin(&$form)
    {   
        // Если нет полей для добавления вкладок
        if(!isset($form->arFields)){
            return false;
        }
        
        // Готовим Пункты меню 
        $arMenu = [];
        $arMenu['TEXT'] = Loc::getMessage("NAV_FUNCTION");
        // Функции предназначенные только для разделов
        if ($form->customTabber->name == 'OnAdminIBlockSectionEdit') {
            $arMenu['MENU']['MIN_PRICE']      = ['TEXT' => Loc::getMessage("NAV_FUNCTION_MIN_PRICE"),'ONCLICK' => '{=minPriceSection}'];     
            $arMenu['MENU']['MAX_PRICE']      = ['TEXT' => Loc::getMessage("NAV_FUNCTION_MAX_PRICE"),'ONCLICK' => '{=maxPriceSection}'];
            $arMenu['MENU']['ACTIVE_GOODS']   = ['TEXT' => Loc::getMessage("NAV_FUNCTION_ACTIVE_GOODS"),'ONCLICK'  => '{=activeGoods}']; 
            $arMenu['MENU']['AVIABLE_GOODS']  = ['TEXT' => Loc::getMessage("NAV_FUNCTION_AVAILABLE_GOODS"),'ONCLICK'  => '{=availableGoods}'];     
        }
        // Функции и для товаров и для разделов
        $arMenu['MENU']['STR_REPLACE']    = ['TEXT' =>  Loc::getMessage("NAV_FUNCTION_STR_REPLACE"),'ONCLICK'  => '{=str_replace "search" "replace" {=this.name}}']; 
        $arMenu['MENU']['TERNARY']        = ['TEXT' =>  Loc::getMessage("NAV_FUNCTION_TERNARY"),'ONCLICK'  => '{=ternary {=this.Name} "?" {=this.Code} " - ok" ":" "empty"}']; 
        $arMenu['MENU']['TERNARY']['MENU']['NULL_COALESCING']     = ['TEXT' =>  Loc::getMessage("NAV_FUNCTION_NULL_COALESCING"),'ONCLICK'  => '{=ternary {=this.Name} "??" "empty"}'];
        $arMenu['MENU']['TERNARY']['MENU']['SUBSTR']              = ['TEXT' =>  Loc::getMessage("NAV_FUNCTION_SUBSTR"),'ONCLICK'  => '{=ternary {=strpos this.Name "Test"} "?" this.Name " contains Test" ":" "Does not contain"}'];
        $arMenu['MENU']['TERNARY']['MENU']['SUBSTR_STR_REPLACE']  = ['TEXT' =>  Loc::getMessage("NAV_FUNCTION_SUBSTR_STR_REPLACE"),'ONCLICK'  => '{=ternary {=strpos this.Name "Test"} "?" {=str_replace "Test" "Replace str" {=this.name}} ":" "Does not contain"}'];
        $arMenu['MENU']['STRIP']    = ['TEXT' =>  Loc::getMessage("NAV_FUNCTION_STRIP"),'ONCLICK'  => '{=strip {=this.name}}']; 

        // Стандартные функции битрикса (почему их нет в меню?)
        $arBxFunc = [];
        $arBxFunc['TEXT'] = Loc::getMessage("BX_NAV_FUNCTION");
        $arBxFunc['MENU']['LOWER']      = ['TEXT' => Loc::getMessage("BX_NAV_FUNCTION_LOWER"),'ONCLICK' => '{=lower {=this.Name}  "TEST"}'];     
        $arBxFunc['MENU']['UPPER']      = ['TEXT' => Loc::getMessage("BX_NAV_FUNCTION_UPPER"),'ONCLICK' => '{=upper {=this.Name}  "test"}'];  
        $arBxFunc['MENU']['CONCAT']     = ['TEXT' => Loc::getMessage("BX_NAV_FUNCTION_CONCAT"),'ONCLICK' => '{=concat {=catalog.store} ", "}'];  
        $arBxFunc['MENU']['LIMIT']      = ['TEXT' => Loc::getMessage("BX_NAV_FUNCTION_LIMIT"),'ONCLICK' => '{=limit {=catalog.store} " " 1}'];  
        $arBxFunc['MENU']['TRANSLIT']   = ['TEXT' => Loc::getMessage("BX_NAV_FUNCTION_TRANSLIT"),'ONCLICK' => '{=translit {=this.name}}'];  
        $arBxFunc['MENU']['MIN']        = ['TEXT' => Loc::getMessage("BX_NAV_FUNCTION_MIN"),'ONCLICK' => '{=min 100 200 300}'];  
        $arBxFunc['MENU']['MAX']        = ['TEXT' => Loc::getMessage("BX_NAV_FUNCTION_MAX"),'ONCLICK' => '{=max 100 200 300}'];  
        $arBxFunc['MENU']['DISTINCT']   = ['TEXT' => Loc::getMessage("BX_NAV_FUNCTION_DISTINCT"),'ONCLICK' => '{=distinct 1 2 3 1 4 5}'];  

        // Небольшая манипуляци для первой вкладки (Доп. функции) и для события
        $arResult['MENU']['BX_DEFAULT_FUNCTIONS'] = $arBxFunc;
        $arResult['MENU']['DOP_FUNCTIONS'] = $arMenu;

        /**
         * Event для добавление кастомных пунктов 
         * Пример события
         * $eventManager->addEventHandler("isaev.seotemplate", "OnBeforeMenuAdd", "eventOnBeforeMenuAdd");
         * function eventOnBeforeMenuAdd($event){
         *      $arResult = $event->getParameters();
         *      $arResult['MENU']['TEST'] = ['TEXT' => "Тестовая вкладка", 'ONCLICK' => '{=activeGoods}'];
         *      return $arResult; 
         * }
         */

        $event = new \Bitrix\Main\Event("isaev.seotemplate", "OnBeforeMenuAdd", $arResult);
        $event->send();
        foreach ($event->getResults() as $eventResult) {
            if ($eventResult->getType() == \Bitrix\Main\EventResult::ERROR) {
                continue;
            }
            $arResult = $eventResult->getParameters();
        }
        foreach ($form->arFields as $key => $val) {        
            // Для добавление наших пунктов используем функцию  
            $form->arFields[$key]['custom_html'] = self::setNavFromArray($arResult, $val);
        }
    }

    /**
     * @param arData [Массив с пунктами меню новой вкладки]
     * @param obForm [Объект класса CAdminTabControl]
     * @return [Возвращается готовый код для пунктов SEO]
     */
    public function setNavFromArray($arData, $obForm){
        $arMenu = self::getJsTemplate($arData, $obForm['id']);   
        // Преобразовываем массив в JSON
        foreach ($arMenu as $menu){
            $replace = \Bitrix\Main\Web\Json::encode($menu, JSON_UNESCAPED_UNICODE);
            // Такая сложная регулярка нужна чтоб верстку не сломать
            $regex = '/((?:\s|)+\](?:\s|)+\})((?:\s|)+\](?:\s|)+\,(?:\s|)+\'(?:\s|)+\'(?:\s|)+\)\;(?:\s|)+\}(?:\s|)+\)(?:\s|)+\;(?:\s|)+\}(?:\S|)+\)\;)/m';
            // Добавляем меню
            $obForm['custom_html'] = preg_replace($regex, '$1,'.$replace.'$2', $obForm['custom_html']);
        }
        return $obForm['custom_html'];
    }

    /**
     * @param data  [Массив с пунктами меню новой вкладки];
     * @param input [ID input сео 'IPROPERTY_TEMPLATES_SECTION_META_TITLE']
     */
    public function getJsTemplate($data, $input)
    {   
        $count = 0;
        foreach ($data['MENU'] as $nav) {
            $arMenuDrop[$count]['TEXT'] = $nav['TEXT'];
            if(!empty($nav['ONCLICK'])){
                $arMenuDrop[$count]['ONCLICK'] = "InheritedPropertiesTemplates.insertIntoInheritedPropertiesTemplate('".$nav['ONCLICK']."', 'mnu_".$input."', \'".$input."')";
            }
            // Подменю через рекурсию
            if(!empty($nav['MENU'])){
                $arMenuDrop[$count]['MENU'] = self::getJsTemplate($nav, $input);
            }
            $count++;
        }
        return $arMenuDrop;
    }
}