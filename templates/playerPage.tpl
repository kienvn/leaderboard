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
            <strong>Лекция номер {$k}</strong>
            <br />
            <div id="lectureDiv">
                    {foreach $v as $hist}
                        {if $hist->type eq 'question'}
                        <img src="images/question_btn50p.png" />
                        {elseif $hist->type eq 'answer'}
                        <img src="images/answer_btn50p.png" />
                        {elseif $hist->type eq 'homework'}
                        <img src="images/homework_btn50p.png" />
                        {/if}
                    {/foreach}
                <br />
                {/strip}
            </div>
            {/foreach}
        </div>
    </body>
</html>