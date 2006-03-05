{* $Header: /cvsroot/bitweaver/_bit_lucene/templates/admin_lucene_edit.tpl,v 1.1 2006/03/05 02:40:19 spiderr Exp $ *}
{strip}

<div class="floaticon">
	<a href="{$smarty.const.LUCENE_PKG_URL}admin/index.php">{tr}&laquo; Index List{/tr}</a>
	{bithelp}
</div>

<div class="admin indexs">
	<div class="header">
		<h1>{if $indexInfo.index_name}{tr}Administer Index{/tr}: {$indexInfo.index_name}{else}{tr}Create New Index{/tr}{/if}</h1>
	</div>

	<div class="body">
		{formfeedback success=$successMsg error=$errorMsg}

		{jstabs}
			{jstab title="Edit Index"}
				{form legend="Add or Edit a Index"}
					<input type="hidden" name="index_id" value="{$indexInfo.index_id}" />
					<div class="row">
						{formfeedback error=$errors.index_title}
						{formlabel label="Title" for="indextitle"}
						{forminput}
							<input type="text" name="index_title" id="indextitle" size="30" maxlength="30" value="{$indexInfo.index_title}" />
						{/forminput}
					</div>
					<div class="row">
						{formfeedback error=$errors.index_path}
						{formlabel label="Path to Index" for="indexpath"}
						{forminput}
							<input type="text" name="index_path" id="indexpath" size="60" maxlength="250" value="{$indexInfo.index_name|default:"`$smarty.const.TEMP_PKG_PATH`lucene/index"}" />
							{formhelp note="This is the directory where the index will be kept. We strongly suggest a non web-accessible directory."}
						{/forminput}
					</div>

					<div class="row submit">
						<input type="submit" name="cancel" value="{tr}Cancel{/tr}" />&nbsp;
						<input type="submit" name="save" value="{tr}Save Index{/tr}" />
					</div>
				{/form}
			{/jstab}

			{if $indexInfo.index_id}
				{jstab title="Index Queries"}
					{form legend="Add queries used for indexing."}

					{/form}
				{/jstab}
			{/if}
		{/jstabs}
	</div><!-- end .body -->
</div><!-- end .liberty -->

{/strip}
