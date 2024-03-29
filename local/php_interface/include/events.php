<?php
AddEventHandler("iblock", "OnBeforeIBlockElementUpdate", Array("MyClass", "OnBeforeIBlockElementUpdateHandler"));


class MyClass
{
    function OnBeforeIBlockElementUpdateHandler(&$arFields)
    {
        if(CModule::includeModule("iblock") && $arFields["IBLOCK_ID"] == IBLOCK_PRODUCT) {
            $count = 0;
            $filter = array(
                "ACTIVE" => "Y",
                "IBLOCK_ID" => intval($arFields["IBLOCK_ID"]),
                "ID" => intval($arFields["ID"]),
                "!SHOW_COUNTER" => false
            );
            $rs = CIBlockElement::GetList(array(), $filter, false, array(), array("SHOW_COUNTER", "ID"));
            while  ($res = $rs->GetNextElement())
            {
                $count = $res->GetFields()["SHOW_COUNTER"];

            }

            if(!empty($count) && $count >= 2 && $arFields["ACTIVE"] == "N") {
                global $APPLICATION;
                $APPLICATION->throwException("Товар невозможно деактивировать, у него $count просмотров");
                return false;
            }
        }

        if($arFields["IBLOCK_ID"] == IBLOCK_SERVICES) {
            // Очищаем кеш по тегу котрому пометили нащ кеш, после обновления инфоблока ID 3
            $GLOBALS["CACHE_MANAGER"]->ClearByTag("my_exam2_tag_" . IBLOCK_SERVICES);
        }
    }
}

AddEventHandler("main", "OnEpilog", array("Test", "statistic"));
AddEventHandler("main", "OnBeforeEventAdd", array("Test", "OnBeforeEventAddHandler"));

class Test
{
    function statistic ()
    {
        if(ERROR_404 == "Y") {
            global $APPLICATION;
            CEventLog::Add(array(
                "SEVERITY" => "INFO",
                "AUDIT_TYPE_ID" => "ERROR_404",
                "MODULE_ID" => "main",
                "DESCRIPTION" => $APPLICATION->GetCurPage(),
            ));
        }

        if(CModule::includeModule("iblock"))
        {
            global $APPLICATION;
            $filter = array("IBLOCK_ID" => ID_IB_METATEG, "NAME" => $APPLICATION->GetCurPage(),  "ACTIVE" => "Y");
            $rs = CIBlockElement::GetList(array(), $filter, false, array(), array("PROPERTY_TITLE", "PROPERTY_DESCRIPTION"));

            while($res = $rs->GetNextElement())
            {
                $APPLICATION->SetPageProperty("title", $res->GetFields()["PROPERTY_TITLE_VALUE"]);
                $APPLICATION->SetPageProperty("description", $res->GetFields()["PROPERTY_DESCRIPTION_VALUE"]);
            }
        }
    }

    function OnBeforeEventAddHandler(&$event, &$lid, &$arFields)
    {
        global $USER;
        if(!empty($arFields["AUTHOR"]) && isset($arFields["AUTHOR"])) {
            $name = $arFields["AUTHOR"];
            $mess = "Пользователь не авторизован, данные из формы: $name пользователя";


            if ($USER->IsAuthorized()) {
                $rs = CUser::GetById($USER->GetId())->fetch();

                $mess = "Пользователь авторизован: ". $rs["ID"] ." (". $rs["LOGIN"] .") " . $rs["NAME"] .", данные из формы: $name пользователя";
            }

            CEventLog::Add(array(
                "SEVERITY" => "INFO",
                "AUDIT_TYPE_ID" => "FEEDBACK",
                "MODULE_ID" => "main",
                "DESCRIPTION" => "Замена данных в отсылаемом письме – [$mess]" ,
            ));
        }
    }
}

// Убрать рабочий стол в админ панели
//AddEventHandler('main', 'OnBuildGlobalMenu', 'ASDFavoriteOnBuildGlobalMenu');
//function ASDFavoriteOnBuildGlobalMenu(&$aGlobalMenu, &$aModuleMenu)
//{
//    global $USER;
//
//    $rs_group = CUser::GetUserGroup($USER->GetId());
//
//    if(in_array(ID_GROUP_CONTENT, $rs_group))
//    {
//        foreach($aModuleMenu as $key => $item) {
//            foreach ($item['items'] as $el) {
//                if($el['items_id'] == MENU_ADMIN_IBLOCK) {
//                    unset($aModuleMenu[$key]);
//                    break;
//                }
//            }
//        }
//        unset($aGlobalMenu["global_menu_desktop"]);
//    }
//}

AddEventHandler('main', "OnBuildGlobalMenu", 'test');

function test(&$param1, &$param2)
{
    $globalMenu = "global_menu_content";
    $subMenus = "menu_iblock_/news";
    $subMenuItem = "menu_iblock_/news/1";

    foreach ($param2 as $subKey => $subMenu)
    {
        if($subMenu["parent_menu"] == $globalMenu && $subMenu["items_id"] == $subMenus) {
            foreach ($subMenu["items"] as $key => $el) {
                if($el["items_id"] != $subMenuItem) {
                    //unset($param2[$subKey]["items"][$key]);
                }
            }
        } else {
            //unset($param2[$subKey]);
        }
    }

    foreach($param1 as $key => $menu)
    {
        if($key != $globalMenu) {
            //unset($param1[$key]);
        }
    }
    // echo "<pre>";var_dump($param1,$param2 );echo "</pre>";
}