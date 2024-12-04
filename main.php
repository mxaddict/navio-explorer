<?=title("Home")?>
<?php
$initial_amount = 75000000;
$block_reward = 4;
$sql = "select data from data where `key` = 'blockchaininfo' limit 1";
$q = $GLOBALS['dbh']->prepare($sql);
$q->execute();
if ($q->rowCount() > 0) {
    $row = $q->fetch(PDO::FETCH_ASSOC);
    $data = json_decode($row["data"], true);
    $headers = $data[0]["headers"];
    $average_block_spacing = $data[0]["averageblockspacing"];
} else {
    $headers = 0;
    $average_block_spacing = 0;
}
$sql = "select data from data where `key` = 'peerinfo' limit 1";
$q = $GLOBALS['dbh']->prepare($sql);
$q->execute();
if ($q->rowCount() > 0) {
    $row = $q->fetch(PDO::FETCH_ASSOC);
    $data = json_decode($row["data"], true);
    $active_peer_count = isset($data[0]) && is_array($data[0]) ? count($data[0]) : 0;
}

try {
    $sql = "select max(block_id) as latest_block from blocks limit 1";
    $q = $GLOBALS['dbh']->prepare($sql);
    $q->execute();
    if ($q->rowCount() > 0) {
        $row = $q->fetch(PDO::FETCH_ASSOC);
        $latest_block = $row["latest_block"];
        $total_reward = ($latest_block * $block_reward);
        $nav_in_circulation = ($initial_amount + $total_reward);
    } else {
        $latest_block = 0;
    }
    $sql = "select
		blocks.block_id,
		blocks.hash,
		blocks.data as blockdata,
		txs.data as txdata
		from blocks
		left join txs on txs.block_hash=blocks.hash and txs.txno=1
        order by blocks.id desc
        limit 10";
    $q = $GLOBALS['dbh']->prepare($sql);
    $q->execute();
    ?>
    <div class="p-4">
        <?php if ($q->rowCount() > 0) { ?>
            <?php $sync_percent = round(($latest_block * 100) / $headers, 2); ?>
			<div class="flex w-full flex-col md:flex-row">
                <div class="flex-1 p-4 mt-4 mb-4 mr-4 bg-white border border-zinc-200 rounded-xl shadow sm:p-8 dark:bg-zinc-800 dark:border-zinc-900 mb-10 bg-gradient-to-br from-blue-400 via-blue-600 to-blue-900">
                    <h5 class="flex mb-4 text-xl font-medium text-gray-500 dark:text-white">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6 mr-2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 6.375c0 2.278-3.694 4.125-8.25 4.125S3.75 8.653 3.75 6.375m16.5 0c0-2.278-3.694-4.125-8.25-4.125S3.75 4.097 3.75 6.375m16.5 0v11.25c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125V6.375m16.5 0v3.75m-16.5-3.75v3.75m16.5 0v3.75C20.25 16.153 16.556 18 12 18s-8.25-1.847-8.25-4.125v-3.75m16.5 0c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125" />
                        </svg>
                        Nav in Circulation
                    </h5>
                    <div class="flex items-baseline text-gray-900 dark:text-white">
                        <span class="textxl font-semibold"></span>
                        <span class="text-2xl font-extrabold tracking-tight"><?=number_format($nav_in_circulation, 0, ',', '.')?></span>
                        <span class="ms-1 text-xl font-normal text-gray-500 dark:text-gray-300">NAV</span>
                    </div>
                </div>
                <div class="flex-1 p-4 mt-4 mb-4 mr-4 bg-white border border-zinc-200 rounded-xl shadow sm:p-8 dark:bg-zinc-800 dark:border-zinc-900 mb-10 bg-gradient-to-br from-violet-400 via-violet-600 to-violet-900">
                    <a href="peers/">
                        <h5 class="flex mb-4 text-xl font-medium text-gray-500 dark:text-white">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6 mr-2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m21 7.5-9-5.25L3 7.5m18 0-9 5.25m9-5.25v9l-9 5.25M3 7.5l9 5.25M3 7.5v9l9 5.25m0-9v9" />
                            </svg>
                            Active Peers
                        </h5>
                        <div class="flex items-baseline text-gray-900 dark:text-white">
                            <span class="textxl font-semibold"></span>
                            <span class="text-2xl font-extrabold tracking-tight"><?=$active_peer_count?></span>
                            <span class="ms-1 text-xl font-normal text-gray-500 dark:text-gray-300">Peer(s)</span>
                        </div>
                    </a>
                </div>
                <div class="flex-1 p-4 mt-4 mb-4 mr-4 bg-white border border-zinc-200 rounded-xl shadow sm:p-8 dark:bg-zinc-800 dark:border-zinc-900 mb-10 bg-gradient-to-br from-teal-400 via-teal-600 to-teal-900">
                    <h5 class="flex mb-4 text-xl font-medium text-gray-500 dark:text-white">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6 mr-2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 4.5h14.25M3 9h9.75M3 13.5h5.25m5.25-.75L17.25 9m0 0L21 12.75M17.25 9v12" />
                        </svg>
                        Height / Headers
                    </h5>
                    <div class="flex items-baseline text-gray-900 dark:text-white">
                        <span class="textxl font-semibold"></span>
                        <span class="text-2xl font-extrabold tracking-tight"><?=$latest_block?> / <?=$headers?></span>
                        <span class="ms-1 text-xl font-normal text-gray-500 dark:text-gray-300"><?=$sync_percent?>%</span>
                    </div>
                </div>
                <div class="flex-1 p-4 mt-4 mb-4 mr-0 bg-white border border-zinc-200 rounded-xl shadow sm:p-8 dark:bg-zinc-800 dark:border-zinc-900 mb-10 bg-gradient-to-br from-yellow-400 via-yellow-600 to-yellow-900">
                    <h5 class="flex mb-4 text-xl font-medium text-gray-500 dark:text-white">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6 mr-2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 12h16.5m-16.5 3.75h16.5M3.75 19.5h16.5M5.625 4.5h12.75a1.875 1.875 0 0 1 0 3.75H5.625a1.875 1.875 0 0 1 0-3.75Z" />
                        </svg>
                        Average Block Spacing
                    </h5>
                    <div class="flex items-baseline text-gray-900 dark:text-white">
                        <span class="textxl font-semibold"></span>
                        <span class="text-2xl font-extrabold tracking-tight"><?=$average_block_spacing?></span>
                        <span class="ms-1 text-xl font-normal text-gray-500 dark:text-gray-300"> Seconds</span>
                    </div>
                </div>
            </div>
            <h6 class="text-lg font-bold text-white dark:text-white mb-5">Latest 10 Block</h6>
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
                            <th scope="col" class="px-6 py-3 text-gray-900 dark:text-white">
                                VIN
                            </th>
                            <th scope="col" class="px-6 py-3 text-gray-900 dark:text-white">
                                VOUT
                            </th>
                            <th scope="col" class="px-6 py-3 text-gray-900 dark:text-white">
                                Fee
                            </th>
                        </tr>
                    </thead>
                    <tbody>
			<?php while ($row = $q->fetch(PDO::FETCH_ASSOC)) { ?>
                <?php
                        $blockdata = json_decode($row["blockdata"]);
			    $txdata = json_decode($row["txdata"]);
			    $fee = "";
			    if (isset($txdata->vout)) {
			        foreach ($txdata->vout as $k => $v) {
			            if ($v->scriptPubKey->asm == "OP_RETURN") {
			                $fee = $v->value;
			            }
			        }
			    }
			    ?>
				<tr class="bg-white text-gray-900 border-b dark:bg-zinc-800 dark:border-zinc-900 dark:text-white">
					<th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
						<a class='text-blue-600 dark:text-blue-400' href="block/<?=$row["hash"]?>"><?=$row["block_id"]?></a>
					</th>
					<td class="px-6 py-4">
						<a class='text-blue-600 dark:text-blue-400' href="block/<?=$row["hash"]?>"><?=$row["hash"]?></a>
					</td>
					<td class="px-6 py-4 text-gray-900 dark:text-white">
						<?php
			                $epoch = $blockdata->time;
			    $dt = new DateTime("@$epoch");
			    echo $dt->format('Y-m-d H:i:s');
			    ?>
					</td>
					<td class="px-6 py-4 text-gray-900 dark:text-white">
						<?=($blockdata->nTx == 1 ? "Empty" : "Not Empty")?>
					</td>
					<td class="px-6 py-4 text-gray-900 dark:text-white">
						<?=$blockdata->size?>
					</td>
					<td class="px-6 py-4 text-gray-900 dark:text-white">
						<?= isset($txdata) ? count($txdata->vin) : 0; ?>
					</td>
					<td class="px-6 py-4 text-gray-900 dark:text-white">
						<?= isset($txdata) ? count($txdata->vout) : 0; ?>
					</td>
					<td class="px-6 py-4 text-gray-900 dark:text-white">
						<?=$fee;?>
					</td>
				</tr>
            <?php } ?>
		</tbody>
	</table>
</div>
<?php } else { ?>
	<p class="text-gray-900 dark:text-white">No blocks found.</p>
<?php } ?>
<?php
} catch (PDOException $e) {
    echo $e->getMessage();
}
?>
</div>
