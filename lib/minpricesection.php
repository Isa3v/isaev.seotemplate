<?php
/**
 * @author Isaev Danil
 * @package Isaev\Seotemplate
 */

namespace Isaev\Seotemplate;

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
        $priceGroup = null;               // Тип цены по умолчанию
        $sectionId  = $this->data['id'];  // Раздел по умолчанию

        // Доп параметры для выборки
        $functions = [
            'RAW'          => false,   // Вывод цены без форматирования
            'IS_AVAILABLE' => false,   // Проверка только в доступных
        ];

        // Проверяем паратры
        foreach ($parameters as $param) {
            $param = strtoupper($param);
            if (stripos($param, 'GROUP_') !== false) { // Группа цен
                $priceGroup = str_ireplace('GROUP_', '', $param);
            } elseif (isset($functions[$param])) { // Доп. параметры
                $functions[$param] = true;
            } else {
                $sectionId = (int) $param; // Раздел
            }
        }

        // Получаем основной раздел
        $section = \Bitrix\Iblock\SectionTable::query()
            ->where('ID', $sectionId)
            ->addSelect('LEFT_MARGIN')
            ->addSelect('RIGHT_MARGIN')
            ->addSelect('IBLOCK_ID')
            ->addSelect('ID')
            ->fetchObject();

        if (!$section) {
            return false;
        }

        // Составляем запрос для получения элементво привязанных к секциям и их доступность
        $elements = \Bitrix\Iblock\SectionElementTable::query()
            ->where('IBLOCK_SECTION.LEFT_MARGIN', '>=', $section->get('LEFT_MARGIN'))
            ->where('IBLOCK_SECTION.RIGHT_MARGIN', '<=', $section->get('RIGHT_MARGIN'))
            ->where('IBLOCK_SECTION.IBLOCK_ID', $section->get('IBLOCK_ID'))
            ->where('IBLOCK_ELEMENT.ACTIVE', 'Y')
            ->addSelect('IBLOCK_ELEMENT_ID')
            ->fetchCollection();

        if (!$elements) {
            return false;
        }

        $productsListIds = array_unique($elements->getIblockElementIdList());

        // get sku product
        $arSkuList = [];
        $arSkuList = \CCatalogSku::getOffersList($productsListIds, $section->get('IBLOCK_ID'), ['ACTIVE' => 'Y'], ['ID']);
        if (!empty($arSkuList)) {
            $skuListIds = [];
            foreach ($arSkuList as $value) {
                $skuListIds = array_merge($skuListIds, array_keys($value));
            }
        }

        // merge elements
        if (!empty($skuListIds)) {
            $productsListIds = array_merge($productsListIds, $skuListIds);
        }

        if (empty($productsListIds)) {
            return false;
        }

        $query = \Bitrix\Catalog\PriceTable::query()
            ->whereIn('ELEMENT.ID', $productsListIds)
            ->where('ELEMENT.ACTIVE', 'Y')
            ->where('PRICE_SCALE', '>', 0)
            ->addOrder('PRICE_SCALE', 'ASC')
            ->addSelect('PRICE_SCALE')
            ->addSelect('CURRENCY')
            ->setLimit(1);

        if ($functions['IS_AVAILABLE'] === true) {
            $query->where('PRODUCT.AVAILABLE', 'Y');
        }

        if ($priceGroup) {
            $query->where('CATALOG_GROUP_ID', $priceGroup);
        }

        $priceObject = $query->fetchObject();

        if ($priceObject) {
            if ($functions['RAW'] === false) {
                $result = html_entity_decode(\CCurrencyLang::CurrencyFormat($priceObject->get('PRICE_SCALE'), $priceObject->get('CURRENCY')));
            } else {
                $result = html_entity_decode($priceObject->get('PRICE_SCALE'));
            }
        }

        return $result;
    }
}
