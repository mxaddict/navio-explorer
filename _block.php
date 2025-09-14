<?php echo title("Block Details"); ?>
<div class="p-4 w-full">
    <h4 class="text-lg font-bold text-white dark:text-white">Block Details</h4>
    <?php
    try {
        if (!is_numeric($_GET["hash"])) {
            $sql = "select * from blks where hash = :hash limit 1";
            $q = $GLOBALS['dbh']->prepare($sql);
            $q->bindParam(':hash', $_GET["hash"], PDO::PARAM_STR);
        } else {
            $sql = "select * from blks where height=:height limit 1";
            $q = $GLOBALS['dbh']->prepare($sql);
            $q->bindParam(':height', $_GET["hash"], PDO::PARAM_STR);
        }
        $q->execute();
        if ($q->rowCount() > 0) {
            $row = $q->fetch(PDO::FETCH_ASSOC);
            $data = json_decode($row["data"], true);
            ?>
            <h5 class="text-white dark:text-white">Block Hash : <?php echo $row["hash"]?></h5>
            <h6 class="text-white dark:text-white">Block Height : <?php echo $row["height"]?></h6>
            <div class="relative overflow-x-auto mt-5">
                <table class="w-full text-sm text-left rtl:text-right text-zinc-500 dark:text-zinc-400">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-200 dark:bg-zinc-900 dark:text-white">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-gray-900 dark:text-white">
                                Key
                            </th>
                            <th scope="col" class="px-6 py-3 text-gray-900 dark:text-white">
                                Value
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($data as $k => $v) { ?>
                            <tr class="bg-white text-gray-900 border-b dark:bg-zinc-800 dark:border-zinc-900 dark:text-white">
                                <td class="px-6 py-4 text-gray-900 dark:text-white">
                                    <?php echo $k?>
                                </td>
                                <td class="px-6 py-4 text-gray-900 dark:text-white">
                                    <?php
                                    if (is_array($v)) {
                                        foreach ($v as $k2 => $v2) {
                                            if ($k == "tx") {
                                                echo "<a class='text-blue-600 dark:text-blue-400' href='tx/" . $v2 . "'>" . $v2 . "</a><br/>";
                                            } else {
                                                echo $v2 . "<br/>";
                                            }
                                        }
                                    } else {
                                        echo $v;
                                    }
                                    ?>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
            <?php
        }
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
    ?>
</div>
