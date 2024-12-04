<?=title("Blocks")?>
<div class="p-4 text-white dark:text-white">
	<h6 class="text-lg font-bold text-white dark:text-white">Blocks</h6>
	<p class="text-white dark:text-white"><?=((isset($_GET["page"])?"Page : " . $_GET["page"]:"Latest Blocks"))?></p>
</div>
<div class="relative overflow-x-auto">
	<table class="w-full text-sm text-left rtl:text-right text-gray-900 dark:text-zinc-400">
		<thead class="text-xs text-gray-900 uppercase bg-gray-200 dark:bg-zinc-900 dark:text-white">
			<tr>
				<th scope="col" class="px-6 py-3 text-gray-900 dark:text-white">
					Height
				</th>
				<th scope="col" class="px-6 py-3 text-gray-900 dark:text-white">
					Hash
				</th>
				<th scope="col" class="px-6 py-3 text-gray-900 dark:text-white">
					Time (UTC)
				</th>
				<th scope="col" class="px-6 py-3 text-gray-900 dark:text-white">
					TXs
				</th>
				<th scope="col" class="px-6 py-3 text-gray-900 dark:text-white">
					Size
				</th>
			</tr>
		</thead>
		<tbody>
			<?
			while($row=$q->fetch(PDO::FETCH_ASSOC))
			{
				$blockData=json_decode($row["blockData"]);
				?>
				<tr class="bg-white text-gray-900 border-b dark:bg-zinc-800 dark:border-zinc-900 dark:text-white">
					<th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
						<a class='text-blue-600 dark:text-blue-400' href="block/<?=$row["hash"]?>"><?=$row["block_id"]?></a>
					</th>
					<td class="px-6 py-4">
						<a class='text-blue-600 dark:text-blue-400' href="block/<?=$row["hash"]?>"><?=$row["hash"]?></a>
					</td>
					<td class="px-6 py-4 text-gray-900 dark:text-white">
						<?
						$epoch = $blockData->time;
						$dt = new DateTime("@$epoch");
						echo $dt->format('Y-m-d H:i:s');
						?>
					</td>
					<td class="px-6 py-4 text-gray-900 dark:text-white">
						<?=($blockData->nTx==1?"Empty":"Not Empty")?>
					</td>
					<td class="px-6 py-4 text-gray-900 dark:text-white">
						<?=$blockData->size?>
					</td>
				</tr>
				<?
			}
			?>
		</tbody>
	</table>
</div>
<?
}
else
{
	?>
	<p class="text-gray-900 dark:text-white">No blocks found.</p>
	<?
}
}
catch (PDOException $e)
{
	echo $e->getMessage();
}
?>
</div>

<div class="p-4">
	<?
	$record_per_page=10;
	try
	{
		$sql="SELECT max(block_id) AS t FROM `blocks` WHERE blocks.network_id=:network_id";
		$q=$GLOBALS['dbh']->prepare($sql);
		$q->bindParam(':network_id',$GLOBALS["network_id"], PDO::PARAM_INT);
		$q->execute();
		if ($q->rowCount()>0)
		{
			$row=$q->fetch(PDO::FETCH_ASSOC);
			$total_blocks=$row["t"];
			$total_page=ceil($total_blocks/$record_per_page);
		}
        $offset = '';
		if (isset($_GET["page"]))
		{
			$offset=" OFFSET ".($record_per_page*intval($_GET["page"]-1));
		}
		$sql="SELECT 
		blocks.block_id,
		blocks.hash,
		blocks.data as blockData
		FROM blocks
		WHERE blocks.network_id=:network_id
		ORDER BY blocks.id DESC LIMIT ".$record_per_page.$offset;
		$q=$GLOBALS['dbh']->prepare($sql);
		$q->bindParam(':network_id',$GLOBALS["network_id"], PDO::PARAM_INT);
		$q->execute();
		if ($q->rowCount()>0)
		{
			if (isset($_GET["page"]))
			{
				$nextpage=$_GET["page"]+1;
				$current_page=$_GET["page"];
				$previuouspage=$_GET["page"]-1;
			}
			else
			{
				$current_page=1;
				$nextpage=2;
				$previuouspage=-1;
			}
			?>
			<div class="mt-5 mb-10">
				<a href="blocks/1" class="text-center inline-flex items-center text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
					<path stroke-linecap="round" stroke-linejoin="round" d="M6.75 15.75 3 12m0 0 3.75-3.75M3 12h18" />
				</svg>
			First</a>
			<?
			if ($previuouspage>0)
			{
				?>
				<a href="blocks/<?=$previuouspage?>" class="text-center inline-flex items-center text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
					<path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
				</svg>
			</a>
			<?
		}
		else
		{
			?>
			<a class="text-center inline-flex items-center text-white bg-gray-700 hover:bg-gray-800 focus:ring-4 focus:ring-gray-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-gray-600 dark:hover:bg-gray-700 focus:outline-none dark:focus:ring-gray-800"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
				<path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
			</svg>
		</a>
		<?
	}
	?>
	<span class="text-white dark:text-white me-2 text-center inline-flex items-center"><?=$current_page?> / <?=$total_page?></span>
	<?
	if ($current_page<$total_page)
	{
		?>
		<a href="blocks/<?=$nextpage?>" class="text-center inline-flex items-center  text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
			<path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
		</svg>
	</a>
	<?
}
else
{
	?>
	<a class="text-center inline-flex items-center text-white bg-gray-700 hover:bg-gray-800 focus:ring-4 focus:ring-gray-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-gray-600 dark:hover:bg-gray-700 focus:outline-none dark:focus:ring-gray-800"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
		<path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
	</svg>
</a>
<?
}
?>
<a href="blocks/<?=$total_page?>" class="text-center inline-flex items-center text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
	<path stroke-linecap="round" stroke-linejoin="round" d="M17.25 8.25 21 12m0 0-3.75 3.75M21 12H3" />
</svg>
Last</a>
</div>
