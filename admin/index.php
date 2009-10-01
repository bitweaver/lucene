<?php
/**
 * Lucene administration page
 *
 * @package  lucene
 * @version  $Header: /cvsroot/bitweaver/_bit_lucene/admin/index.php,v 1.5 2009/10/01 13:45:43 wjames5 Exp $
 * @author   spider <spider@steelsun.com>
 */
// +----------------------------------------------------------------------+
// | Copyright (c) 2006, bitweaver.org
// +----------------------------------------------------------------------+
// | All Rights Reserved. See copyright.txt for details and a complete list of authors.
// | Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See http://www.gnu.org/copyleft/lesser.html for details
// |
// | For comments, please use phpdocu.sourceforge.net documentation standards!!!
// | -> see http://phpdocu.sourceforge.net/
// +----------------------------------------------------------------------+
// | Authors: spider <spider@steelsun.com>
// +----------------------------------------------------------------------+

include_once( '../../bit_setup_inc.php' );

include_once( '../lookup_lucene_inc.php' );

$mid = 'bitpackage:lucene/admin_lucene_list.tpl';

if( !empty( $_REQUEST['action'] ) ) {

	switch( $_REQUEST['action'] ) {
		case 'edit':
		case 'create':
			$mid = 'bitpackage:lucene/admin_lucene_edit.tpl';
			break;
		case 'delete':
			if( empty( $_POST['confirm'] ) ) {
				$formHash['action'] = 'delete';
				$formHash['lucene_id'] = $gLucene->mLuceneId;
				$gBitSystem->confirmDialog( $formHash, array( 'warning' => tra( 'Are you sure you want to delete the search index').' "'.$gLucene->getField('index_title').'"?', 'error' => 'This cannot be undone!' ) );
			} else {
				$gLucene->expunge();
				$indexList = $gLucene->getList( $_REQUEST );
				$gBitSmarty->assign_by_ref( 'indexList', $indexList );
			}
			break;
	}
} elseif( !empty( $_REQUEST['save'] ) ) {
	if( !$gLucene->store( $_REQUEST ) ) {
		$gBitSmarty->assign_by_ref( 'errors', $gLucene->mErrors );
	} else {
		$gLucene->load();
	}
	$mid = 'bitpackage:lucene/admin_lucene_edit.tpl';
} else {
	$indexList = $gLucene->getList( $_REQUEST );
	$gBitSmarty->assign_by_ref( 'indexList', $indexList );
}

$gBitSystem->verifyPermission( 'p_lucene_admin' );

$gBitSystem->display( $mid, tra( 'Administer' ).': Lucene' , array( 'display_mode' => 'admin' ));

?>