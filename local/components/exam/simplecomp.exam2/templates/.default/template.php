<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */

//echo "<pre>";var_dump($arResult["RESULT"]);echo "<pre>";
?>
<?=time();?>
<a href="<?=$APPLICATION->GetCurPage() . "?filter=Y";?>"><?=$APPLICATION->GetCurPage() . "?filter=Y";?></a>
<div>
    <h5>Каталог:</h5>
    <ul>
        <?foreach ($arResult["RESULT"] as $arItem):?>
        <li>
            <b><?=$arItem["NAME"];?></b>

            <ul>
                <?foreach ($arItem["ITEMS"] as $item):?>
                    <?
                    $this->AddEditAction($item['ID'], $item['EDIT_LINK'], CIBlock::GetArrayByID($item["IBLOCK_ID"], "ELEMENT_EDIT"));
                    $this->AddDeleteAction($item['ID'], $item['DELETE_LINK'], CIBlock::GetArrayByID($item["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
                    ?>
                    <li id="<?=$this->GetEditAreaId($item['ID']);?>">
                        <?=$item["NAME"]?>&nbsp;&mdash;&nbsp;<?=$item["PROPERTY_PRICE_VALUE"]?>&nbsp;&mdash;&nbsp;<?=$item["PROPERTY_ARTNUMBER_VALUE"]?>&nbsp;&mdash;&nbsp;<?=$item["PROPERTY_MATERIAL_VALUE"]?>&nbsp;(<?=$item["DETAIL_PAGE_URL"]?>)
                    </li>
                <?endforeach;?>
            </ul>
        </li>
        <?endforeach;?>
    </ul>
</div>
<?=$arResult["NAV_STRING"];?>
<?if(!empty($arResult["MAX_PRICE"]) && !empty($arResult["MIN_PRICE"])):?>
    <?$this->SetViewTarget("PRICES");?>
        <div style="color:red; margin: 34px 15px 35px 15px">
            Максимальная цена &mdash; <?=$arResult["MAX_PRICE"];?>
            Минимальная цена &mdash; <?=$arResult["MIN_PRICE"];?>
        </div>
    <?$this->EndViewTarget();?>
<?endif;?>
