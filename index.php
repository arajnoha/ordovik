<?php
session_start();
if (!isset($_SESSION["in"])) {
	header("Location: login.php");
    exit;
}
?>

<!doctype html>
<html lang="cs">
<head>
    <meta charset="utf-8">
    <title>Týdeník</title>
    <link rel="stylesheet" type="text/css" href="neon.css?v=4">
    <meta name="viewport" content="width=device-width, height=device-height, initial-scale=1, minimum-scale=1, maximum-scale=1, user-scalable=no" />
    <meta name="theme-color" content="#ffffff">
    <link rel="icon" href="assets/favicon.png" type="image/png" size="512x512">
</head>
<body>

<?php

if (isset($_POST['submit'])) {


        $writen = false;

        if (file_exists("vault/cal.json")) {

            $file = json_decode(file_get_contents("vault/cal.json"), true);

            foreach ($file as $key => $item) {
                if ($item["id"] == $_POST["id"]) {

                    $construct = array(
                            "taskid"=>time(),
                            "name"=>$_POST["task"],
                            "done"=> 0,
                            "color"=> 0,
                            "repetition"=>"none"
                    );

                    $file[$key]["tasks"][] = $construct;
                    $writen = true;
                }
            }    

            if ($writen == false) {
                $construct = array(
                    "id"=> $_POST["id"],
                    "tasks"=> array(
                        array(
                            "taskid"=>time(),
                            "name"=>$_POST["task"],
                            "done"=> 0,
                            "color"=> 0,
                            "repetition"=>"none"
                        )
                    )
                );

                array_push($file, $construct);
            }


        } else {
            $construct = array(
                "id"=> $_POST["id"],
                "tasks"=> array(
                    array(
                        "taskid"=>time(),
                        "name"=>$_POST["task"],
                        "done"=> 0,
                        "color"=> 0,
                        "repetition"=>"none"
                    )
                )
                
            );
            $file[] = $construct;

        }


        file_put_contents("vault/cal.json", json_encode($file));
}

function string_mesic($mesic) {
    static $nazvy = array(1 => 'leden', 'únor', 'březen', 'duben', 'květen', 'červen', 'červenec', 'srpen', 'září', 'říjen', 'listopad', 'prosinec');
    return $nazvy[$mesic];
}

function string_den($den) {
    static $nazvy = array('neděle', 'pondělí', 'úterý', 'středa', 'čtvrtek', 'pátek', 'sobota');
    return $nazvy[$den];
}

$dt = new DateTime;
$real = new DateTime;
if (isset($_GET['year']) && isset($_GET['week'])) {
    $dt->setISODate($_GET['year'], $_GET['week']);
} else {
    $dt->setISODate($dt->format('o'), $dt->format('W'));
}
$year = $dt->format('o');
$week = $dt->format('W');

