<?php

require_once( '../bit_setup_inc.php' );

/* $field is the default field to be searched. */
$field = "contents";

/* Creation of an IndexSearcher instance */
try {
	$searcher = new IndexSearcher( CLUCENE_PKG_PATH."data/index", $field);
} catch (Exception $e) {
	echo 'Message: ' . $e->getMessage() . ', ';
	echo 'File: '    . $e->getFile()    . ', ';
	echo 'Line: '    . $e->getLine();
	exit();
}

$query = '';
if (isset($_SERVER['argv'][1])) {
	$query = $_SERVER['argv'][1];
} else {
	$query = "time"; /* Should return 3 results */
}

$hits = $searcher->search( $query );
$gBitSmarty->assign_by_ref( 'searchHits', $hits->length() );
$gBitSmarty->assign_by_ref( 'searchResults', $hits );


$gBitSystem->display( 'bitpackage:clucene/clucene_search.tpl' );

?>
