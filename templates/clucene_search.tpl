{strip}
<div class="display clucene">
	<div class="header">
		<h1>{tr}Search{/tr}</h1>
	</div>

	<div class="body">

{if $searchResults}
<div>Number of results: {$searchHits}</div>
<ul>
{section loop=$searchHits name=ix}
	<li> path: {$searchResults->get($smarty.section.ix.index,'path')}<br />
		 id: {$searchResults->id($smarty.section.ix.index)}<br/>
		 score: {$searchResults->score($smarty.section.ix.index)*100|round:1}<br/>
	</li>
{/section}
</ul>
</div>
{/if}

	</div><!-- end .body -->
</div><!-- end .liberty -->
{/strip}
