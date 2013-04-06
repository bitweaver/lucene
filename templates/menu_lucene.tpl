{strip}
{if $gBitUser->hasPermission( 'p_lucene_search' )}
	<a class="dropdown-toggle" data-toggle="dropdown" href="#"> {tr}{$packageMenuTitle}{/tr} <b class="caret"></b></a>
<ul class="{$packageMenuClass}">
		<li><a class="item" href="{$smarty.const.LUCENE_PKG_URL}">{tr}Search{/tr}</a></li>
	</ul>
{/if}
{/strip}
