{strip}
<div class="listing lucene">
	<div class="header">
		<h1>{tr}List of Lucene Search Indices{/tr}</h1>
	</div>

	<div class="body">
		{smartlink ititle="Add a new index" ipackage=lucene ifile="admin/index.php" action=create}

		<div class="navbar">
			<ul>
				<li>{booticon iname="icon-circle-arrow-right"  ipackage="icons"  iexplain="sort by"}</li>
				<li>{smartlink ititle="Name" isort="group_name" offset=$offset idefault=1}</li>
				<li>{smartlink ititle="Next Index" isort="last_indexed_desc" offset=$offset}</li>
			</ul>
		</div><!-- end .navbar -->

		{formfeedback success=$successMsg error=$errorMsg}

		<ul class="clear data">
			{foreach from=$indexList key=indexId item=idx}
				<li class="item {cycle values='odd,even'}">
					<div class="floaticon">
						{smartlink ititle="Edit" ipackage="lucene" ifile="admin/index.php" booticon="icon-edit" lucene_id=$indexId action=edit}
						{smartlink ititle="Remove" ipackage="lucene" ifile="admin/index.php" booticon="icon-trash" action=delete lucene_id=$indexId}
					</div>

					<h2>{$idx.index_title}</h2>

					<div>
						{tr}Queries{/tr}
						<ul class="small">
							{foreach from=$idx.queries key=queryId item=query}
								<li>{$query}</li>
							{foreachelse}
								<li>{tr}none{/tr}</li>
							{/foreach}
						</ul>
					</div>
					<div class="clear"></div>
				</li>
			{foreachelse}
				<li class="item {cycle values='odd,even'}">No Search Indexes exist. {smartlink ititle="Add a new index" ipackage=lucene ifile="admin/index.php" action=create}
				</li>
			{/foreach}
		</ul>
		{pagination}
	</div><!-- end .body -->
</div><!-- end .liberty -->
{/strip}
