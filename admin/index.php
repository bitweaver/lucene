<?php
/**
 * Lucene administration page
 *
 * @package  lucene
 * @version  $Header: /cvsroot/bitweaver/_bit_lucene/admin/index.php,v 1.1 2006/03/05 02:40:18 spiderr Exp $
 * @author   spider <spider@steelsun.com>
 */
// +----------------------------------------------------------------------+
// | Copyright (c) 2006, bitweaver.org
// +----------------------------------------------------------------------+
// | All Rights Reserved. See copyright.txt for details and a complete list of authors.
// | Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details
// |
// | For comments, please use phpdocu.sourceforge.net documentation standards!!!
// | -> see http://phpdocu.sourceforge.net/
// +----------------------------------------------------------------------+
// | Authors: spider <spider@steelsun.com>
// +----------------------------------------------------------------------+

include_once( '../../bit_setup_inc.php' );
include_once( LUCENE_PKG_PATH.'BitLucene.php' );

$mid = 'bitpackage:lucene/admin_lucene_list.tpl';

$luc = new BitLucene();

if( !empty( $_REQUEST['action'] ) ) {

	switch( $_REQUEST['action'] ) {
		case 'create';
			$mid = 'bitpackage:lucene/admin_lucene_edit.tpl';
			break;
	}

} elseif( !empty( $_REQUEST['save'] ) ) {
	if( !$luc->store( $_REQUEST ) ) {
		$gBitSmarty->assign_by_ref( 'errors', $luc->mErrors );
	}
	$mid = 'bitpackage:lucene/admin_lucene_edit.tpl';
} else {
	$indexList = $luc->getList( $_REQUEST );
	$gBitSmarty->assign_by_ref( 'indexList', $indexList );
}

$gBitSystem->verifyPermission( 'bit_p_lucene_admin' );

$gBitSystem->display( $mid, tra( 'Administer' ).': Lucene' );

?>