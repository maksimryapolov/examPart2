<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<p><b><?=GetMessage("SIMPLECOMP_EXAM2_CAT_TITLE")?></b></p>
<pre>
<?
//print_r($arResult);
?>
</pre>
<?if (!empty($arResult["USERS"])):?>
    <ul>
        <? foreach ($arResult["USERS"] as $item):?>
            <li>
                [<?=$item["ID"];?>]&nbsp;<?=$item["NAME"];?>
                <ul>
                    <?foreach ($item["ITEMS"] as $arElem):?>
                        <li>
                            <?=$arElem["NAME"];?>&nbsp;&mdash;&nbsp;<?=$arElem["ACTIVE_FROM"];?>
                        </li>
                    <?endforeach;?>
                </ul>
            </li>
        <?endforeach;?>
    </ul>
<?else:?>
    <?=GetMessage("SIMPLECOMP_EXAM2_CAT_WARNING");?>
<?endif;?>
