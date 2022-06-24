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
        $sectionId = (!empty(reset($parameters)) ? reset($parameters) : $this->data['id']);
        $result = false;

        // Получаем основной раздел
        $section =  \Bitrix\Iblock\SectionTable::query()
            ->where('ID', $sectionId)
            ->addSelect('LEFT_MARGIN')
            ->addSelect('RIGHT_MARGIN')
            ->addSelect('IBLOCK_ID')
            ->addSelect('ID')
            ->fetchObject();

        if ($section) {
            // Составляем запрос для получения элементво привязанных к секциям и их доступность
            $result = \Bitrix\Iblock\SectionElementTable::query()
                ->where('IBLOCK_SECTION.LEFT_MARGIN', '>=', $section->get('LEFT_MARGIN'))
                ->where('IBLOCK_SECTION.RIGHT_MARGIN', '<=', $section->get('RIGHT_MARGIN'))
                ->where('IBLOCK_SECTION.IBLOCK_ID', $section->get('IBLOCK_ID'))
                ->where('IBLOCK_ELEMENT.ACTIVE', 'Y')
                ->addSelect('IBLOCK_ELEMENT_ID')
                ->addGroup('IBLOCK_ELEMENT_ID')
                ->countTotal(true)
                ->exec()
                ->getCount();
        }

        return $result;
    }
}
