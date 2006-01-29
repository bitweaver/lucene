{strip}
<div class="display clucene">
	<div class="header">
		<h1>{tr}Search{/tr}</h1>
	</div>

	<div class="body">

		{formfeedback hash=$feedback}

		{form legend="Search"}
			<input type="hidden" name="highlight" value="{$smarty.request.search_phrase}" />

			<div class="row">
				{formlabel label="Search term" for="search_phrase"}
				{forminput}
					<input type="text" name="search_phrase" id="search_phrase" value="{$smarty.request.search_phrase}" />
					{formhelp note=""}
				{/forminput}
			</div>

			<div class="row submit">
				<input type="submit" name="{tr}Search{/tr}" />
			</div>
		{/form}

		{if $searchResults}
			<p>Number of results: {$searchHits}</p>

			<ul class="data">
				{section loop=$searchHits name=ix}
					<li class="item {cycle values="odd,even"}">
						<a href="{$smarty.const.BIT_ROOT_URL}index.php?content_id={$searchResults->get($smarty.section.ix.index,'content_id')}&amp;highlight={$smarty.request.highlight|escape:"url"}">{$searchResults->get($smarty.section.ix.index,'title')}</a>
						<p>
							{$searchResults->get($smarty.section.ix.index,'data')|escape|truncate:500}
							<br />
							{assign var=contentTypeGuid value=$searchResults->get($smarty.section.ix.index,'content_type_guid')}
							<small>{$gLibertySystem->mContentTypes.$contentTypeGuid.content_description} &nbsp;&bull;&nbsp; {tr}Relevance{/tr}: {$searchResults->score($smarty.section.ix.index)*100|round:0}</small>
						</p>
					</li>
				{/section}
			</ul>
		{/if}
	</div><!-- end .body -->
</div><!-- end .liberty -->
{/strip}
