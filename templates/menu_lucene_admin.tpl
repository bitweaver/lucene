{strip}
{if $packageMenuTitle}<a href="#"> {tr}{$packageMenuTitle|capitalize}{/tr}</a>{/if}
<ul class="{$packageMenuClass}">
	<li><a class="item" href="{$smarty.const.KERNEL_PKG_URL}admin/index.php?page=lucene">{tr}Lucene{/tr}</a></li>
	<li><a class="item" href="{$smarty.const.LUCENE_PKG_URL}admin/index.php">{tr}Lucene Indices{/tr}</a></li>
</ul>
{/strip}
