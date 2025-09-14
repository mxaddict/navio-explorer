<?php echo title("Peers")?>
<div class="p-4 w-full">
	<h4 class="text-lg font-bold text-white dark:text-white">Active Peer Details</h4>
	<?php
	require 'vendor/autoload.php';
	require_once 'vendor/autoload.php';
	use MaxMind\Db\Reader;
	$CityDatabaseFile = 'GeoLite2-City.mmdb';
	$ASNDatabaseFile = 'GeoLite2-ASN.mmdb';
	$CityReader = new Reader($CityDatabaseFile);
	$ASNReader = new Reader($ASNDatabaseFile);
	try
	{
        $commit_hash_cache_path = __DIR__ . "/cache/commit_hash.json";
        if (
            !file_exists($commit_hash_cache_path) ||
            filemtime($commit_hash_cache_path) + 500 < time()
        ) {
            $url="https://api.github.com/repos/nav-io/navio-core/commits?per_page=1";
            $ch=curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.2; WOW64; rv:17.0) Gecko/20100101 Firefox/17.0;");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $content = curl_exec($ch);
            curl_close($ch);
            file_put_contents($commit_hash_cache_path, $content);
        }
		$json=json_decode(file_get_contents($commit_hash_cache_path));
        $latest_commit_hash=is_array($json) ? $json[0]->sha : 'UNKNOWN';
        $latest_commit_hash_short=substr($latest_commit_hash,0,12);
        $latest_commit_date=is_array($json) ? $json[0]->commit->author->date : "1970-01-01T00:00:00Z";
		$d=DateTime::createFromFormat('Y-m-d\TH:i:s\Z', $latest_commit_date, new DateTimeZone('Europe/Istanbul'));
		$latest_commit_date=date_format($d, 'd-m-Y H:i:s');
		?>
		<div class="text-white dark:text-white">
			Latest commit hash : <?php echo $latest_commit_hash;?> (<?php echo $latest_commit_hash_short?>) (<?php echo $latest_commit_date?> UTC)
		</div>
		<?php
		$sql="select data from data where k = 'peerinfo' limit 1";
		$q=$GLOBALS['dbh']->prepare($sql);
		$q->execute();
		if ($q->rowCount()>0)
		{
			$row=$q->fetch(PDO::FETCH_ASSOC);
			$data=json_decode($row["data"],true);
			?>
			<div class="relative overflow-x-auto mt-5">
				<table class="w-full text-sm text-left rtl:text-right text-zinc-500 dark:text-zinc-400">
					<thead class="text-xs text-gray-700 uppercase bg-gray-200 dark:bg-zinc-900 dark:text-white">
						<tr>
							<th scope="col" class="px-6 py-3 text-gray-900 dark:text-white">
								Region
							</th>
							<th scope="col" class="px-6 py-3 text-gray-900 dark:text-white">
								Country / Provider
							</th>
							<th scope="col" class="px-6 py-3 text-gray-900 dark:text-white">
								Address
							</th>
							<th scope="col" class="px-6 py-3 text-gray-900 dark:text-white">
								Version
							</th>
							<th scope="col" class="px-6 py-3 text-gray-900 dark:text-white">
								Sub Version
							</th>
							<th scope="col" class="px-6 py-3 text-gray-900 dark:text-white">
								Starting Height
							</th>
							<th scope="col" class="px-6 py-3 text-gray-900 dark:text-white">
								Synced Blocks
							</th>
							<th scope="col" class="px-6 py-3 text-gray-900 dark:text-white">
								Synced Headers
							</th>
						</tr>
					</thead>
					<tbody>
						<?php
                        $peers = isset($data[0]) && is_array($data[0]) ? $data[0] : [];
						foreach ($peers as $k=>$v)
						{
							$ip=gethostbyname(explode(":",$v["addr"])[0]);
							$city_en="Unknown";
                            $city_code="Unknown";
                            $city_iso="Unknown";
							$asn="Unknown";
                            try {
                                $city=$CityReader->get($ip);
                                $city_en = $city["continent"]["names"]["en"];
                                $city_code = $city["continent"]["code"];
                                $city_iso = $city["country"]["iso_code"];
                                $asn=$ASNReader->get($ip)["autonomous_system_organization"];
                            } catch (Exception $e) {
                            }

							if (strpos($v["subver"],$latest_commit_hash_short ) !== false)
							{
								$class="text-green-500 dark:text-green-500";
							}
							else
							{
								$class="text-gray-900 dark:text-white";
							}
							?>
							<tr class="bg-white text-gray-900 border-b dark:bg-zinc-800 dark:border-zinc-900 dark:text-white">
								<td>
									<?php echo $city_en;?> -
									<?php echo $city_code;?>
								</td>
								<td>
									<?php echo $city_en?> -
									<?php echo $city_en?> (<?php echo $city_iso?>)
									<p><?php echo $asn?></p>
								</td>
								<td class="px-6 py-4 text-gray-900 dark:text-white">
									<?php echo $v["addr"]?>
								</td>
								<td class="px-6 py-4 text-gray-900 dark:text-white">
									<?php echo $v["version"]?>
								</td>
								<td class="px-6 py-4 <?php echo $class?>">
									<?php echo $v["subver"]?>
								</td>
								<td class="px-6 py-4 text-gray-900 dark:text-white">
									<?php echo $v["startingheight"]?>
								</td>
								<td class="px-6 py-4 text-gray-900 dark:text-white">
									<?php echo $v["synced_blocks"]?>
								</td>
								<td class="px-6 py-4 text-gray-900 dark:text-white">
									<?php echo $v["synced_headers"]?>
								</td>
							</tr>
							<?php
						}
						$CityReader->close();
						$ASNReader->close();
						?>
					</tbody>
				</table>
			</div>
			<?php
		}
	}
	catch (PDOException $e)
	{
		echo $e->getMessage();
	}
	?>
</div>
