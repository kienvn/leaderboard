<?php
header("Content-Type: text/html; charset=utf-8");
require_once("class_loader.php");
require_once("config/database_config.php");

$game = new Game($database);
$students = array();

if (isset($_GET["lecture"]) && !empty($_GET["lecture"])) {

    $getLecture = $_GET["lecture"];
    $students = $game->leaderboard("lecture", $getLecture);
    //var_dump($students);
} else {
    $students = $game->leaderboard();
}
?>
<htmL>
    <head>
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
