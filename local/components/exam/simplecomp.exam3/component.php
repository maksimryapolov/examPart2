<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

use Bitrix\Main\Loader,
	Bitrix\Iblock;

global $USER;

if(!Loader::includeModule("iblock"))
{
	ShowError(GetMessage("SIMPLECOMP_EXAM2_IBLOCK_MODULE_NONE"));
	return;
}

$arParams["NEWS_IBLOCK_ID"] = trim($arParams["NEWS_IBLOCK_ID"]);
$arParams["CODE_PROP"] = trim($arParams["CODE_PROP"]);
$arParams["CODE_PROP_USER"] = trim($arParams["CODE_PROP_USER"]);

if(intval($arParams["NEWS_IBLOCK_ID"]) > 0)
{
//    $arResult["USERS"] = array();
//    if($USER->IsAuthorized()) {
//        $this_user = $USER->GetID();
//        // user
//        $arOrderUser = array("id");
//        $sortOrder = "asc";
//        $arFilterUser = array(
//            "ACTIVE" => "Y",
//            "UF_AUTHOR_TYPE" => CUser::GetById($this_user)->Fetch()["UF_AUTHOR_TYPE"]
//        );
//
//
//        $rsUsers = CUser::GetList($arOrderUser, $sortOrder, $arFilterUser); // выбираем пользователей
//        while($arUser = $rsUsers->GetNext()){
//
//            if($arUser["ID"] != $this_user) {
//                $arResult["USERS"][$arUser["ID"]] = array("ID" => $arUser["ID"], "NAME" => $arUser["NAME"]) ;
//            }
//        }
//
//
//        //iblock elements
//        $arSelectElems = array (
//            "ID",
//            "IBLOCK_ID",
//            "NAME",
//        );
//        $arFilterElems = array (
//            "IBLOCK_ID" => $arParams["NEWS_IBLOCK_ID"],
//            "ACTIVE" => "Y",
//            "!PROPERTY_USER" => false
//        );
//        $arSortElems = array (
//            "NAME" => "ASC"
//        );
//
//        $arResult["ELEMENTS"] = array();
//        $rsElements = CIBlockElement::GetList($arSortElems, $arFilterElems, false, array(), array("NAME", "ID", "PROPERTY_USER", "DATE_ACTIVE_FROM"));
//        $count = array();
//        $arr_id = array();
//        while($arElement = $rsElements->GetNextElement())
//        {
//            if($this_user == $arElement->GetFields()["PROPERTY_USER_VALUE"]) {
//                $arr_id[] = $arElement->GetFields()["ID"];
//            }
//
//
//            if(!empty($arResult["USERS"][$arElement->GetFields()["PROPERTY_USER_VALUE"]]) && !in_array($arElement->GetFields()["ID"], $arr_id)) {
//
//                $arResult["USERS"][$arElement->GetFields()["PROPERTY_USER_VALUE"]]["ITEMS"][] = array(
//                    "NAME" => $arElement->GetFields()["NAME"],
//                    "ACTIVE_FROM" => $arElement->GetFields()["DATE_ACTIVE_FROM"]
//                );
//                if(!in_array($arElement->GetFields()["ID"], $count)) {
//                    $count[] = $arElement->GetFields()["ID"];
//                }
//            }
//
//        }
//    }
//
//    $APPLICATION->SetTitle("Новостей " . count($count));

    if($arParams["NEWS_IBLOCK_ID"] > 0)
    {
        $this_user = $USER->GetId();
        $orderBy = "id";
        $order = "asc";
        $filter = array(
            "ACTIVE" => "Y",
            "UF_AUTHOR_TYPE" => CUser::GetById($this_user)->Fetch()["UF_AUTHOR_TYPE"]
        );

        $arResult["USERS"] = array();

        $arUsers = CUser::GetList($orderBy, $order, $filter);

        while($arUser = $arUsers->GetNext())
        {
            if($this_user !=  $arUser["ID"]) {
                $arResult["USERS"][$arUser["ID"]] = array("ID" => $arUser["ID"], "NAME" => $arUser["NAME"]);
            }
        }
        $filter = array(
            "ACTIVE" => "Y",
            "IBLOCK_ID" => $arParams["NEWS_IBLOCK_ID"],
            "!PROPERTY_USER" => false
        );
        $elements = CIBlockElement::GetList(
            array("NAME" => "ASC"),
            $filter,
            false,
            array(),
            array("DATE_ACTIVE_FROM", "NAME", "ID", "PROPERTY_USER")
            );
        $ids = array();
        $title_count = array();
        while ($res = $elements->GetNextElement())
        {
            $el = $res->GetFields();

            if($el["PROPERTY_USER_VALUE"] == $this_user) {
                $ids[] = $el["ID"];
            }

            if(!empty($arResult["USERS"][$el["PROPERTY_USER_VALUE"]]) && !in_array($el["ID"], $ids)) {
                $arResult["USERS"][$el["PROPERTY_USER_VALUE"]]["ITEMS"][] = array(
                    "NAME" => $el["NAME"],
                    "ACTIVE_FROM" => $el["DATE_ACTIVE_FROM"]
                );
                if(!in_array($el["ID"], $title_count)) {
                    $title_count[] = $el["ID"];
                }
            }
        }
    }
    $APPLICATION->SetTitle("Новостей " . count($title_count));
}
$this->includeComponentTemplate();	
?>