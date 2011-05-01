<!DOCTYPE HTML>
<html>
    <head>
        <title>Статистика за {$playerName}</title>
        <link href="images/favicon.ico" rel="shortcut icon" type="image/x-icon" />
        <link rel="stylesheet" href="styles/main.css" type="text/css" media="screen" />
        <link rel="stylesheet" href="styles/player.css" type="text/css" media="screen" />
    </head>
    <body>
        <div id="main">
            <strong>{$playerName}</strong>
            <br />
            {foreach $history as $k => $v}
                {strip}
                Лекция номер {$k}:
                <br />
                <div id="lectureDiv">
                    {foreach $v as $hist}
                        {$hist->type} : {$hist->points}
                <br />
                    {/foreach}
                <br />
                {/strip}
            </div>
            {/foreach}
        </div>
    </body>
</html>