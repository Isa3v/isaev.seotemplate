<?php
/**
 * @author Isaev Danil
 * @package Isaev\Seotemplate
 */
namespace Isaev\Seotemplate;


\Bitrix\Main\Loader::includeModule('iblock');
/**
 * {=maxPriceSection} or {=maxPriceSection 5} (5 - id section)
 */
class Activegoods extends \Bitrix\Iblock\Template\Functions\FunctionBase
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
    
        // Составляем запрос для получения элементво привязанных к секциям и их доступность
        $arActiveID = \Bitrix\Iblock\SectionElementTable::getList([
            'filter' => ['=IBLOCK_SECTION_ID' => $arSectionsID, 'ElementTable.ACTIVE' => 'Y'],
            'select' => [
                'IBLOCK_ELEMENT_ID', // Сумма конвертируется в базовую валюту
            ],'runtime' => [
                new \Bitrix\Main\Entity\ReferenceField(
                    'SectionTable',
                    \Bitrix\Iblock\SectionTable::class,
                    [ '=this.IBLOCK_SECTION_ID' => 'ref.ID'],
                    ['join_type' => 'inner']
                ),
				 new \Bitrix\Main\Entity\ReferenceField(
                    'ElementTable',
                    \Bitrix\Iblock\ElementTable::class,
                    [ '=this.IBLOCK_ELEMENT_ID' => 'ref.ID'],
                    ['join_type' => 'inner']
				)
            ]
        ])->fetchAll();
        
        return count($arActiveID);
    }
}
