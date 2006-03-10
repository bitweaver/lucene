<?php

require_once( '../bit_setup_inc.php' );

/* $field is the default field to be searched. */
$field = "data";

if( !empty( $_REQUEST['search_index'] ) && is_numeric( $_REQUEST['search_index'] ) ) {
	$_REQUEST['lucene_id'] = $_REQUEST['search_index'];
} else {
	$_REQUEST['lucene_id'] = 1;
}
require_once( LUCENE_PKG_PATH.'lookup_lucene_inc.php' );

$gBitSystem->verifyPackage( 'lucene' );

if (isset ($_POST["search_phrase"])) {
	if( $gLucene->search( $_POST["search_phrase"] ) ) {
		$gBitSmarty->assign( 'fieldHash', explode( ',', $gLucene->getField( 'index_fields' ) ) );
	}
}

$gBitSmarty->assign( 'searchIndices', $gLucene->getIndexList() );

$gBitSystem->display( 'bitpackage:lucene/lucene_search.tpl' );

?>
