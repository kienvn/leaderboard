<?php
header("Content-Type: text/html; charset=utf-8");
require_once("class_loader.php");
require_once("config/database_config.php");

$game = new Game($database);
$students = array();

if (isset($_GET["lecture"]) && !empty($_GET["lecture"])) {

    $getLecture = (int) $_GET["lecture"];
    $students = $game->leaderboard("lecture", $getLecture);
} else if (isset($_GET["homework"]) && !empty($_GET["homework"])) {
    $getHomework = (int) $_GET["homework"];
    $students = $game->leaderboard("homework", $getHomework);
} else {
    $students = $game->leaderboard();
}
?>
<htmL>
    <head>
        <link href="images/favicon.ico" rel="shortcut icon" type="image/x-icon" />
        <title>PHP 11 Course Leaderboard</title>
        <link rel="stylesheet" href="styles/main.css" type="text/css" media="screen" />
        <script src="javascript/namespace.js" type="text/javascript" language="javascript" charset="utf-8"></script>
        <script src="http://code.jquery.com/jquery-1.5.2.js" type="text/javascript" language="javascript" charset="utf-8"></script>

        <script type="text/javascript" language="javascript">
            $(document).ready(function() {
                console.log("asdasdads");
                $("tr:odd").addClass("odd");
                $("tr:even").addClass("even");
            });
        </script>


    </head>
    <body>
        <strong>Виж точките за лекция :</strong>
        <a href="?lecture=2">Лекция 2</a>
        <a href="?lecture=3">Лекция 3</a>
        <a href="?lecture=4">Лекция 4</a>
        <a href="?lecture=5">Лекция 5</a>
        |
        <a href="?">Всички</a>

        <br />
        <strong>Виж точките за домашно :</strong>
        <a href="?homework=3">Първо домашно</a>
        <a href="?homework=4">Второ домашно</a>
        <table>
            <thead>
                <tr>
                    <th>Name:</th>
                    <th>Points:</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sum = 0;
                foreach ($students as $student) {
                    echo "<tr>";
                    echo "<td>" . $student->name . "</td>";
                    echo "<td>" . $student->score . "</td>";
                    echo "</tr>";
                    $sum += $student->score;
                }
                ?>
                <tr>
                    <td class="score"><strong>Total Score:</strong></td>
                    <td class="score"><?php echo $sum; ?></td>
                </tr>
            </tbody>
        </table>
    </body>
</html>
