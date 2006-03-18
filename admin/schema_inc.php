<?php


$tables = array(

'lucene_indices' => "
	lucene_id I4 PRIMARY,
	index_title C(250) NOTNULL,
	index_fields X NOTNULL,
	index_path C(250),
	index_interval I4 NOTNULL DEFAULT '86400',
	last_indexed I8 NOTNULL DEFALT '0',
	next_index I8 NOTNULL DEFALT '0',
	result_template C(255)
",

'lucene_queries' => "
	lucene_id I4 NOTNULL,
	lucene_query X NOTNULL,
	lucene_index_columns X NOTNULL
	CONSTRAINT	', CONSTRAINT `lucene_index_queries_ref` FOREIGN KEY (`lucene_id`) REFERENCES `".BIT_DB_PREFIX."lucene_indices` (`lucene_id`)'
",

'lucene_search_history' => "
	lucene_id I4 NOTNULL PRIMARY,
	search_phrase C(255) NOTNULL PRIMARY,
	search_count I4 NOTNULL DEFAULT 1,
	last_searched I8 NOTNULL,
	last_searched_ip C(16),
	is_blacklisted C(1)
	CONSTRAINT	', CONSTRAINT `lucene_searches_indices_ref` FOREIGN KEY (`lucene_id`) REFERENCES `".BIT_DB_PREFIX."lucene_indices` (`lucene_id`)'
"

);

global $gBitInstaller;

foreach( array_keys( $tables ) AS $tableName ) {
	$gBitInstaller->registerSchemaTable( LUCENE_PKG_NAME, $tableName, $tables[$tableName] );
}

$gBitInstaller->registerPackageInfo( LUCENE_PKG_NAME, array(
	'description' => "A search package utilizing the <a href=\"http://lucene.apache.org\">Lucene search engine</a>.",
	'license' => '<a href="http://www.gnu.org/licenses/licenses.html#LGPL">LGPL</a>',
	'version' => '0.1',
	'state' => 'beta',
	'dependencies' => '',
) );

// ### Indexes
$indices = array (
	'lucene_history_phrase_idx' => array( 'table' => 'lucene_search_history', 'cols' => 'search_phrase', 'opts' => '' ),
	'lucene_history_lucene_idx' => array( 'table' => 'lucene_indices', 'cols' => 'lucene_id', 'opts' => '' ),
);
// TODO - SPIDERR - following seems to cause time _decrease_ cause bigint on postgres. need more investigation
//	'blog_posts_created_idx' => array( 'table' => 'blog_posts', 'cols' => 'created', 'opts' => NULL ),
$gBitInstaller->registerSchemaIndexes( LUCENE_PKG_NAME, $indices );

// ### Sequences
$sequences = array (
	'lucene_id_seq' => array( 'start' => 1 )
);
$gBitInstaller->registerSchemaSequences( LUCENE_PKG_NAME, $sequences );

// ### Default UserPermissions
$gBitInstaller->registerUserPermissions( LUCENE_PKG_NAME, array(
	array('bit_p_lucene_admin', 'Can administer the lucene search engine', 'admin', LUCENE_PKG_NAME),
	array('bit_p_lucene_search', 'Can use the search engine', 'basic', LUCENE_PKG_NAME),
) );


?>
