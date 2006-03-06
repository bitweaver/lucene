<?php

require_once( '../bit_setup_inc.php' );

/* $field is the default field to be searched. */
$field = "data";

$_REQUEST['lucene_id'] = 1;
require_once( LUCENE_PKG_PATH.'lookup_lucene_inc.php' );

$gBitSystem->verifyPackage( 'lucene' );

if (isset ($_POST["search_phrase"])) {
	$gLucene->search( $_POST["search_phrase"] );
}

$gBitSystem->display( 'bitpackage:lucene/lucene_search.tpl' );

?>
