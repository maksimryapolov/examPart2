<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
$arComponentParameters = array(
	"PARAMETERS" => array(
		"NEWS_IBLOCK_ID" => array(
			"NAME" => GetMessage("SIMPLECOMP_EXAM2_CAT_IBLOCK_ID"),
			"TYPE" => "STRING",
		),
        "CODE_PROP" => array(
            "NAME" => GetMessage("SIMPLECOMP_EXAM2_CAT_ICODE_PROP"),
            "TYPE" => "STRING",
        ),
        "CODE_PROP_USER" => array(
            "NAME" => GetMessage("SIMPLECOMP_EXAM2_CAT_ICODE_PROP_USER"),
            "TYPE" => "STRING",
        ),
        "CACHE_TIME"  =>  array("DEFAULT"=>36000000),
	),

);