<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true);

$img = SITE_TEMPLATE_PATH. '/img/rew/no_photo.jpg';
if (is_array($arResult["DETAIL_PICTURE"])) {
	$img = $arResult["DETAIL_PICTURE"]["SRC"];
}
?>
<div class="review-block">
	<div class="review-text">
		<div class="review-text-cont">
			<?=$arResult["DETAIL_TEXT"]?>
		</div>
		<div class="review-autor">
			<?=$arResult["NAME"]?>, <?=$arResult["DISPLAY_ACTIVE_FROM"]?> <?=GetMessage("Y")?>, 
			<?=trim($arResult["DISPLAY_PROPERTIES"]["POSITION"]["VALUE"])?>, <?=trim($arResult["DISPLAY_PROPERTIES"]["COMPANY"]["VALUE"])?>.
		</div>
	</div>
	<div style="clear: both;" class="review-img-wrap">
		<img src="<?=$img?>" alt="<?=$arResult["DETAIL_PICTURE"]["ALT"]?>">
	</div>
</div>

<?
	foreach ($arResult["DISPLAY_PROPERTIES"]["DOCUMENTS"]["VALUE"] as $fileId) {
		$fileIds[] = $fileId;
	}

	$res = CFile::GetList([], ["@ID" => $fileIds]);
	while($res_arr = $res->Fetch()) {
		$arFile[] = $res_arr;
	}					
?>

<?if (is_array($arFile)):?>
	<div class="exam-review-doc">
		<p><?=GetMessage("DOCS")?></p>
		<?foreach ($arFile as $file):?>
			<div class="exam-review-item-doc">
				<img class="rew-doc-ico" src="<?=SITE_TEMPLATE_PATH?>/img/icons/pdf_ico_40.png">
				<a href="/upload/<?=$file["SUBDIR"]?>/<?=$file["FILE_NAME"]?>" download=""><?=$file["ORIGINAL_NAME"]?></a>
			</div>
		<?endforeach?>
	</div>
	<hr>
<?endif?>

