{if $gBitSystem->isPackageActive( 'lucene' ) and $gBitSystem->isFeatureActive( 'site_header_extended_nav' )}
	<link rel="search" title="{tr}Search{/tr}" href="{$smarty.const.LUCENE_PKG_URL}" />
{/if}