$data = array();
if (file_exists("vault/cal.json")) {
    $data = json_decode(file_get_contents("vault/cal.json"),true);
}
?>
<header>
<?php
echo "<h2>".string_mesic($dt->format('n'))." ".$dt->format('Y')."</h2>";
?>
<div class="navigation">
<a class='round-button' title="Posunout o týden zpět" href="<?php echo $_SERVER['PHP_SELF'].'?week='.($week-1).'&year='.$year; ?>"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 10 22"><path d="M7.059 7.667a.667.667 0 000-.933.648.648 0 00-.922 0l-3.64 3.716a.663.663 0 000 .931l3.64 3.717a.648.648 0 00.922 0 .667.667 0 000-.932l-2.987-3.25L7.06 7.667z"/></svg></a>
<a class='round-button small-hack' title="Zobrazit tento týden" href="<?php echo $_SERVER['PHP_SELF'];?>"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path color="#000000" d="M12,22A2,2 0 0,1 10,20A2,2 0 0,1 12,18A2,2 0 0,1 14,20A2,2 0 0,1 12,22M13,2V13L17.5,8.5L18.92,9.92L12,16.84L5.08,9.92L6.5,8.5L11,13V2H13Z" /></svg></a>
<a class='round-button' title="Posunout o týden vpřed" href="<?php echo $_SERVER['PHP_SELF'].'?week='.($week+1).'&year='.$year; ?>"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 10 22"><path d="M2.564 14.173a.667.667 0 000 .933.648.648 0 00.922 0l3.64-3.716a.663.663 0 000-.931l-3.64-3.717a.648.648 0 00-.922 0 .667.667 0 000 .932l2.986 3.25-2.986 3.249z"/></svg></a>
<a class='round-button ghost pick-date' title="Vybrat přesné datum"><svg viewBox="0 0 128 128" xmlns="http://www.w3.org/2000/svg"><path fill="#666" d="M121,10.3H107.2V3a3,3,0,0,0-6,0v7.3H80.4V3a3,3,0,0,0-6,0v7.3H53.6V3a3,3,0,0,0-6,0v7.3H26.8V3a3,3,0,0,0-6,0v7.3H7a7,7,0,0,0-7,7V121a7,7,0,0,0,7,7H95a3,3,0,0,0,.59-.06l.26-.08a1.76,1.76,0,0,0,.59-.25l.23-.12a3,3,0,0,0,.46-.38l30-30a3,3,0,0,0,.38-.46c0-.07.07-.14.11-.21a1.78,1.78,0,0,0,.25-.6c0-.09.06-.17.08-.27A3,3,0,0,0,128,95h0V17.3A7,7,0,0,0,121,10.3ZM98,98h19.76L98,117.76ZM7,16.3H20.8v7.3a3,3,0,0,0,6,0V16.3H47.6v7.3a3,3,0,1,0,6,0V16.3H74.4v7.3a3,3,0,1,0,6,0V16.3h20.8v7.3a3,3,0,1,0,6,0V16.3H121a1,1,0,0,1,1,1V31.6H6V17.3A1,1,0,0,1,7,16.3ZM6,121V37.6H122V92H95a3,3,0,0,0-3,3v27H7A1,1,0,0,1,6,121Z"/></svg></a>
<a class='round-button ghost' title="Odhlásit se" href="logout.php"><svg width="20" height="20" xmlns="http://www.w3.org/2000/svg"><g fill="#666"><path d="M16 17.5H4v-10h12v10zm-11-1h10v-8H5v8z"/><path d="M14 8.5H6V6c0-1.9 1.6-3.5 3.5-3.5h1C12.4 2.5 14 4.1 14 6v2.5zm-7-1h6V6c0-1.4-1.1-2.5-2.5-2.5h-1C8.1 3.5 7 4.6 7 6v1.5zm3 6c-1.1 0-2-.9-2-2s.9-2 2-2 2 .9 2 2-.9 2-2 2zm0-3c-.6 0-1 .4-1 1s.4 1 1 1 1-.4 1-1-.4-1-1-1z"/><path d="M9.5 13h1v2.5h-1V13z"/></g></svg></a>
</div>

<?php
echo "</header>";
echo "<section class='tyden'>";

do {

    $today = "";
    if ($dt->format('d.m.') == $real->format('d.m.')) {
        $today = " today";
    }
    $id = $dt->format('Ymd');
    echo "<div class='den".$today."'>";
    echo "<div class='den-header'><h4>".$dt->format('d.m.')."</h4><span>".string_den($dt->format('w'))."</span></div>";
    echo "<div class='den-content'>";

    foreach ($data as $key => $val) {
        if ($val['id'] === $id) {
            $tasks = $data[$key]["tasks"];

            foreach ($tasks as $task) {
                echo "<div class='task' data-taskid='".$task["taskid"]."' data-color='".$task["color"]."' data-state='".$task["done"]."' data-day='".$id."' data-repetition='".$task["repetition"]."'><span>".$task["name"]."</span><div class='buttons'><button class='delete'></button><button class='rename'></button><button class='repeat'></button></div></div>";
            }
        }
    }
    

    echo "<form action='' method='post'><input type='text' name='id' value='".$id."'><input type='text' name='task' placeholder='+ Přidat úkol'><input type='submit' name='submit'></form></div>";
    echo "</div>";
    $dt->modify('+1 day');

} while ($week == $dt->format('W'));

echo "</section>";

?>
<div class="modal hide" id="modal-repeat">
    <h4>Jak často se má úkol opakovat?</h4>
    <span id="modal-repeat-name"></span>
    <div id="repeat-form">
        <label><input type="radio" name="repeat-radio" id="none" value="none"><span>Neopakovat</span></label>
        <label><input type="radio" name="repeat-radio" id="day" value="day"><span>Denně</span></label>
        <label><input type="radio" name="repeat-radio" id="week" value="week"><span>Týdně</span></label>
        <label><input type="radio" name="repeat-radio" id="fortnight" value="fortnight"><span>Každých 14 dní</span></label>
        <label><input type="radio" name="repeat-radio" id="month" value="month"><span>Měsíčně</span></label>
        <label><input type="radio" name="repeat-radio" id="quarter" value="quarter"><span>Čtvrtletně</span></label>
        <label><input type="radio" name="repeat-radio" id="year" value="year"><span>Ročně</span></label>
        <div class="navigation">
            <button class="text-link" id="repeat-form-send">Uložit</button>
            <button class="text-link ghost" id="repeat-form-close">Zavřít</button>
        </div>
    </div>
</div>
<button class="ctrl-toggle-button" title="Spustit režim úprav úkolů" id="ctrl-toggle">CTRL režim</button>
<script src="do.js?v=2"></script>
</body>
</html>