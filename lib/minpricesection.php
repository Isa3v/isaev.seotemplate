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
        // ????????????? id ????????/???????, ????? ????? ???? ?????????? ? ??? ?????????
        $this->data['id'] = $entity->getId();
        foreach ($parameters as $parameter) {
            $arguments[] = $parameter->process($entity);
        }
        return $arguments;
    }
    //?????????? ??????? ??????????? "?????"
    public function calculate($parameters)
    {
        $priceGroup = '1'; // base or number
        \Bitrix\Main\Loader::includeModule("catalog");
        \Bitrix\Main\Loader::includeModule('currency');
        \Bitrix\Main\Loader::includeModule('iblock');
        $sectionID = (!empty(reset($parameters)) ? reset($parameters) : $this->data['id']);

        // ???????? ???????? ??????
        $section =  \Bitrix\Iblock\SectionTable::getList([
            'filter' => ['ID' => $sectionID],
            'select' => ['LEFT_MARGIN', 'RIGHT_MARGIN', 'IBLOCK_ID', 'ID']
        ])->fetchRaw();
        // ???????? ??? ??????????
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
    
        // ?????????? ?????? ??? ????????? ????????? ??????????? ? ???????
        $resElementsID = \Bitrix\Iblock\SectionElementTable::getList([
            'filter' => ['=IBLOCK_SECTION_ID' => $arSectionsID],
            'select' => [
                'IBLOCK_ELEMENT_ID', // ????? ?????????????? ? ??????? ??????
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
                'PriceTable.PRICE_SCALE', // ????? ?????????????? ? ??????? ??????
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
            // ???????? ??????????????? ???? ? ??????? ??????
            $minPriceSection = html_entity_decode(\CCurrencyLang::CurrencyFormat(reset($arItem), \Bitrix\Currency\CurrencyManager::getBaseCurrency()));
        }
        return $minPriceSection;
    }
}
