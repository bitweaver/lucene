{* $Header$ *}

{if $gBitSystem->isPackageActive( 'lucene' )}
	{bitmodule title="$moduleTitle" name="search_new"}
		{include file="bitpackage:lucene/global_mini_search.tpl"}
	{/bitmodule}
{/if}
