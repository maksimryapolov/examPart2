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

CPageOption::SetOptionString("main", "nav_page_in_session", "N");

if(!isset($arParams["CACHE_TIME"]))
	$arParams["CACHE_TIME"] = 36000000;

$arParams["IBLOCK_ID"] = trim($arParams["IBLOCK_ID"]);
$arParams["IBLOCK_ID_CLASSIFIC"] = trim($arParams["IBLOCK_ID_CLASSIFIC"]);
$arParams["CODE_CLASSIFIC"] = trim($arParams["CODE_CLASSIFIC"]);
$arParams["DETAIL_URL"] = trim($arParams["DETAIL_URL"]);

$arNavParams = array(
    "nPageSize" => 1,
    "bShowAll" => "Y",
);
$arNavigation = CDBResult::GetNavParams($arNavParams);

if(empty($arParams["CODE_CLASSIFIC"]))
    $arParams["CODE_CLASSIFIC"] = "UF_NEWS_LINK";

$additional_filter = array();
if ($_REQUEST["filter"] == "Y") {
    $additional_filter = array(
        "LOGIC" => "OR",
        array("<=PROPERTY_PRICE" => 1700, "=PROPERTY_MATERIAL" => "Дерево, ткань"),
        array("<PROPERTY_PRICE" => 1500, "=PROPERTY_MATERIAL" => "Металл, пластик")
    );
}

//if($this->startResultCache(false, array($additional_filter, $arNavigation)))

/*
 * [ex2-107] Автоматический сброс кеша в компоненте при изменении элемента информационного блока «Услуги».
 * Не забыть очистить кеш по тегу в событии
*/

$cache_time = $arParams["CACHE_TIME"];
$cache_id = md5(serialize($arParams)); //Создание ID кеша если параметры изменятся $arParams соответственно и ID будет другой и кеш инициализируется другой
$cache_path = "/exam_cache"; //Папка для хранения кеша относительно /bitrix/cache/

// Создание объекта кеширования
$obCache = new CPHPCache();

// При нахождении кеша в указанной папке return true

if($obCache->InitCache($cache_time, $cache_id, $cache_path))
{
    // Получаем необходимые переменные из кеша
    $arResult = $obCache->GetVars()["arResult"];
    // вывод в шаблон
    $obCache->Output();
}
else if($obCache->StartDataCache()) //кеш инициализируется
{
    global $CACHE_MANAGER; //Глобальный объект для создания тэг метки кеша
    $CACHE_MANAGER->StartTagCache($cache_path); //Начало создание метки тега передается путь к кешу
    $CACHE_MANAGER->RegisterTag("my_exam2_tag_" . IBLOCK_SERVICES); // Сам тег по которому на который будем ориентироваться при сбрасывании

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
    $count = 0;

	$rsCl = CIBlockELement::GetList(
	    array(),
        array("ACTIVE" => "Y", "IBLOCK_ID" => intval($arParams["IBLOCK_ID_CLASSIFIC"])),
        false,
        $arNavParams,
        array("NAME", "ID")
    );

	while ($resCL = $rsCl->GetNextElement())
    {
        ++$count;
        $myResult[$resCL->GetFields()["ID"]] = array(
            "ID" => $resCL->GetFields()["ID"],
            "NAME" => $resCL->GetFields()["NAME"]
        );
    }

    $main_filter = array("IBLOC_ID" => intval(intval($arParams["IBLOCK_ID"])), "ACTIVE" => "Y", "!PROPERTY_FIRMA" => false);
	if(!empty($additional_filter)) {
        $main_filter[] = $additional_filter;
    }

    $arResult["NAV_STRING"] = $rsCl->GetPageNavStringEx(
        $navComponentObject,
        $arParams["PAGER_TITLE"],
        $arParams["PAGER_TEMPLATE"],
        $arParams["PAGER_SHOW_ALWAYS"],
        $this,
        array()
    );

    $rsEl = CIBlockElement::GetList(
        array(),
        $main_filter,
        false,
        array(),
        array("NAME", "IBLOCK_ID", "ID", "PROPERTY_PRICE", "PROPERTY_ARTNUMBER",  "PROPERTY_MATERIAL", "PROPERTY_FIRMA", "DETAIL_PAGE_URL")
    );
    $rsEl->SetUrlTemplates($arParams["DETAIL_URL"], "", $arParams["IBLOCK_URL"]);

    $res_items = array();

    // [ex2-100] Добавить пункт «ИБ в админке» в выпадающем меню компонента
    // Передать ID инфоблока который нужен для редактирования
    // return array; из массива взять ссылку для перехода в нужный раздел админки
    $arButtonss = CIBlock::GetPanelButtons(
        $arParams["IBLOCK_ID"],
        0,
        0,
        array("SECTION_BUTTONS"=>false, "SESSID"=>false)
    );
    // Метод добавляющий кнопку
    $this->AddIncludeAreaIcons(
        Array(
            Array(
                "TITLE" => "ИБ в админке",
                "URL" => $arButtonss["submenu"]["element_list"]["ACTION_URL"],
                "IN_PARAMS_MENU" => true, //показать в контекстном меню
                "IN_MENU" => false //показать в подменю компонента
            )
        )
    );
    // end!
    $prices = array();

    while ($resEl = $rsEl->GetNextElement())
    {
        $element = $resEl->GetFields();

        $arButtons = CIBlock::GetPanelButtons(
            $element["IBLOCK_ID"],
            $element["ID"],
            0,
            array("SECTION_BUTTONS"=>false, "SESSID"=>false)
        );

        $element["EDIT_LINK"] = $arButtons["edit"]["edit_element"]["ACTION_URL"];
        $element["DELETE_LINK"] = $arButtons["edit"]["delete_element"]["ACTION_URL"];
        if($myResult[$element["PROPERTY_FIRMA_VALUE"]])
        {
            $myResult[$element["PROPERTY_FIRMA_VALUE"]]["ITEMS"][] = $element;
            $prices[] = $element["PROPERTY_PRICE_VALUE"];
        }
    }

    $arResult["MAX_PRICE"] = max($prices);
    $arResult["MIN_PRICE"] = min($prices);

    $arResult["RESULT"] = $myResult;
	$this->setResultCacheKeys(array("RESULT"));
	$this->includeComponentTemplate();
	$APPLICATION->SetTitle("Разделов: $count");
    $CACHE_MANAGER->EndTagCache(); // Конец метки тега кеша
    // завершение кешируемой области сохраним все необходимые переменные
	$obCache->EndDataCache(array(
        "arResult" => $arResult
    ));
}