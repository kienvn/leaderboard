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
        {foreach $lectures as $lec}
        {strip}
        <a href="?lecture={$lec}">Лекция {$lec}</a>
        {/strip}
        {/foreach}
        |
        <a href="?">Всички</a>

        <br />
        <strong>Виж точките за домашно :</strong>
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
                    <td><a href="player.php?pid={$student->id}">{$student->name}</a></td>
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
        {include file="footer.tpl"}
    </body>
</html>
