<?php

include_once( LUCENE_PKG_PATH.'BitLucene.php' );

$lucId = !empty( $_REQUEST['lucene_id'] ) ? $_REQUEST['lucene_id'] : NULL;

$luceneClass = $gBitSystem->getConfig( 'lucene_engine', 'BitZendLucene' );

require_once( LUCENE_PKG_PATH.$luceneClass.'.php' );

$gLucene = new $luceneClass( $lucId );

if( $gLucene->load() ) {
}

$gBitSmarty->assign_by_ref( 'gLucene', $gLucene );

?>
