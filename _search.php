<?php
include "init.php";
try {
    if (is_numeric($_GET["q"])) {
        $sql = "select * from blocks where block_id=:block_id limit 1";
        $q = $GLOBALS['dbh']->prepare($sql);
        $q->bindParam(':block_id', $_GET["q"], PDO::PARAM_STR);
    } else {
        $sql = "select * from blocks where hash=:hash limit 1";
        $q = $GLOBALS['dbh']->prepare($sql);
        $q->bindParam(':hash', $_GET["q"], PDO::PARAM_STR);
    }
    $q->execute();
    if ($q->rowCount() > 0) {
        $row = $q->fetch(PDO::FETCH_ASSOC);
        $data = json_decode($row["data"], true);
        header("location:/block/".$row["hash"]);
        exit();
    } else {
        include "header.php";
        ?>
		<?=title("Search")?>
		<div class="p-4 w-full">
			<h4 class="text-lg font-bold text-white dark:text-white">Search</h4>
			<p class="text-white dark:text-white">No block matching your search criteria was found.</p>
		</div>
		<?php
        include "footer.php";
    }
} catch (PDOException $e) {
    echo $e->getMessage();
}
