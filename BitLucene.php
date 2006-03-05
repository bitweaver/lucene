<?php
/**
 * Lucene class
 *
 * @package  lucene
 * @version  $Header: /cvsroot/bitweaver/_bit_lucene/BitLucene.php,v 1.1 2006/03/05 02:40:18 spiderr Exp $
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

class BitLucene extends BitBase {
	var $mLuceneId;

	function BitLucene( $pLuceneId=NULL ) {
		parent::BitBase();
		$this->mLuceneId = $pLuceneId;
	}

	function verify( &$pParamHash ) {
		if( !$this->isValid() && empty( $_REQUEST['index_title'] ) ) {
			$this->mErrors['index_title'] = 'You must enter a title.';
		} elseif( !empty( $_REQUEST['index_title'] ) && $this->getField( 'index_title' ) != $_REQUEST['index_title'] ) {
			$pParamHash['lucene_store']['index_title'] = $_REQUEST['index_title'];
		}

		if( !$this->isValid() && empty( $_REQUEST['index_path'] ) ) {
			$this->mErrors['index_path'] = 'You must enter a path to the index file.';
		} elseif( !empty( $_REQUEST['index_path'] ) && $this->getField( 'title' ) != $_REQUEST['index_path'] ) {
			$pParamHash['lucene_store']['index_path'] = $_REQUEST['index_path'];
		}

		return( count( $this->mErrors ) == 0 && !empty( $pParamHash['lucene_store'] ) );
	}

	function store( &$pParamHash ) {
		if( $this->verify( $pParamHash ) ) {
			if( $this->isValid() ) {
				$this->mDb->associateUpdate( 'lucene_indices', $pParamHash['lucene_store'], array( 'lucene_id' => $this->mLuceneId ) );
			} else {
				$pParamHash['lucene_store']['lucene_id'] = $this->mDb->GenID( 'lucene_id_seq' );
				$this->mDb->associateInsert( 'lucene_indices', $pParamHash['lucene_store'] );
				$this->mLuceneId = $pParamHash['lucene_store']['lucene_id'];
			}
		}
	}

	function getList( &$pListHash ) {
		$this->prepGetList( $pListHash );
		$query = "SELECT * FROM `".BIT_DB_PREFIX."lucene_indices` ORDER BY `index_title`";
		return( $this->mDb->getAssoc( $query ) );
	}

	function expunge() {
	}

	function isValid() {
		return( is_numeric( $this->mLuceneId ) );
	}
}

?>