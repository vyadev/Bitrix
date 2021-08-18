<?
IncludeModuleLangFile(__FILE__);
Class my_module extends CModule
{	
	const MODULE_ID = 'my.module';
	var $MODULE_ID = 'my.module'; 
	var $MODULE_VERSION;
	var $MODULE_VERSION_DATE;
	var $MODULE_NAME;
	var $MODULE_DESCRIPTION;

	function __construct()
	{
		$arModuleVersion = array();
		include(dirname(__FILE__)."/version.php");
		$this->MODULE_VERSION = $arModuleVersion["VERSION"];
		$this->MODULE_VERSION_DATE = $arModuleVersion["VERSION_DATE"];
		$this->MODULE_NAME = GetMessage("my.module_MODULE_NAME");
		$this->MODULE_DESCRIPTION = GetMessage("my.module_MODULE_DESC");

		$this->PARTNER_NAME = GetMessage("my.module_PARTNER_NAME");
		$this->PARTNER_URI = GetMessage("my.module_PARTNER_URI");
	}

	function DoInstall()
	{
		global $DB, $APPLICATION, $step, $errors;

		if ($GLOBALS['APPLICATION']->GetGroupRight('main') < 'W') {
			return;
		}

		$errors = false;

		$this->InstallDB();

		$APPLICATION->IncludeAdminFile(GetMessage("my_module_UNINSTALL_TITLE"), $_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/".self::MODULE_ID."/install/step.php");	
	}


	function InstallDB($arParams = array())
	{
		global $APPLICATION, $DB, $DBType, $errors;

		$errors = false;

		$errors = $DB->RunSQLBatch($_SERVER["DOCUMENT_ROOT"].'/bitrix/modules/'.self::MODULE_ID.'/install/db/'.strtolower($DBType).'/install.sql');

		if (!empty($errors))
		{
			$APPLICATION->ThrowException(implode("", $errors));
			return false;
		}

		RegisterModule(self::MODULE_ID);

		return true;
	}

	function DoUninstall()
	{
		global $DB, $APPLICATION, $step, $errors;

		if ($GLOBALS['APPLICATION']->GetGroupRight('main') < 'W') {
			return;
		}

		$step = IntVal($step);
		if($step < 2)
		{
			$APPLICATION->IncludeAdminFile(GetMessage("my_module_UNINSTALL_TITLE"), $_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/".self::MODULE_ID."/install/unstep.php");
		}
		elseif($step == 2)
		{
			$errors = false;

			$this->UnInstallDB(array(
				"savedata" => $_REQUEST["savedata"],
			));

			$APPLICATION->IncludeAdminFile(GetMessage("my_module_UNINSTALL_TITLE"), $_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/".self::MODULE_ID."/install/unstep1.php");
		}		
	}


	function UnInstallDB($arParams = array())
	{
		global $APPLICATION, $DB, $DBType, $errors;

		if(!array_key_exists("savedata", $arParams) || $arParams["savedata"] != "Y") 
		{
			$errors = false;

			$errors = $DB->RunSQLBatch($_SERVER["DOCUMENT_ROOT"].'/bitrix/modules/'.self::MODULE_ID.'/install/db/'.strtolower($DBType).'/uninstall.sql');

			if (!empty($errors))
			{
				$APPLICATION->ThrowException(implode("", $errors));
				return false;
			}
		}

		UnRegisterModule(self::MODULE_ID);

		return true;
	}

}


