<?php

include_once( LUCENE_PKG_PATH.'BitLucene.php' );

$lucId = !empty( $_REQUEST['lucene_id'] ) ? $_REQUEST['lucene_id'] : NULL;

$gLucene = new BitLucene( $lucId );

if( $gLucene->load() ) {
}

$gBitSmarty->assign_by_ref( 'gLucene', $gLucene );

?>