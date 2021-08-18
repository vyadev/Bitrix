<?
	if (isset($arResult["CANONICAL_LINK"])) {
			$APPLICATION->SetPageProperty("canonical", $arResult["CANONICAL_LINK"]);
	}
?>

<?CJSCore::Init()?>

	<?if ($_GET["TYPE"] == "REPORT_RESULT"):?>
	<!-- для GET запроса -->
		<script>	
			function textInsert(selector, text) {
				var textElem = document.querySelector(selector);
				textElem.innerText = text;
				window.history.pushState(null, null, "<?=$APPLICATION->GetCurPage()?>");
			}
		</script>

		<?if ($_GET["ID"]):?><!-- для GET запроса с ID новости -->
			<script>
				textInsert("#ajax-report-text", "Ваше мнение учтено, №" + <?=$_GET["ID"]?>);
			</script>
		<?else:?><!-- для GET запроса с ошибкой -->
			<script>
				textInsert("#ajax-report-text", "Ошибка!!!");
			</script>
		<?endif?>

	<?elseif (isset($_GET["ID"])):?>
		<?
			$jsonObj = array();

			if (CModule::IncludeModule("iblock")) {
				$arUser = "";

				if ($USER->IsAuthorized()) {
					$arUser = $USER->GetId(). " (". $USER->GetLogin(). ") ". $USER->GetFullName();
				} else {
					$arUser = "Не авторизован";
				}

				$arField = array(
					"IBLOCK_ID" => IBLOCK_COMPLAINT,
					"NAME" => "Новость ". $_GET["ID"],
					"ACTIVE_FROM" => \Bitrix\Main\Type\DateTime::createFromTimestamp(time()),
					"PROPERTY_VALUES" => array(
						"USER" => $arUser,
						"NEWS" => $_GET["ID"]
					)
				);

				$element = new CIBlockElement(false);

				if ($elId = $element->Add($arField)) {
					$jsonObj["ID"] = $elId;

					if ($_GET["TYPE"] == "REPORT_AJAX") {
						$APPLICATION->RestartBuffer();
						echo json_encode($jsonObj);
						die();
					} else if ($_GET["TYPE"] == "REPORT_GET") {//для GET запроса с ID новости
						LocalRedirect($APPLICATION->GetCurPage(). "?TYPE=REPORT_RESULT&ID=". $jsonObj["ID"]);
					}
				} else {//для GET запроса с ошибкой
					LocalRedirect($APPLICATION->GetCurPage(). "?TYPE=REPORT_RESULT");
				}
			}
		?>
	<?endif?>	