{strip}
<div class="display clucene">
	<div class="header">
		<h1>{tr}Search{/tr}</h1>
	</div>

	<div class="body">

	{formfeedback hash=$feedback}

	{form}
		<input type="text" name="search_phrase" value="{$smarty.request.search_phrase}" />
		<input type="submit" name="{tr}Search{/tr}" />
	{/form}

{if $searchResults}
<div>Number of results: {$searchHits}</div>

<div style="float:right">{tr}Score{/tr}</div>
<ul class="data">
{section loop=$searchHits name=ix}
	<li class="item"><div style="float:right"> {$searchResults->score($smarty.section.ix.index)*100|round:1}</div>
		<a href="{$smarty.const.BIT_ROOT_URL}index.php?content_id={$searchResults->get($smarty.section.ix.index,'content_id')}">{$searchResults->get($smarty.section.ix.index,'title')}</a><br/>
		 {$searchResults->get($smarty.section.ix.index,'content_type_guid')}<br />
	</li>
{/section}
</ul>
</div>
{/if}

	</div><!-- end .body -->
</div><!-- end .liberty -->
{/strip}
