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
				<div class="rocket-bank">
					<h2><?=Loc::getMessage('ISAEV_ROCKET')?></h2>
					<a href="https://rocketbank.ru/isa3v" target="_blank">
						<svg  xmlns="http://www.w3.org/2000/svg" viewBox="0 0 230 60" role="img" width="200px" color="black">
							<title id="logoTitle">Рокетбанк</title><desc id="logoDesc">Рокетбанк</desc>
							<path fill="currentColor" d="M0,30a30,30 0 1, 0 60,0a30,30 0 1, 0 -60,0M44.423 17.03L31.6 47.804a.903.903 0 0 1-1.725-.193c-.74-4.324-2.796-8.266-5.946-11.397a21.2 21.2 0 0 0-11.465-5.91.898.898 0 0 1-.194-1.715l30.965-12.75a.908.908 0 0 1 .365-.077h.02a.9.9 0 0 1 .803 1.27z"></path><path fill="currentColor" d="M86.377 30.132h-4.01v-7.469h4.01c2.4 0 4.041 1.57 4.041 3.735 0 2.228-1.642 3.734-4.04 3.734zm.632-10.67h-8.683v21.09h4.042v-7.187h4.64c2.21 0 4.01-.659 5.43-1.946 1.422-1.318 2.116-2.981 2.116-5.021s-.694-3.703-2.115-4.99c-1.421-1.287-3.22-1.946-5.43-1.946z M104.007 37.79c-2.495 0-4.23-2.04-4.23-4.927 0-2.888 1.735-4.928 4.23-4.928 2.494 0 4.262 2.04 4.262 4.928 0 2.887-1.768 4.927-4.262 4.927zm0-12.993c-2.431 0-4.452.754-5.999 2.26-1.547 1.506-2.336 3.452-2.336 5.806 0 2.353.79 4.3 2.336 5.806 1.547 1.507 3.568 2.26 5.999 2.26 2.462 0 4.483-.754 6.03-2.26 1.547-1.507 2.336-3.453 2.336-5.806 0-2.354-.79-4.3-2.336-5.806-1.547-1.506-3.568-2.26-6.03-2.26z M129.106 25.174h-4.262l-4.64 5.963h-1.264v-5.963h-3.883v15.378h3.883v-6.34h1.358l4.798 6.34h4.452l-6.093-8.003 5.651-7.375z M133.681 31.45c.253-2.165 1.831-3.671 3.883-3.671 2.02 0 3.442 1.443 3.568 3.671h-7.45zm3.883-6.653c-2.336 0-4.23.754-5.714 2.26-1.484 1.506-2.21 3.452-2.21 5.806 0 2.353.79 4.3 2.336 5.806 1.579 1.506 3.6 2.26 6.093 2.26 2.053 0 4.105-.502 6.189-1.539l-1.232-2.73c-1.578.848-3.062 1.287-4.451 1.287-2.652 0-4.641-1.6-4.894-4.08h11.271c.032-.533.063-.973.063-1.287 0-2.29-.694-4.174-2.083-5.617-1.358-1.444-3.157-2.166-5.368-2.166z M145.991 28.218h4.672v12.334h4.042V28.218h4.704v-3.044H145.99v3.044z M169.3 37.728c-1.2 0-2.21-.472-3.03-1.444-.822-.973-1.232-2.197-1.232-3.672 0-2.73 1.768-4.613 4.293-4.613 2.495 0 4.231 1.914 4.231 4.676 0 2.98-1.736 5.053-4.262 5.053zm1.01-12.93c-2.178 0-3.883.753-5.146 2.227.537-2.667 2.273-4.33 5.178-5.053l5.02-1.192-.726-3.044-4.957 1.193c-5.715 1.286-8.682 5.492-8.682 12.113 0 3.013.757 5.43 2.304 7.219 1.547 1.789 3.6 2.667 6.157 2.667 2.336 0 4.262-.784 5.746-2.354 1.484-1.569 2.241-3.608 2.241-6.119 0-2.197-.694-4.017-2.052-5.46-1.357-1.476-3.03-2.198-5.083-2.198z M189.2 34.526c0 2.103-1.421 3.578-3.568 3.578-1.515 0-2.684-.973-2.684-2.228 0-1.601 1.421-2.574 3.726-2.574.915 0 1.736.095 2.526.314v.91zm4.861 3.358c-.631 0-1.073-.439-1.073-1.098V30.54c0-1.726-.6-3.107-1.8-4.142-1.168-1.067-2.746-1.6-4.704-1.6-2.115 0-4.293.438-6.566 1.286l1.01 2.793c1.863-.722 3.536-1.098 5.083-1.098 1.894 0 3.189 1.16 3.189 2.699v.815c-1.01-.219-2.021-.313-3-.313-4.515 0-7.135 1.883-7.135 5.115 0 1.413.505 2.574 1.547 3.484 1.042.91 2.368 1.35 3.978 1.35 1.894 0 3.726-.722 5.083-2.072.537 1.287 1.768 2.071 3.315 2.071.6 0 1.295-.062 2.147-.22l-.284-2.918a3.21 3.21 0 0 1-.79.094z M207.871 31.231h-6.756v-6.057h-4.041v15.378h4.041v-6.277h6.756v6.277h4.042V25.174h-4.042v6.057z M223.907 32.55l5.651-7.376h-4.262l-4.641 5.963h-1.263v-5.963h-3.883v15.378h3.883v-6.34h1.358l4.798 6.34H230l-6.093-8.003z"></path>
						</svg>
					</a>
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