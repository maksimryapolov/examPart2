<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
/** @var CBitrixComponent $this */
/** @var array $arParams */
/** @var array $arResult */
/** @var string $componentPath */
/** @var string $componentName */
/** @var string $componentTemplate */
/** @global CDatabase $DB */
/** @global CUser $USER */
/** @global CMain $APPLICATION */

/** @global CIntranetToolbar $INTRANET_TOOLBAR */
global $INTRANET_TOOLBAR;

use Bitrix\Main\Context,
	Bitrix\Main\Type\DateTime,
	Bitrix\Main\Loader,
	Bitrix\Iblock;

if(!isset($arParams["CACHE_TIME"]))
	$arParams["CACHE_TIME"] = 36000000;

$arParams["IBLOCK_ID"] = trim($arParams["IBLOCK_ID"]);
$arParams["IBLOCK_ID_CLASSIFIC"] = trim($arParams["IBLOCK_ID_CLASSIFIC"]);
$arParams["CODE_CLASSIFIC"] = trim($arParams["CODE_CLASSIFIC"]);

if(empty($arParams["CODE_CLASSIFIC"]))
    $arParams["CODE_CLASSIFIC"] = "UF_NEWS_LINK";

if($this->startResultCache(false, array()))
{
	if(!Loader::includeModule("iblock"))
	{
		$this->abortResultCache();
		ShowError(GetMessage("IBLOCK_MODULE_NOT_INSTALLED"));
		return;
	}
	if(is_numeric($arParams["IBLOCK_ID"]))
	{
		$rsIBlock = CIBlock::GetList(array(), array(
			"ACTIVE" => "Y",
			"ID" => $arParams["IBLOCK_ID"],
		));
	}
	else
	{
		$rsIBlock = CIBlock::GetList(array(), array(
			"ACTIVE" => "Y",
			"CODE" => $arParams["IBLOCK_ID"],
			"SITE_ID" => SITE_ID,
		));
	}

	$arResult = $rsIBlock->GetNext();

	$myResult = array();
	$rsCl = CIBlockELement::GetList(
	    array(),
        array("ACTIVE" => "Y", "NAME", "ID", "IBLOCK_ID" => intval($arParams["IBLOCK_ID_CLASSIFIC"])),
        false,
        array(),
        array("NAME", "ID", "ACTIVE_FROM")
    );

	while ($resCL = $rsCl->GetNextElement())
    {
        $myResult[$resCL->GetFields()["ID"]]["ITEMS_NEWS"] = $resCL->GetFields()["NAME"];
        $myResult[$resCL->GetFields()["ID"]]["ACTIVE_FROM"] = $resCL->GetFields()["ACTIVE_FROM"];
    }


    $reSect = CIblockSection::GetList(
        array(),
        array("IBLOCK_ID" => intval($arParams["IBLOCK_ID"]), "!UF_NEWS_LINK" => false),
        false,
        array("NAME", "ID", "UF_NEWS_LINK")
    );

    $arSelectSection = array();

    while ($resSect = $reSect->GetNext())
    {
        foreach ($resSect["UF_NEWS_LINK"] as $key => $item)
        {
            $myResult[intval($item)]["SECTION"][$resSect["ID"]] = ["SECT_NAME" => $resSect["NAME"], "SECT_ID" => $resSect["ID"]];
            if(!in_array($resSect["ID"], $arSelectSection)) {
                $arSelectSection[] = $resSect["ID"];
            }
        }
    }

    $rsEl = CIBlockElement::GetList(
        array(),
        array("IBLOC_ID" => intval(intval($arParams["IBLOCK_ID"])), "ACTIVE" => "Y", "SECTION_ID" => $arSelectSection),
        false,
        array(),
        array("NAME", "ID", "IBLOCK_SECTION_ID", "PROPERTY_PRICE", "PROPERTY_ARTNUMBER",  "PROPERTY_MATERIAL")
    );
    $res_items = array();

    while ($resEl = $rsEl->GetNextElement())
    {
        $element = $resEl->GetFields();

        $res_items[$element["IBLOCK_SECTION_ID"]][] = array(
            "NAME" => $element["NAME"],
            "ID" => $element["ID"],
            "IBLOCK_SECTION_ID" => $element["IBLOCK_SECTION_ID"],
            "PROPERTY_PRICE_VALUE" => $element["PROPERTY_PRICE_VALUE"],
            "PROPERTY_ARTNUMBER_VALUE" => $element["PROPERTY_ARTNUMBER_VALUE"],
            "PROPERTY_MATERIAL_VALUE" => $element["PROPERTY_MATERIAL_VALUE"]
        );
    }
    $count = 0;
    foreach ($res_items as $id_key => $value) {

        foreach ($myResult as &$item)
        {
            if($item["SECTION"][$id_key]) {
                $item["ITEMS"][$id_key] = $value;
                $count += count($value);
            }
        }
    }

    $arResult["RESULT"] = $myResult;
	$this->setResultCacheKeys(array("RESULT"));
	$this->includeComponentTemplate();
	$APPLICATION->SetTitle("В каталоге товаров представлено товаров: $count");
}