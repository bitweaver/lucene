{strip}
{if $gBitUser->hasPermission( 'bit_p_lucene_search' )}
	<ul>
		<li><a class="item" href="{$smarty.const.LUCENE_PKG_URL}">{tr}Search{/tr}</a></li>
	</ul>
{/if}
{/strip}
