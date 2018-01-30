<?php

$currentdate = date("Y/m/d");

$sixmonthsafter = date("Y/m/d", strtotime("+6 months", strtotime($currentdate)));

$data = array();

$data[] =  array("Month", "Mid-month Meeting Date", "End of Month Testing Date");

for($i = 0; $i <= 6; $i++){
    $currentmonthdate = date("Y/m/d", strtotime("+".$i." months", strtotime($currentdate)));
    $currentdatearray = explode("/", $currentmonthdate);
    $currentdatearray[2] = '14';

    $tempdata = array();

    $tempdata[] = strval(date("F", strtotime("+".$i." months", strtotime($currentdate))));

    if(find_mid_morning_date($currentdatearray, $currentdate, $sixmonthsafter) != null){
        $tempdata[] = implode('/', find_mid_morning_date($currentdatearray, $currentdate, $sixmonthsafter));
    } else {
        $tempdata[] = "No mid morning date";
    }

    $currentdatearray[2] = date('t',strtotime("+".$i." months", strtotime($currentdate)));
    if(find_last_day_meeting($currentdatearray, $currentdate, $sixmonthsafter) != null){
        $tempdata[] = implode('/', find_last_day_meeting($currentdatearray, $currentdate, $sixmonthsafter));
    } else {
        $tempdata[] = "No end of morning date";
    }

    $data[] = $tempdata;

}

function find_mid_morning_date($tempcurrentdatearray, $tempcurrentdate, $tempsixmonthsafter){
    if(strtotime(implode('/', $tempcurrentdatearray)) > strtotime($tempcurrentdate) && strtotime(implode('/', $tempcurrentdatearray)) < strtotime($tempsixmonthsafter)){
        if(date('l', strtotime(implode('/', $tempcurrentdatearray))) == "Saturday"){
            $tempcurrentdatearray[2] = '16';
        } else if (date('l', strtotime(implode('/', $tempcurrentdatearray))) == "Sunday"){
            $tempcurrentdatearray[2] = '15';
        }
        // To check if there's a change and if it makes it outside the 6 month range
        if(strtotime(implode('/', $tempcurrentdatearray)) < strtotime($tempsixmonthsafter)){
            return $tempcurrentdatearray;
        }
    }
}

function find_last_day_meeting($tempcurrentdatearray, $tempcurrentdate, $tempsixmonthsafter){
    if(strtotime(implode('/', $tempcurrentdatearray)) > strtotime($tempcurrentdate) && strtotime(implode('/', $tempcurrentdatearray)) < strtotime($tempsixmonthsafter)){
        if(date('l', strtotime(implode('/', $tempcurrentdatearray))) == "Friday"){
            $tempcurrentdatearray[2] = strval(intval($tempcurrentdatearray[2]) - 1);
        } else if(date('l', strtotime(implode('/', $tempcurrentdatearray))) == "Saturday"){
            $tempcurrentdatearray[2] = strval(intval($tempcurrentdatearray[2]) - 2);
        } else if(date('l', strtotime(implode('/', $tempcurrentdatearray))) == "Sunday"){
            $tempcurrentdatearray[2] = strval(intval($tempcurrentdatearray[2]) - 3);
        }
        // To check if there's a change and if it makes it outside the 6 month range
        if(strtotime(implode('/', $tempcurrentdatearray)) > strtotime($tempcurrentdate)){
            return $tempcurrentdatearray;
        }
    }
}

$file = new SplFileObject('file.csv', 'w');

foreach ($data as $fields) {
    $file->fputcsv($fields);
}