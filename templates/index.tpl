<!DOCTYPE HTML>
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
               {foreach $students as $student}
                {strip}
                <tr>
                    <td>{$student->name}</td>
                    <td>{$student->score}</td>
                </tr>
                {/strip}
               {/foreach}
                <tr>
                    <td class="score"><strong>Total Score:</strong></td>
                    <td class="score">{$totalScore}</td>
                </tr>

            </tbody>
        </table>
    </body>
</html>
