{* $Header$ *}
{if $gBitSystem->isPackageActive( 'lucene' )}
	{bitmodule title="$moduleTitle" name="pkg_search_box"}
		{include file=$miniSearchRsrc}
	{/bitmodule}
{/if}
