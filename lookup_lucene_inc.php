<?php
/**
 * @version  $Header: /cvsroot/bitweaver/_bit_lucene/lookup_lucene_inc.php,v 1.4 2008/06/19 04:44:33 lsces Exp $
 * Lucene class
 *
 * @package  lucene
 * @subpackage functions
 * @author   spider <spider@steelsun.com>
 */

/**
 * Initialize
 */
include_once( LUCENE_PKG_PATH.'BitLucene.php' );

$lucId = !empty( $_REQUEST['lucene_id'] ) ? $_REQUEST['lucene_id'] : NULL;

$luceneClass = $gBitSystem->getConfig( 'lucene_engine', 'BitZendLucene' );

require_once( LUCENE_PKG_PATH.$luceneClass.'.php' );

$gLucene = new $luceneClass( $lucId );

if( $gLucene->load() ) {
}

$gBitSmarty->assign_by_ref( 'gLucene', $gLucene );

?>
