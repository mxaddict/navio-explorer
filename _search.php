<?
include "init.php";
try
{
	if (!is_numeric($_GET["q"]))
	{
		$sql="SELECT * FROM blocks WHERE hash=:hash AND network_id=:network_id LIMIT 1";
		$q=$GLOBALS['dbh']->prepare($sql);
		$q->bindParam(':hash',$_GET["q"] , PDO::PARAM_STR);
		$q->bindParam(':network_id',$GLOBALS["network_id"], PDO::PARAM_INT);
	}
	else
	{
		$sql="SELECT * FROM blocks WHERE block_id=:block_id AND network_id=:network_id LIMIT 1";
		$q=$GLOBALS['dbh']->prepare($sql);
		$q->bindParam(':block_id',$_GET["q"] , PDO::PARAM_STR);
		$q->bindParam(':network_id',$GLOBALS["network_id"], PDO::PARAM_INT);
	}
	$q->execute();
	if ($q->rowCount()>0)
	{
		$row=$q->fetch(PDO::FETCH_ASSOC);
		$data=json_decode($row["data"],true);
		header("location:../block/".$row["hash"]);
		exit();
	}
	else
	{
		include "header.php";
		?>
		<?=title("Search")?>
		<div class="p-4 w-full">
			<h4 class="text-lg font-bold text-white dark:text-white">Search</h4>
			<p class="text-white dark:text-white">No block matching your search criteria was found.</p>
		</div>
		<?
		include "footer.php";
	}
}
catch (PDOException $e)
{
	echo $e->getMessage();
}
?>
