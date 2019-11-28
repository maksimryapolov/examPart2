<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */

//echo "<pre>";var_dump($arResult["RESULT"]);echo "<pre>";
?>
<div>
    <h5>Каталог:</h5>
    <ul>
        <?foreach ($arResult["RESULT"] as $arItem):?>
            <li>
                <b><?=$arItem["ITEMS_NEWS"];?></b>&nbsp;&mdash;&nbsp;<?=$arItem["ACTIVE_FROM"];?>
                (<?foreach ($arItem["SECTION"] as $arSect):?>
                    <?=$arSect["SECT_NAME"]; echo end($arItem["SECTION"]) != $arSect ? "," : "";?>
                <?endforeach;?>)
            </li>
           <?foreach ($arItem["ITEMS"] as $arElement):?>
            <ul>
               <?foreach ($arElement as $el):?>
                   <li>
                        <?=$el["NAME"];?>&nbsp;&mdash;&nbsp;<?=$el["PROPERTY_PRICE_VALUE"];?>&nbsp;&mdash;&nbsp;<?=$el["PROPERTY_ARTNUMBER_VALUE"];?>&nbsp;&mdash;&nbsp;<?=$el["PROPERTY_MATERIAL_VALUE"];?>
                   </li>
               <?endforeach;?>
            </ul>
           <?endforeach;?>
        <?endforeach;?>
    </ul>
</div>
