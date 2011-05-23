<!DOCTYPE HTML>
<htmL>
    <head>
        <link href="{$imagesFolder}/favicon.ico" rel="shortcut icon" type="image/x-icon" />
        <link href="{$cssFolder}/feedback.css" rel="stylesheet" type="text/css" media="screen" />
    </head>
    <body>
        <form method="post" action="feedback.php">
            <fieldset>
                <legend>Мнение</legend>
                <label for="nameInput">Твоето име ? :)</label>
                <br />
                <input type="text" name="nameInput" />
                <br />
                <label for="opinionInput">Твоето мнение ? :) (около 500 символа)</label>
                <br />
                <textarea name="opinionInput"></textarea>
            </fieldset>
            <fieldset>
                <legend>Няколко тикчета</legend>
                Харесва ми идеята с Leaderboard-a :
                <input type="checkbox" value="leaderboard" />
                <br />
                Научих интересни и полезни неща :
                <input type="checkbox" value="learned" />
            </fieldset>
            <fieldset>
                <legend>Верификация</legend>
                Изберете преподавателя ;)
                <br />
                {foreach from=$captchaImages key=value item=image name=foo}
                {strip}
                <input type="radio" name="captcha[]" value="{$value}" />
                <img src="{$image}" />
                {if ($smarty.foreach.foo.index + 1) % 2 == 0}
                <br />
                {/if}
                {/strip}
                {/foreach}
            </fieldset>
            <hr />
            <input type="submit" value="Давам своята обратна връзка" name="feedbackForm" />
        </form>

        {include file="footer.tpl"}
    </body>
</htmL>