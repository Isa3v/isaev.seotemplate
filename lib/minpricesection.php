<?php
/**
 * @author Isaev Danil
 * @package Isaev\Seotemplate
 */
namespace Isaev\Seotemplate;

\Bitrix\Main\Loader::includeModule('iblock');
/**
 * {=minPriceSection} or {=minPriceSection 5 "@param" "@param"} (5 - id section)
 * @param [RAW] - Unformatted price output
 * @param [GROUP_(ID)] - Display the price of the selected group
 */
class Minpricesection extends \Bitrix\Iblock\Template\Functions\FunctionBase
{
    public function onPrepareParameters(\Bitrix\Iblock\Template\Entity\Base $entity, array $parameters)
    {
        $arguments = [];
        // get section ID
        $this->data['id'] = $entity->getId();
        foreach ($parameters as $parameter) {
            $arguments[] = $parameter->process($entity);
        }
        return $arguments;
    }
    
    public function calculate($parameters)
    {
        $priceGroup = ''; // base or number
        \Bitrix\Main\Loader::includeModule("catalog");
        \Bitrix\Main\Loader::includeModule('currency');
        \Bitrix\Main\Loader::includeModule('iblock');

        /**
         * For the future. To add features
         */
        $arFunction = [
            'RAW' => 'isRawCurrency',
            'IS_AVAILABLE' => 'isAvailableProduct',
        ];
        foreach ($arFunction as $function) {
            ${$function} = false; // example $isRawCurrency == false
        }
        /**
         * Check the received template for functions
         */
        foreach ($parameters as $param) {
            $param = ToUpper($param); // Upper bitrix function
            if (stripos($param, 'GROUP_') !== false) {
                $priceGroup = str_ireplace('GROUP_', '', $param); // price group
            } elseif (array_key_exists($param, $arFunction)) {
                ${$arFunction[$param]} = true; // example $isRawCurrency == true
            } else {
                $paramSectionID = (int) $param;
            }
        }
        
        $sectionID = (!empty($paramSectionID) ? $paramSectionID : $this->data['id']);

        // get section
        $section =  \Bitrix\Iblock\SectionTable::getList([
            'filter' => ['ID' => $sectionID],
            'select' => ['LEFT_MARGIN', 'RIGHT_MARGIN', 'IBLOCK_ID', 'ID']
        ])->fetchRaw();
        // get subsection
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
    
        // get all element ID from section and subsection
        $resElementsID = \Bitrix\Iblock\SectionElementTable::getList([
            'filter' => ['=IBLOCK_SECTION_ID' => $arSectionsID],
            'select' => [
                'IBLOCK_ELEMENT_ID',
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
            $arElementsID[] = (int) $elementsID['IBLOCK_ELEMENT_ID'];
        }

         // get sku product 
         $arSkuList = \CCatalogSku::getOffersList($arElementsID, $section['IBLOCK_ID'], array('ACTIVE' => 'Y'), array('ID'));
         $arSkuIDs = [];
         foreach ($arSkuList as $value) {
            $arSkuIDs = array_merge($arSkuIDs, array_keys($value));
         }
         // merge elements
         if (!empty($arSkuIDs)) {
             $arElementsID = array_merge($arElementsID, $arSkuIDs);
         }
         

        // get max price element
        $filterPrice = ['=ID' => $arElementsID, 'ACTIVE' => 'Y'];

        // if param 'IS_AVAILABLE' active
        if($isAvailableProduct === true){
            $filterPrice['=ProductTable.AVAILABLE'] = 'Y';
        }
        
        if (!empty($priceGroup)) {
            $filterPrice['PriceTable.CATALOG_GROUP_ID'] = $priceGroup;
        }
        $arItem = \Bitrix\Iblock\ElementTable::getList(
            [
            'filter' => $filterPrice,
            'order' =>  ['PriceTable.PRICE_SCALE' => 'asc'],
            'select' => [
                'PriceTable.PRICE_SCALE',
            ],
            'limit' => 1,
            'runtime' => [
                new \Bitrix\Main\Entity\ReferenceField(
                    'PriceTable',
                    \Bitrix\Catalog\PriceTable::class,
                    ['=this.ID' => 'ref.PRODUCT_ID'],
                    ['join_type' => 'RIGHT']
                ),
                new \Bitrix\Main\Entity\ReferenceField(
                    'ProductTable',
                    \Bitrix\Catalog\ProductTable::class,
                    [ '=this.ID' => 'ref.ID'],
                    ['join_type' => 'inner']
				)
            ]
        ]
        )->fetchRaw();

        if (!empty($arItem)) {
            /**
             * Functions over the min price
             */
            $rawPrice = (int) reset($arItem);

            if ($isRawCurrency === false) {
                $minPriceSection = html_entity_decode(\CCurrencyLang::CurrencyFormat($rawPrice, \Bitrix\Currency\CurrencyManager::getBaseCurrency()));
            } else {
                $minPriceSection = html_entity_decode($rawPrice);
            }
        }
        return $minPriceSection;
    }
}
