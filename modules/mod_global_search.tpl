{* $Header: /cvsroot/bitweaver/_bit_lucene/modules/mod_global_search.tpl,v 1.1 2006/11/22 05:53:32 spiderr Exp $ *}

{if $gBitSystem->isPackageActive( 'lucene' )}
	{bitmodule title="$moduleTitle" name="search_new"}
		{include file="bitpackage:lucene/global_mini_search.tpl"}
	{/bitmodule}
{/if}
