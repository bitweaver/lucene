{* $Header: /cvsroot/bitweaver/_bit_lucene/templates/admin_lucene_edit.tpl,v 1.3 2006/03/06 03:28:37 spiderr Exp $ *}
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
						{formlabel label="Index Columns" for="indexfields"}
						{forminput}
							<input type="text" name="index_fields" id="indexfields" size="60" maxlength="250" value="{$gLucene->getField('index_fields')}" />
							{formhelp note="This is a comma-separated list of all columns that will be indexed and searched. They should be the same as the columns present in the SELECT clause of each of the index queries."}
						{/forminput}
					</div>


					<div class="row submit">
						<input type="submit" name="cancel" value="{tr}Cancel{/tr}" />&nbsp;
						<input type="submit" name="save" value="{tr}Save Index{/tr}" />
					</div>
			{/jstab}

			{if $gLucene->getField('lucene_id')}
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
			{/if}
		{/jstabs}
		{/form}
	</div><!-- end .body -->
</div><!-- end .liberty -->

{/strip}
