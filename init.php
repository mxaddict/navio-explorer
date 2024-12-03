<?
session_start();
if (isset($_GET["network_id"]))
{
    $_SESSION["network_id"]=$_GET["network_id"];
}
else
{
    if (empty($_SESSION["network_id"])) $_SESSION["network_id"]=1;
}
if ($_SESSION["network_id"]==1) $_SESSION["network_name"]="testnet";
if ($_SESSION["network_id"]==2) $_SESSION["network_name"]="mainnet";
$GLOBALS["network_name"]=$_SESSION["network_name"];
$GLOBALS["network_id"]=$_SESSION["network_id"];
include "db.php";
try
{
	$GLOBALS['dbh']=new PDO('mysql:host=' . $DBHost.';dbname='.$DBName, $DBUser, $DBPass);
	$GLOBALS['dbh']->query("SET NAMES 'utf8'");
	$GLOBALS['dbh']->query("SET CHARACTER SET utf8");
	$GLOBALS['dbh']->query("SET COLLATION_CONNECTION = 'utf8_general_ci'");
	$GLOBALS['dbh']->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$GLOBALS['dbh']->setAttribute(PDO::ATTR_EMULATE_PREPARES, FALSE);
}
catch(PDOException $e)
{
	echo "Database Connection Error : " . $e->getMessage();
}
function pretty_dump($arr, $d=0){
    if ($d==1) echo "<pre>";    // HTML Only
    if (is_array($arr)){
        foreach($arr as $k=>$v){
            for ($i=0;$i<$d;$i++){
                echo "\t";
            }
            if (is_array($v)){
                echo "<span class='text-gray-400'>" . $k . "</span>" . PHP_EOL;
                Pretty_Dump($v, $d+1);
            } else {
                echo "<span class='text-gray-500'>" . $k . "</span>" .":".$v.PHP_EOL;
            }
        }
    }
    if ($d==1) echo "</pre>";   // HTML Only
}
function title($title)
{
    echo "<title>".$title." - Navio Explorer</title>";
}
?>
