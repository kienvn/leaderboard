<!DOCTYPE HTML>
<html>
    <head>
        <title>Статистика за {$playerName}</title>
        <link rel="stylesheet" href="styles/main.css" type="text/css" media="screen" />
    </head>
    <body>
        <strong>{$playerName}</strong>
        <br />
        {foreach $history as $k => $v}
            {strip}
                Лекция {$k}:
                <br />
                {foreach $v as $hist}
                    {$hist->type} : {$hist->points}
                <br />
                {/foreach}
                <br />
            {/strip}
        {/foreach}
    </body>
</html>