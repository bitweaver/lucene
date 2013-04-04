{strip}
{form method="post" ipackage="lucene" ifile="index.php"}
    <div>
        <input id="fuser" name="search_phrase" size="20" type="text" accesskey="s" value="{tr}search{/tr}" onfocus="this.value=''" />
	{if $searchIndex}
        <input name="search_index" type="hidden" value="{$searchIndex}" />
	{else}
        <br />
        {html_options options=$searchIndexes name="search_index" selected=$smarty.session.search_index  selected=$perms[user].level}
	{/if}
        <input type="submit" class="btn" name="search" value="{tr}go{/tr}"/>
    </div>
{/form}
{/strip}
