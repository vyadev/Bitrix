<?		
	if (!empty($arParams["ID_IBLOCK_CANONICAL"])) {
		$arSelect = array("ID", "IBLOCK_ID", "NAME");
		$arFilter = array("IBLOCK_ID" => $arParams["ID_IBLOCK_CANONICAL"], "PROPERTY_NEWS" => $arResult["ID"]);
		$res = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);
		if($arRes = $res->GetNext()) { 
	 		$arResult["CANONICAL_LINK"] = $arRes["NAME"];
 			$this->__component->SetResultCacheKeys(array("CANONICAL_LINK"));
		}
	}
