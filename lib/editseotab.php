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

    public function onAdminTabControlBegin(&$form)
    {
        // В каких input выводим опеределенные функции
        $seoInputSection = [
            'IPROPERTY_TEMPLATES_SECTION_META_TITLE', 
            'IPROPERTY_TEMPLATES_SECTION_META_KEYWORDS', 
            'IPROPERTY_TEMPLATES_SECTION_META_DESCRIPTION', 
            'IPROPERTY_TEMPLATES_SECTION_PAGE_TITLE'
        ];
        // При правильном обращении это регулярка может послужить во многих функциях 
        $re = '/((?:\s|)+\](?:\s|)+\})((?:\s|)+\](?:\s|)+\,(?:\s|)+\'(?:\s|)+\'(?:\s|)+\)\;(?:\s|)+\}(?:\s|)+\)(?:\s|)+\;(?:\s|)+\}(?:\S|)+\)\;)/m';
        foreach ($form->arFields as $key => $val) {
            $data = [];
            $data['TEXT'] = Loc::getMessage("NAV_FUNCTION");
            if (in_array($val['id'], $seoInputSection, 1)) {
                $data['MENU'][] = ['TEXT' =>  Loc::getMessage("NAV_FUNCTION_MIN_PRICE"),'ONCLICK' => '{=minPriceSection}'];     
                $data['MENU'][] = ['TEXT' =>  Loc::getMessage("NAV_FUNCTION_MAX_PRICE"),'ONCLICK' => '{=maxPriceSection}'];    
            }
            $data['MENU'][] = ['TEXT' =>  Loc::getMessage("NAV_FUNCTION_TERNARY"),'ONCLICK'  => '{=ternary {=this.Name} "?" {=this.Code} " - ok" ":" "empty"} ']; 
            $replace = self::getJsTemplate($data, $val['id']);
            $form->arFields[$key]['custom_html'] =  preg_replace($re, '$1,'.$replace.'$2', $form->arFields[$key]['custom_html']);
        }
    }

    /**
     * @param  $data['TEXT'] = 'Доп. функции';
     * @param  $data['MENU'][] = ['TEXT' =>  'Минимальная цена раздела','ONCLICK' => '{=minPriceSection}'];
     * @param  $input = 'IPROPERTY_TEMPLATES_SECTION_META_TITLE'
     */
    public function getJsTemplate($data, $input)
    {
        foreach ($data['MENU'] as $nav) {
            $arMenuDrop[] = [
            'TEXT' => $nav['TEXT'],
            'ONCLICK' => "InheritedPropertiesTemplates.insertIntoInheritedPropertiesTemplate('".$nav['ONCLICK']."', 'mnu_".$input."', \'".$input."')"
        ];
        }
        $arMenu = ['TEXT' => $data['TEXT'], 'MENU' => $arMenuDrop];
        return \Bitrix\Main\Web\Json::encode($arMenu, JSON_UNESCAPED_UNICODE);
    }
}