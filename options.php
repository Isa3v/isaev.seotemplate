<?
/**
 * @author Isaev Danil
 * @package Isaev\Seotemplate
 */

defined('B_PROLOG_INCLUDED') and (B_PROLOG_INCLUDED === true) or die();

use Bitrix\Main\Application;
use Bitrix\Main\Config\Option;
use Bitrix\Main\Localization\Loc;

if (!$USER->isAdmin()) {
	$APPLICATION->authForm();
}
$app = Application::getInstance();
$context = $app->getContext();

Loc::loadMessages(__FILE__);

$aTabs = array(
	array(
		"DIV" => "edit1",
		"TAB" => Loc::getMessage('ISAEV_INFO_MESSAGE_TAB1'),
		"ICON" => "main_user_edit",
		"TITLE" => Loc::getMessage('ISAEV_INFO_MESSAGE_TAB1_TITLE'),
	),
);

$tabControl = new CAdminTabControl("tabControl", $aTabs);
$tabControl->begin();
?>
	<? $tabControl->BeginNextTab(); ?>
	<tr>
		<td colspan="2" align="left">
			<div class="container">
				<div class="adm-info-message">	
					<?=Loc::getMessage('ISAEV_INFO_MESSAGE')?>
				</div>
				<div class="yandex-money">
					<h2><?=Loc::getMessage('ISAEV_YANDEX')?></h2>
					<iframe src="https://money.yandex.ru/quickpay/shop-widget?writer=seller&targets=%D0%92%D0%BD%D0%B5%D1%81%D0%B8%D1%82%D0%B5%20%D1%81%D0%B2%D0%BE%D0%B9%20%D0%B2%D0%BA%D0%BB%D0%B0%D0%B4%20%D0%B2%20%D1%80%D0%B0%D0%B7%D0%B2%D0%B8%D1%82%D0%B8%D0%B5%20%D0%B8%20%D0%BF%D0%BE%D0%B4%D0%B4%D0%B5%D1%80%D1%80%D0%B6%D0%BA%D1%83%20%D0%B4%D0%B0%D0%BD%D0%BD%D0%BE%D0%B3%D0%BE%20%D0%BC%D0%BE%D0%B4%D1%83%D0%BB%D1%8F&targets-hint=&default-sum=400&button-text=14&payment-type-choice=on&mobile-payment-type-choice=on&hint=&successURL=marketplace.1c-bitrix.ru/solutions/isaev.seotemplate/&quickpay=shop&account=410018743055686" width="423" height="250" frameborder="0" allowtransparency="true" scrolling="no"></iframe>
				</div>
				<div class="other-help">
				<h2><?=Loc::getMessage('ISAEV_OTHER_HELP')?></h2>
					<ul>
						<li><a href="//marketplace.1c-bitrix.ru/solutions/isaev.seotemplate/#tab-rating-link" target="_blank"><?=Loc::getMessage('ISAEV_OTHER_HELP_MARKET')?></a></li>
						<li><?=Loc::getMessage('ISAEV_OTHER_HELP_THANKS')?> <a href="//vk.me/id160592285" target="_blank"><?=Loc::getMessage('ISAEV_OTHER_HELP_VK')?></a>, <a href="//teleg.run/isa3v" target="_blank"><?=Loc::getMessage('ISAEV_OTHER_HELP_TG')?></a></li>
					</ul>
				</div>

				<div class="support-help">
				<h2><?=Loc::getMessage('ISAEV_OTHER_HELP_CONTACT')?></h2>
					<ul>
						<li><a href="//vk.com/id160592285" target="_blank"><?=Loc::getMessage('ISAEV_OTHER_HELP_VK')?> Danil Isaev</a></li>
						<li><a href="//teleg.run/isa3v" target="_blank"><?=Loc::getMessage('ISAEV_OTHER_HELP_TG')?> @isa3v</a></li>
						<li><a href="mail:danil.isaev@yahoo.com" target="_blank"><?=Loc::getMessage('ISAEV_OTHER_HELP_MAIL')?> danil.isaev@yahoo.com</a></li>
					</ul>
				</div>
			</div>
		</td>
	</tr>
	<? $tabControl->end(); ?>