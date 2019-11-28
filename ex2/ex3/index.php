<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Простой компонент (материалы)");
?><?$APPLICATION->IncludeComponent(
	"exam:simplecomp.exam3",
	"",
	Array(
		"CACHE_TIME" => "36000000",
		"CACHE_TYPE" => "A",
		"CODE_PROP" => "USER",
		"CODE_PROP_USER" => "UF_AUTHOR_TYPE",
		"NEWS_IBLOCK_ID" => "1"
	)
);?><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>