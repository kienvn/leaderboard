<strong>{$playerName}</strong>
<br />

{foreach $history as $k => $v}
    {strip}
        {$k}:
        <br />
        {foreach $v as $hist}
            {$hist->type} : {$hist->points}
            <br />
        {/foreach}
        <br />
    {/strip}
{/foreach}