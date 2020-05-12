<?php
session_start();

$file = fopen("cats.txt", "r");
$visitors_file = fopen("log.json", "a+");

if ($file) {
    $array = explode("\n", fread($file, filesize("cats.txt")));
}

if (isset($_GET["N"]))
{
    $i = $_GET["N"];
    echo $array[$i-1];
    echo ", ";
    echo $array[$i];
    echo ", ";
    echo $array[$i+1];
}
else {
    echo " ";
}

///////////////////////////PARASOMA DATA
fwrite($visitors_file, "datetime: ");
date_default_timezone_set('Europe/Vilnius');
$inTwoMonths = 60 * 60 * 24 * 60 + time();
setcookie('lastVisit', date("Y-m-d H:i:s"), $inTwoMonths);
if(isset($_COOKIE['lastVisit']))

{
    $visit = $_COOKIE['lastVisit'];
}
fwrite($visitors_file, $visit);
///////////////////////////

///////////////////////////PARASOMAS N
fwrite($visitors_file, ", N: ");
if (isset($_GET["N"]))
{
    fwrite($visitors_file, $_GET["N"]);
}
///////////////////////////

///////////////////////////SURASOMOS KATES
fwrite($visitors_file, ", Cats: ");
$i = $_GET["N"];
fwrite($visitors_file,"[");
fwrite($visitors_file, $array[$i-1]);
fwrite($visitors_file,", ");
fwrite($visitors_file, $array[$i]);
fwrite($visitors_file, ", ");
fwrite($visitors_file, $array[$i+1]);
fwrite($visitors_file,"]");
///////////////////////////

////////////////////////////SKAICIUOJAMI VISI LANKYTOJAI
if(isset($_SESSION['views']))
    $_SESSION['views'] = $_SESSION['views']+1;
else
    $_SESSION['views']=1;
fwrite($visitors_file, ", countAll: ");
fwrite($visitors_file, $_SESSION['views']);

///////////////////////////SKAICIUOJAMA N
fwrite($visitors_file, ", countN: ");
//fwrite($visitors_file, $lala);
fwrite($visitors_file, "\n");
///////////////////////////

////////////////////////////UZDAROMI FAILAI
fclose($file);
fclose($visitors_file);
///////////////////////////
?>