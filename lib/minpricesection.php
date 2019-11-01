<?php
/**
 * @author Isaev Danil
 * @package Isaev\Seotemplate
 */
namespace Isaev\Seotemplate;


\Bitrix\Main\Loader::includeModule('iblock');
/**
 * {=minPriceSection} or {=minPriceSection 5} (5 - id section)
 */
class Minpricesection extends \Bitrix\Iblock\Template\Functions\FunctionBase
{

    public function onPrepareParameters(\Bitrix\Iblock\Template\Entity\Base $entity, array $parameters)
    {
        $arguments = [];
        // Перехватываем id элемента/раздела, чтобы можно было обращаться к его свойствам
        $this->data['id'] = $entity->getId();
        foreach ($parameters as $parameter) {
            $arguments[] = $parameter->process($entity);
        }
        return $arguments;
    }
    //собственно функция выполняющая "магию"
    public function calculate($parameters)
    {
        $priceGroup = '1'; // base or number
        \Bitrix\Main\Loader::includeModule("catalog");
        \Bitrix\Main\Loader::includeModule('currency');
        \Bitrix\Main\Loader::includeModule('iblock');
        $sectionID = (!empty(reset($parameters)) ? reset($parameters) : $this->data['id']);

        // Получаем основной раздел
        $section =  \Bitrix\Iblock\SectionTable::getList([
            'filter' => ['ID' => $sectionID],
            'select' => ['LEFT_MARGIN', 'RIGHT_MARGIN', 'IBLOCK_ID', 'ID']
        ])->fetchRaw();
        // Собираем все подразделы
        $subSections = \Bitrix\Iblock\SectionTable::getList([
            'filter' => [
                '>=LEFT_MARGIN' => $section['LEFT_MARGIN'],
                '<=RIGHT_MARGIN' => $section['RIGHT_MARGIN'],
                '=IBLOCK_ID'  => $section['IBLOCK_ID'],
            ],
            'select' => ['ID']
        ]);
        while ($section = $subSections->fetch()) {
            $arSectionsID[] = $section['ID'];
        }
    
        // Составляем запрос для получения элементво привязанных к секциям
        $resElementsID = \Bitrix\Iblock\SectionElementTable::getList([
            'filter' => ['=IBLOCK_SECTION_ID' => $arSectionsID],
            'select' => [
                'IBLOCK_ELEMENT_ID', // Сумма конвертируется в базовую валюту
            ],'runtime' => [
                new \Bitrix\Main\Entity\ReferenceField(
                    'SectionTable',
                    \Bitrix\Iblock\SectionTable::class,
                    [ '=this.IBLOCK_SECTION_ID' => 'ref.ID'],
                    ['join_type' => 'inner']
                )
            ]
        ]);

        while ($elementsID = $resElementsID->fetch()) {
            $arElementsID[] = $elementsID['IBLOCK_ELEMENT_ID'];
        }
        $arItem = \Bitrix\Iblock\ElementTable::getList(
            [
            'filter' => ['=ID' => $arElementsID, 'ACTIVE' => 'Y'],
            'order' =>  ['PriceTable.PRICE_SCALE' => 'asc'],
            'select' => [
                'PriceTable.PRICE_SCALE', // Сумма конвертируется в базовую валюту
            ],
            'limit' => 1,
            'runtime' => [
                new \Bitrix\Main\Entity\ReferenceField(
                    'PriceTable',
                    \Bitrix\Catalog\PriceTable::class,
                    ['=this.ID' => 'ref.PRODUCT_ID', $priceGroup => 'ref.CATALOG_GROUP_ID'],
                    ['join_type' => 'RIGHT']
                )
            ]
        ]
        )->fetchRaw();
        if (!empty($arItem)) {
            // Получаем форматированную цену в базовой валюте
            $minPriceSection = \CCurrencyLang::CurrencyFormat(reset($arItem), \Bitrix\Currency\CurrencyManager::getBaseCurrency());
        }
        return $minPriceSection;
    }
}
