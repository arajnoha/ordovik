<?php

    session_start();
    if (!isset($_SESSION["in"])) {
        header("Location: ./");
        exit;
    }

    $package = file_get_contents('php://input');

    $id = $_POST["id"];
    $taskid = $_POST["taskid"];
    $name = $_POST["name"];
    $done = $_POST["done"];

    if ($done == 0) {
        $done = 1;
    } else {
        $done = 0;
    }

    $file = json_decode(file_get_contents("vault/cal.json"), true);

            foreach ($file as $key => $item) {
                if ($item["id"] == $id) {

                    foreach ($file[$key]["tasks"] as $subkey => $subitem) {
                        if ($subitem["taskid"] == $taskid) {
                            $file[$key]["tasks"][$subkey]["done"] = $done;
                        }
                    }
                }
            }
            
    if (file_put_contents("vault/cal.json", json_encode($file)) ) {
        echo $done;
    }

?>