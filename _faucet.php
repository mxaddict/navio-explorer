<?=title("Faucet Wallet Transaction History")?>
<div class="p-4 w-full">
	<h4 class="text-lg font-bold text-white dark:text-white">Faucet Wallet Transaction History</h4>
	<?
	try
	{
		$sql="SELECT 
		data,
		last_updated 
		FROM faucet_txs
		WHERE faucet_txs.network_id=:network_id 
		LIMIT 1";
		$q=$GLOBALS['dbh']->prepare($sql);
		$q->bindParam(':network_id',$GLOBALS["network_id"], PDO::PARAM_INT);
		$q->execute();
		if ($q->rowCount()>0)
		{
			$row=$q->fetch(PDO::FETCH_ASSOC);
			$data=json_decode($row["data"]);
			$dt = new DateTime($row["last_updated"]);
			?>
			<h6 class="text-white dark:text-white">Last updated : <?=$dt->format('d-m-Y H:i:s')?></h6>
			<div class="mt-5">
				<span class="text-white dark:text-white">Filter by category : </span>
				<a href="./faucet/?type=send" class="text-white bg-gray-800 hover:bg-gray-900 focus:outline-none focus:ring-4 focus:ring-gray-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-gray-800 dark:hover:bg-gray-700 dark:focus:ring-gray-700 dark:border-gray-700">Send</a>
				<a href="./faucet/?type=receive" class="text-white bg-gray-800 hover:bg-gray-900 focus:outline-none focus:ring-4 focus:ring-gray-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-gray-800 dark:hover:bg-gray-700 dark:focus:ring-gray-700 dark:border-gray-700">Receive</a>
				<a href="./faucet/?type=generate" class="text-white bg-gray-800 hover:bg-gray-900 focus:outline-none focus:ring-4 focus:ring-gray-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-gray-800 dark:hover:bg-gray-700 dark:focus:ring-gray-700 dark:border-gray-700">Generate</a>
				<a href="./faucet/?type=immature" class="text-white bg-gray-800 hover:bg-gray-900 focus:outline-none focus:ring-4 focus:ring-gray-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-gray-800 dark:hover:bg-gray-700 dark:focus:ring-gray-700 dark:border-gray-700">Immature</a>
				<a href="./faucet/" class="text-white bg-gray-800 hover:bg-gray-900 focus:outline-none focus:ring-4 focus:ring-gray-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-gray-800 dark:hover:bg-gray-700 dark:focus:ring-gray-700 dark:border-gray-700">All</a>
			</div>
			<div class="relative overflow-x-auto mt-5">
				<table class="w-full text-sm text-left rtl:text-right text-zinc-500 dark:text-zinc-400">
					<thead class="text-xs text-gray-700 uppercase bg-gray-200 dark:bg-zinc-900 dark:text-white">
						<tr>
							<th scope="col" class="px-6 py-3 text-gray-900 dark:text-white">
								Height
							</th>
							<th scope="col" class="px-6 py-3 text-gray-900 dark:text-white">
								Category
							</th>
							<th scope="col" class="px-6 py-3 text-gray-900 dark:text-white">
								Date
							</th>
							<th scope="col" class="px-6 py-3 text-gray-900 dark:text-white">
								Amount
							</th>
						</tr>
					</thead>
					<tbody>
						<?
						foreach ($data as $k=>$v)
						{
							if (empty($_GET["type"])||(!empty($_GET["type"])&&$v->category==$_GET["type"]))
							{
								//var_dump($v);
								?>
								<tr class="bg-white text-gray-900 border-b dark:bg-zinc-800 dark:border-zinc-900 dark:text-white">
									<td class="px-6 py-4 text-gray-900 dark:text-white">
										<?=$v->blockheight?>
									</td>
									<td class="px-6 py-4 text-gray-900 dark:text-white">
										<?=$v->category?>
									</td>
									<td class="px-6 py-4 text-gray-900 dark:text-white">
										<?
										$epoch = $v->time;
										$dt = new DateTime("@$epoch");
										echo $dt->format('d-m-Y H:i:s');
										?>
									</td>
									<td class="px-6 py-4 text-gray-900 dark:text-white">
										<?=$v->amount?>
									</td>
								</tr>
								<?
							}
						}
						?>
					</tbody>
				</table>
			</div>
			<?
		}
	}
	catch (PDOException $e)
	{
		echo $e->getMessage();
	}
	?>
</div>