<?php

use Bitrix\Main\ModuleManager;
use Bitrix\Main\EventManager;
use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);
class isaev_seotemplate extends CModule
{
    public const MODULE_ID = "isaev.seotemplate";
    public $MODULE_ID = "isaev.seotemplate";
    public $MODULE_VERSION;
    public $MODULE_VERSION_DATE;
    public $MODULE_NAME;
    public $MODULE_DESCRIPTION;
    public function __construct()
    {
        $arModuleVersion = array();
        include(dirname(__FILE__) . "/version.php");
        $this->MODULE_VERSION      = $arModuleVersion["VERSION"];
        $this->MODULE_VERSION_DATE = $arModuleVersion["VERSION_DATE"];
        $this->MODULE_NAME         = Loc::getMessage("isaev.seotemplate_MODULE_NAME");
        $this->MODULE_DESCRIPTION  = Loc::getMessage("isaev.seotemplate_MODULE_DESC");
        $this->PARTNER_NAME        = Loc::getMessage("isaev.seotemplate_PARTNER_NAME");
        $this->PARTNER_URI         = Loc::getMessage("isaev.seotemplate_PARTNER_URI");
    }
    public function installEvents()
    {
        $eventManager = EventManager::getInstance();
        $eventManager->registerEventHandler("main", "OnAdminTabControlBegin", self::MODULE_ID, "\Isaev\Seotemplate\Editseotab", "onAdminTabControlBegin");
        $eventManager->registerEventHandler("iblock", "OnTemplateGetFunctionClass", self::MODULE_ID, "\Isaev\Seotemplate\Ontemplategetfunctionclass", "eventHandler");
        return true;
    }
    public function unInstallEvents()
    {
        $eventManager = EventManager::getInstance();
        $eventManager->unRegisterEventHandler("main", "OnAdminTabControlBegin", self::MODULE_ID, "\Isaev\Seotemplate\Editseotab", "onAdminTabControlBegin");
        $eventManager->unRegisterEventHandler("iblock", "OnTemplateGetFunctionClass", self::MODULE_ID, "\Isaev\Seotemplate\Ontemplategetfunctionclass", "eventHandler");
        return true;
    }
    public function doInstall()
    {
        ModuleManager::registerModule(self::MODULE_ID);
        $this->installEvents();
        return true;
    }
    public function doUninstall()
    {
        $this->unInstallEvents();
        ModuleManager::unRegisterModule(self::MODULE_ID);
        return true;
    }
}
