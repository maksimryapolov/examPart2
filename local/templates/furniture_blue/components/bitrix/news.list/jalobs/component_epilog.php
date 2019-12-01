<?$link = $arParams["JALOBS_AJAX"] == "Y" ? $APPLICATION->GetCurPage() : $APPLICATION->GetCurPage() . "?ELEMENT_ID=".$arParams["ELEMENT_ID"];
$result = "";

if(isset($_REQUEST["ELEMENT_ID"]) && !empty($_REQUEST["ELEMENT_ID"])) {
    CModule::includeModule('iblock');
    global $USER;
    $el = new CIBlockElement;

    $name = "Не авторизован";
    if(CUser::isAuthorized()) {
        $res_user = CUser::GetById($USER->GetId())->Fetch();
        $name = $res_user["ID"] . " " . $res_user["LOGIN"] . " " . $res_user["NAME"] . " " . $res_user["LAST_NAME"] . " " . $res_user["SECOND_NAME"];
    }

    $PROP = array(
        "USER" => $name,
        "NEWS" => $_REQUEST["ELEMENT_ID"]
    );

    $arLoadProductArray = Array(
        "IBLOCK_ID"      => IBLOCK_JALOBS,
        "PROPERTY_VALUES"=> $PROP,
        "NAME"           => "Элемент_" . $_REQUEST["ELEMENT_ID"],
        "ACTIVE"         => "Y",
        "ACTIVE_FROM"    => date("d.m.Y h:i:s")
    );

    if($PRODUCT_ID = $el->Add($arLoadProductArray))
    {
        $result = " Ваше мнение учтено, №" . $PRODUCT_ID;

    }
    else
    {
        $result = "Ошибка";
    }
}
?>
<a
        id="jalobs"
        href="<?=$link;?>"
        <?if($arParams["JALOBS_AJAX"] == "Y"):?>data-id="<?=$arParams["ELEMENT_ID"]?>"<?endif;?>
>
    Пожаловаться на новость
</a>
<?
if($arParams["JALOBS_AJAX"] == "Y"):?>

    <script type="text/javascript">
        let link = $("#jalobs");
        link.on("click", function(e) {
            e.preventDefault();
            let id  = $(this).attr("data-id");
            let path  = $(this).attr("href");
            let _this = $(this);
            $.ajax({
                "url": path,
                data: {"ELEMENT_ID": id, "AJAX": "Y"},
                success: function (res) {
                    _this.after('<span>'+res+'</span>');
                }
            });
        });
    </script>
<?endif;

if($_REQUEST["AJAX"] == "Y") {
    $APPLICATION->RestartBuffer();
    echo $result;
    die;
} else {?>
<span><?=$result?></span>
<?}?>
