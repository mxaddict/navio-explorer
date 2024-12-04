<?php

session_start();

$networks = [
    "testnet",
    "mainnet",
];

if (!isset($_SESSION["network"])) {
    $_SESSION["network"] = $networks[0];
}

if (isset($_GET["network"])) {
    if (in_array($_GET["network"], $networks)) {
        $_SESSION["network"] = $_GET["network"];
    }
}

// This is just to editor does not complain about it being not declared
$DB_HOST = "";
$DB_NAME = "";
$DB_USER = "";
$DB_PASS = "";

include "db.{$_SESSION["network"]}.php";

try {
    $GLOBALS['dbh'] = new PDO('mysql:host=' . $DB_HOST.';dbname='.$DB_NAME, $DB_USER, $DB_PASS);
    $GLOBALS['dbh']->query("SET NAMES 'utf8'");
    $GLOBALS['dbh']->query("SET CHARACTER SET utf8");
    $GLOBALS['dbh']->query("SET COLLATION_CONNECTION = 'utf8_general_ci'");
    $GLOBALS['dbh']->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $GLOBALS['dbh']->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
} catch (PDOException $e) {
    echo "Database Connection Error : " . $e->getMessage();
}

function pretty_dump($arr, $d = 0)
{
    if ($d == 1) {
        echo "<pre>";
    } // HTML Only

    if (is_array($arr)) {
        foreach ($arr as $k => $v) {
            for ($i = 0;$i < $d;$i++) {
                echo "\t";
            }
            if (is_array($v)) {
                echo "<span class='text-gray-400'>" . $k . "</span>" . PHP_EOL;
                pretty_dump($v, $d + 1);
            } else {
                echo "<span class='text-gray-500'>" . $k . "</span>" .":".$v.PHP_EOL;
            }
        }
    }

    if ($d == 1) {
        echo "</pre>";
    } // HTML Only
}

function title($title)
{
    echo "<title>".$title." - Navio Explorer</title>";
}
