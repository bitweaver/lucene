{* $Header: /cvsroot/bitweaver/_bit_lucene/modules/mod_package_search.tpl,v 1.1 2006/11/22 05:53:32 spiderr Exp $ *}
{if $gBitSystem->isPackageActive( 'lucene' )}
	{bitmodule title="$moduleTitle" name="pkg_search_box"}
		{include file=$miniSearchRsrc}
	{/bitmodule}
{/if}
