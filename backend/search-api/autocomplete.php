<?php

include '../config/search-db.php';

if (isset($_POST['s'])) {

    $key = "%{$_POST['s']}%";

    $sql = "SELECT * FROM services
	        WHERE title 
	        LIKE ? OR link Like ?
	        LIMIT 4";

    $stmt = $conn->prepare($sql);
    $stmt->execute([$key, $key]);

    if ($stmt->rowCount() > 0) {
        $results = $stmt->fetchAll();
        foreach ($results as $result) { ?>
            <li>
                <a target="_blank" href="<?= $result['link'] ?>"><?= $result['title'] ?></a>
            </li>
            <?php
        }
    } else {
        echo "<li style='color: red; font-weight: 700;'>Service Not Found</li>";
    }

}