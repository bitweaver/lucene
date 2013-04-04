{strip}
{form legend="Lucene Settings"}
	<input type="hidden" name="page" value="{$page}" />
	<div class="control-group">
		{formlabel label="Lucene Engine" for="lucene_engine"}
		{forminput}
			<label><input name="lucene_engine" value="BitZendLucene" {if $gBitSystem->getConfig('lucene_engine')=='BitZendLucene'}checked="checked"{/if} type="radio"><strong>ZendLucene</strong></label>
			{formhelp note="This is the Lucene engine technology used to search the indices. For Zend Lucene, install the bitweaver <a href=\"http://www.bitweaver.org/wiki/ZendPackage\">ZendPackage</a> - no php extensions are needed. ZendLucene works well, but is very slow on large indices."}
			<label><input name="lucene_engine" value="BitJavaLucene" {if $gBitSystem->getConfig('lucene_engine')=='BitJavaLucene'}checked="checked"{/if} type="radio"><strong>php-java</strong></label>
			{formhelp note="For php-java-bridge, install the <a href=\"http://php-java-bridge.sourceforge.net/\">php-java-bridge</a> php extension. Quick and speedy, unfortunately, strange characters in return fields can cause the bridge to choke and results come back empty."}
			<label><input name="lucene_engine" value="BitCLucene" {if $gBitSystem->getConfig('lucene_engine')=='BitCLucene'}checked="checked"{/if} type="radio"><strong>php-clucene</strong></label>
			{formhelp note="For php-java-bridge, install the <a href=\"http://pecl.php.net/package/clucene\">php-clucene</a> php extension as well as the <a href=\"http://clucene.sourceforge.net/\">clucene system libraries</a> for your server. Very fast, unfortunately it can only search a single field currently."}
		{/forminput}
	</div>

	<div class="control-group submit">
		<input type="submit" class="btn" name="submit" value="{tr}Save{/tr}" />
	</div>
{/form}
{/strip}

