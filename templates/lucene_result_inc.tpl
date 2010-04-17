	<li class="item {cycle values="odd,even"}">
		{assign var=contentId value=$gLucene->getResult($resultNum,'content_id')}
		<h3><a href="{$gLucene->getResult($resultNum,'url',"`$smarty.const.BIT_ROOT_URL`index.php?content_id=`$contentId`")}&amp;highlight={$smarty.request.search_phrase|escape:"url"}">{$gLucene->getResult($resultNum,'title')}</a>
		</h3>
			{$gLucene->getResult($resultNum,'data')|strip_tags|escape|truncate:1000}<br />
			{assign var=contentTypeGuid value=$gLucene->getResult($resultNum,'content_type_guid')}
			<small>{$gLibertySystem->getContentTypeName($contentTypeGuid)} &nbsp;&bull;&nbsp; {tr}Relevance{/tr}: {$gLucene->getResult($resultNum,'score')*100|round:0}</small>
		</p>
	</li>
