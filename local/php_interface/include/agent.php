<?
/*
 * [ex2-54] Подсчет количества зарегистрированных пользователей
 * */
function CheckUserCount()
{
    // Объявить необходимые переменные
    $format = "d.m.Y";          // Формат даты для работы
    $date_name = "LAST_TAME";   // Ключ по которому записываем/получаем дату COption
    $diff_days = 1;             // Разница дней при которой надо вести учёт
    $module = "main";           // ID модуля

    // Важно запомнить работу с классом DateTime
    // Получаем объект DateTime с текущей настрокой даты и вермя
    $now = new DateTime();

    // Получить позже сохраненную дату, т.е. при последной отработке агента
    $last_time = COption::GetOptionString($module, $date_name);

    // Проверить если значение 0 значит функция исполняется 1-ый раз
    if(empty($last_time) || $last_time == 0) {
        // Значит получить в переменную последней даты, текущую дату
        $last_time = new DateTime();
        // Сохраним текущую дату
        COption::SetOptionString($module, $date_name, $last_time->format($format));
    } else { // Если значение не 0...
        // Получаем экземпляр класса с настройкой времени которое получили раннее (строка 25)
        $last_time = new DateTime($last_time);
    }

    // Получить разницу времени в данном случае получаем в днях
    $diff_date = intval($last_time->diff($now)->format("%R%a"));

    //Проверим если разница равна 1 дню или более посчитаем пользователей и отправим письма
    if ($diff_date >= $diff_days)
    {
        $order = array('id');
        $tmp = 'sort';

        /*
         *  С таким фильтром получаем пользователей в границах времени C($last_time) /  ПО($now)
         * при этом привести к формату обязательно к такому формату какой используется на сайте в настройках
         * $arFilter = array(
                "DATE_REGISTER_1" => "29.08.2017 00:51:59",
                "DATE_REGISTER_2" => "31.08.2017 23:59:59"
            );
         */
        $arFilter = array(
            "ACTIVE" => "Y",
            "DATE_REGISTER_1" => $last_time->format($format),
            "DATE_REGISTER_2" => $now->format($format)
        );

        $rs_users = CUser::GetList($order,$tmp , $arFilter);
        $i = 0;

        while ($user = $rs_users->GetNext())
        {
            ++$i;
        }

        // Получаем пользователй которые состоят в группе администраторов
        /*
         * "GROUPS_ID" => array(1)
         * */
        $rs_users_group = CUser::GetList($order, $tmp, array("ACTIVE" => "Y", "GROUPS_ID" => array(GROUP_ADMINS)));

        $emails_to = array();
        while ($group = $rs_users_group->GetNext())
        {
            $emails_to[] = $group["EMAIL"];
        }

        // Отправка письма
        CEvent::Send(
            "COUNT_REGISTER_USERS", // Тип события
            SITE_ID,                  // ID Сайта
            array(                       //Параметры
                "DAYS" => $diff_date,
                "COUNT" => $i,
                "EMAIL_TO" => implode(",", $emails_to) // Массив email'ов разбить в строку implode(",")
            )
        );

        // Сохранить дату которую получам в начале
        COption::SetOptionString($module, $date_name, $now->format($format));
    }
    return "CheckUserCount();";
}