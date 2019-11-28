<?php
if(!empty($arResult["CANONICAL"]))
{
    $APPLICATION->SetPageProperty('canonical', $arResult["CANONICAL"]);
}
