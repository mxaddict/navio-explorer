<?
date_default_timezone_set('UTC');
include "init.php";
include "header.php";
if (empty($_GET["a"]))
{
	include "main.php";
}
else
{
	include "_".$_GET["a"].".php";
}
include "footer.php";
?>
</body>
</html>