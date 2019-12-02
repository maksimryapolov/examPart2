<?php
function CheckUserCount2()
{
    $now = new DateTime();
    $diff = 0;

    $last_date = COption::GetOptionString("main", "TIME");

    if(empty($last_date)) {
        $last_date = new DateTime();
        COption::SetOptionString("main", "TIME", $last_date->format("d.m.Y"));
    } else {
        $last_date = new DateTime($last_date);
        $diff = intval($last_date->diff($now)->format("%R%a"));
    }

    if(!empty($diff) && $diff >= 1) {

        $count = array();
        $by = "name";
        $order = "asc";
        $filter = array(
            "ACTIVE" => "Y",
            "DATE_REGISTER_1" =>  $now->format("d.m.Y"),
            "DATE_REGISTER_2" => $last_date->format("d.m.Y"),
        );

        $uRes = CUser::GetList(
            $by,
            $order,
            $filter
        );

        while($uItem = $uRes->GetNext()) {
            $count[] = $uItem;
        }

        $filter2 = array("ACTIVE" => "Y", "GROUPS_ID" => array(1));
        $uAdms = CUser::GetList(
            $by,
            $order,
            $filter2
        );
        $arEmail = array();


        while($uAdm = $uAdms->GetNext()) {
            $arEmail[] = $uAdm["EMAIL"];
        }

//        CEvent::Send(
//            "COUNT_REGISTER_USERS",
//            SITE_ID,
//            array(
//                "COUNT" => count($count),
//                "DAYS" => $diff,
//                "EMAIL_TO" => implode(", ", $arEmail)
//            )
//        );
        COption::SetOptionString("main", "TIME", $last_date->format("d.m.Y"));
    }
    //return "CheckUserCount2();";
}

CheckUserCount2();