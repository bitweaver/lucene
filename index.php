<?php

require_once( '../bit_setup_inc.php' );

/* $field is the default field to be searched. */
$field = "data";

$indexDir = $gBitSystem->getPreference( 'lucene_index_dir', TEMP_PKG_PATH.'lucene/index' );

mkdir_p( $indexDir );

$feedback = array();

/* Creation of an IndexSearcher instance */
try {
	$searcher = new IndexSearcher( $indexDir, $field );
} catch (Exception $e) {
	$feedback['error'] = 'The search index is unavailable: '.$e->getMessage();
}

if( isset( $_REQUEST['search_phrase'] ) ) {
	$query = $_REQUEST['search_phrase'];
	$hits = $searcher->search( $query );
	$gBitSmarty->assign_by_ref( 'searchHits', $hits->length() );
	$gBitSmarty->assign_by_ref( 'searchResults', $hits );
}

$gBitSmarty->assign_by_ref( 'feedback', $feedback );

$gBitSystem->display( 'bitpackage:clucene/clucene_search.tpl' );

?>
