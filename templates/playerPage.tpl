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
            <span id="playerName">{$playerName}</span> | <span id="totalScore">Общо точки : {$totalScore}</span>
            <br /><br />
            <span id="pointsHistory">История на точките :</span>
            <br />
            {foreach $history as $k => $v}
                {strip}
            Лекция #{$k}
            <br />
            <div id="lectureDiv">
                    {foreach $v as $hist}
                        {if $hist->type eq 'question'}
                <img title="Въпрос" alt="Въпрос {$hist->points}" src="images/question_btn50p.png" />
                        {elseif $hist->type eq 'answer'}
                <img title="Отговор" alt="Отговор {$hist->points}" src="images/answer_btn50p.png" />
                        {elseif $hist->type eq 'homework'}
                <img title="Домашно" alt="Домашно {$hist->points}" src="images/homework_btn50p.png" />
                        {/if}
                    {/foreach}
                <br />
                {/strip}
            </div>
            {/foreach}
            Общо:
            <div id="totalDiv">
                Общо Въпроси : {$totalQuestions}
                <br />
                Общо Отговори : {$totalAnswers}
                <br />
                Общо Домашни : {$totalHomeworks}
            </div>
        </div>
    </body>
</html>