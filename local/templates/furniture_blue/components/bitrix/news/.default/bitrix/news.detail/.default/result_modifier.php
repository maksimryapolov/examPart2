<?php
if (!empty($arParams["CANONICAL"]))
{
    if(CModule::includeModule("iblock"))
    {
        $rs = CIBlockElement::GetList(array(), array("ACTIVE" => "Y", "IBLOCK_ID" => intval($arParams["CANONICAL"]), "PROPERTY_NEWS_VALUE" => $arResult["ID"]));

        while ($res = $rs->GetNextElement())
        {
            $arResult["CANONICAL"] = $res->GetFields()["NAME"];
        }
        $cp = $this->__component;
        $cp->SetResultCacheKeys(array("CANONICAL"));
    }

}

