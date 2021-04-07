<?php
session_start();

$file = fopen("cats.txt", "r");
$visitors_file = fopen("log.json", "a+");

$cache_file = './data/cache'.$_GET["N"];
$time  = null;
$change_every = 60;

if ($file) {
    $array = explode("\n", fread($file, filesize("cats.txt")));
}

if(is_file($cache_file)) {
    list($n, $time, $cat1, $cat2, $cat3) = explode(', ', file_get_contents($cache_file));
}

if(!$time || time() - $time > $change_every) {
    $cat1 = rand(0,count($array) - 3);
    $cat2 = rand($cat1 + 1,count($array) - 2);
    $cat3 = rand($cat2 + 1,count($array) - 1);
    if ($_GET["N"] > 0 && $_GET["N"] < 1000001) {
        file_put_contents($cache_file, $_GET["N"] . ', ' . time() . ', ' . $cat1 . ', ' . $cat2 . ', ' . $cat3);
    }
}

if ($_GET["N"] > 0 && $_GET["N"] < 1000001) {
    echo ($array[$cat1]) . ', ' . ($array[$cat2]) . ', ' . ($array[$cat3]);
} else {
    echo "Pick a number between 1 and 1000000!";
}

if ($_GET["N"] > 0 && $_GET["N"] < 1000001) {
    //Data
    fwrite($visitors_file, "datetime: ");
    date_default_timezone_set('Europe/Vilnius');
    $inTwoMonths = 60 * 60 * 24 * 60 + time();
    setcookie('lastVisit', date("Y-m-d H:i:s"), $inTwoMonths);
    if (isset($_COOKIE['lastVisit'])) {
        $visit = $_COOKIE['lastVisit'];
    }
    fwrite($visitors_file, $visit);

    //N
    fwrite($visitors_file, ", N: " . $_GET["N"]);

    //Kates
    fwrite($visitors_file, ', Cats: [' . ($array[$cat1]) . ', ' . ($array[$cat2]) . ', ' . ($array[$cat3]) . ']');

    //Visi lankytojai
    if (isset($_SESSION['views'])) {
        $_SESSION['views'] = $_SESSION['views'] + 1;
    } else {
        $_SESSION['views'] = 1;
    }
    fwrite($visitors_file, ", countAll: " . $_SESSION['views']);

    //Puslapio lankytojai
    if (isset($_SESSION['views'. $_GET["N"]])) {
        $_SESSION['views'. $_GET["N"]] = $_SESSION['views'. $_GET["N"]] + 1;
    } else {
        $_SESSION['views'. $_GET["N"]] = 1;
    }
    fwrite($visitors_file, ", countN: " . $_SESSION['views'. $_GET["N"]] . "\n");

    fclose($file);
    fclose($visitors_file);
}