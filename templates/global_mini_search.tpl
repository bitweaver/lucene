{strip}
{form method="get" ipackage=search ifile="`$smarty.const.LUCENE_PKG_URL`index.php"}
    <div class="row">
        <input id="fuser" name="search_phrase" size="20" type="text" accesskey="s" value="{tr}search{/tr}" onfocus="this.value=''" />
        <br />
        {html_options options=$searchIndexes name="search_index" selected=$smarty.session.search_index  selected=$perms[user].level}
    </div>
    <div class="row submit">
        <input type="submit" name="search" value="{tr}go{/tr}"/>
    </div>
{/form}
{/strip}
