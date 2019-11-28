<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<h3>exampage:</h3>
<?
foreach ($arResult["VARIABLES"] as $key => $var) {
    echo $key . ": " . $var;
    echo "<br>";
}?>
