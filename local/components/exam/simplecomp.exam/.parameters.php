<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
/** @var array $arCurrentValues */

$arComponentParameters = array(
	"GROUPS" => array(
	),
	"PARAMETERS" => array(
		"IBLOCK_ID" => array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("T_IBLOCK_DESC_LIST_ID"),
			"TYPE" => "STRING",
			"ADDITIONAL_VALUES" => "Y",
		),
        "IBLOCK_ID_CLASSIFIC" => array(
            "PARENT" => "BASE",
            "NAME" => GetMessage("T_IBLOCK_DESC_LIST_ID_CLASSIF"),
            "TYPE" => "STRING",
        ),
        "CODE_CLASSIFIC" => array(
            "PARENT" => "BASE",
            "NAME" => GetMessage("T_CODE_CLASSIF"),
            "TYPE" => "STRING",
        ),
		"CACHE_TIME"  =>  array("DEFAULT"=>36000000),
	),
);

