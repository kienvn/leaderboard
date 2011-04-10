<?php
header("Content-Type: text/html; charset=utf-8");
require_once("class_loader.php");
require_once("config/database_config.php");

$game = new Game($database);
$students = array();

if (isset($_GET["lecture"]) && !empty($_GET["lecture"])) {

    $getLecture = $_GET["lecture"];
    $students = $game->getStudentsByLecture($getLecture);
    //var_dump($students);
} else {
    $students = $game->getStudents();
}
// sort for assoc array
usort($students, array("Student", "compare"));
?>
<htmL>
    <head>
        <title>PHP 11 Course Leaderboard</title>
        <link rel="stylesheet" href="styles/main.css" type="text/css" media="screen" />
        <script src="javascript/yepnope/yepnope.js" type="text/javascript" language="javascript" charset="utf-8"></script>
        <script src="javascript/namespace.js" type="text/javascript" language="javascript" charset="utf-8"></script>
        <script src="javascript/jquery-1.5.1.min.js" type="text/javascript" language="javascript" charset="utf-8"></script>

        <script type="text/javascript" language="javascript">
                        // TODO - BARZANA ZA MONITORITE
                        // DOMASHNO PO DVOIKI, POCHVA OT 3.5, STEP 0.1 --
                        // DA UPDATE-NA LEADERBOARD-A
                        // GRUPIRANE PO SPECIALNOST
                        // interesni vyprosi sa su6to i tezi za interview
			// http://code.jquery.com/jquery-1.5.2.js			
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
