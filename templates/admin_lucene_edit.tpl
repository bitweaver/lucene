{* $Header: /cvsroot/bitweaver/_bit_lucene/templates/admin_lucene_edit.tpl,v 1.6 2006/04/14 23:00:07 spiderr Exp $ *}
{strip}

<div class="floaticon">
	<a href="{$smarty.const.LUCENE_PKG_URL}admin/index.php">{tr}&laquo; Index List{/tr}</a>
	{bithelp}
</div>

<div class="admin indexs">
	<div class="header">
		<h1>{if $gLucene->isValid()}{tr}Administer Index{/tr}: {$gLucene->getField('index_title')}{else}{tr}Create New Index{/tr}{/if}</h1>
	</div>

	<div class="body">
		{formfeedback success=$successMsg error=$errorMsg}

		{form legend="Add or Edit a Index"}
		{jstabs}
			{jstab title="Edit Index"}
					<input type="hidden" name="lucene_id" value="{$gLucene->getField('lucene_id')}" />
					<div class="row">
						{formfeedback error=$errors.index_title}
						{formlabel label="Title" for="indextitle"}
						{forminput}
							<input type="text" name="index_title" id="indextitle" size="30" maxlength="30" value="{$gLucene->getField('index_title')}" />
						{/forminput}
					</div>
					<div class="row">
						{formfeedback error=$errors.index_path}
						{formlabel label="Path to Index" for="indexpath"}
						{forminput}
							<input type="text" name="index_path" id="indexpath" size="60" maxlength="250" value="{$gLucene->getField('index_path',"`$smarty.const.TEMP_PKG_PATH`lucene/index")}" />
							{formhelp note="This is the directory where the index will be kept. We strongly suggest a non web-accessible directory."}
						{/forminput}
					</div>
					<div class="row">
						{formfeedback error=$errors.index_fields}
						{formlabel label="Search Fields" for="indexfields"}
						{forminput}
							<input type="text" name="index_fields" id="indexfields" size="60" maxlength="250" value="{$gLucene->getField('index_fields')}" />
							{formhelp note="This is a comma-separated list of all fields that will be searched. They should be the same as the columns present in the SELECT clause of each of the index queries."}
						{/forminput}
					</div>
					<div class="row">
						{formfeedback error=$errors.index_interval}
						{formlabel label="Index Interval" for="indexinterval"}
						{forminput}
							<input type="text" name="index_interval" id="indexinterval" size="6" maxlength="10" value="{$gLucene->getField('index_interval',86400)}" />
							{formhelp note="Number of seconds that must pass before the index is recreated."}
						{/forminput}
					</div>
					<div class="row">
						{formfeedback error=$errors.sort_order}
						{formlabel label="Sort Order" for="sortorder"}
						{forminput}
							<input type="text" name="sort_order" id="indexinterval" size="6" maxlength="10" value="{$gLucene->getField('sort_order')}" />
							{formhelp note="Order index is listed in search options."}
						{/forminput}
					</div>


					<div class="row submit">
						<input type="submit" name="cancel" value="{tr}Cancel{/tr}" />&nbsp;
						<input type="submit" name="save" value="{tr}Save Index{/tr}" />
					</div>
			{/jstab}

			{jstab title="Index Queries"}
				{form legend="Add queries used for indexing. Every column in the SELECT will be indexed automatically."}

				{foreach from=$gLucene->mQueries item=query}
					<input type="text" name="lucene_query[]" value="{$query}" size="100" /><br/>
				{/foreach}
					<input type="text" name="lucene_query[]" value="" size="100" /><br/>
					<input type="text" name="lucene_query[]" value="" size="100" /><br/>
					<input type="text" name="lucene_query[]" value="" size="100" /><br/>
				{/form}
			{/jstab}
		{/jstabs}
		{/form}
	</div><!-- end .body -->
</div><!-- end .liberty -->

{/strip}
