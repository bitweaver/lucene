<?php
/**
 * Lucene class
 *
 * @package  lucene
 * @version  $Header: /cvsroot/bitweaver/_bit_lucene/BitJavaLucene.php,v 1.1 2006/03/11 06:56:57 spiderr Exp $
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

require_once( LUCENE_PKG_PATH.'BitLucene.php' );

class BitJavaLucene extends BitLucene {

	function search( $pQuery ) {
		global $gBitSystem;
		$this->mResults = array();
		$this->mHits = 0;
		if( $this->verifySearchIndex() ) {
			// use java wddx
			$query = $_POST["search_phrase"];

			java_require( LUCENE_PKG_PATH.'indexer/lucene.jar;'.LUCENE_PKG_PATH.'indexer/wddx.jar;'.LUCENE_PKG_PATH.'indexer' );
			$obj = new Java("org.bitweaver.lucene.SearchEngine");
			$result = $obj->search( $this->getField('index_path'), "OR", $query, $this->getField( 'index_fields' ) );
			$rs = wddx_deserialize( (string)$result );
			$meta = $rs["meta_data"];
			$this->mHits = $meta['hits'];
			if( !empty( $rs["rows"] ) ) {
				$this->mResults = $rs['rows'];
			}
		}
		return count( $this->mResults );
	}

	function getResult( $pRow, $pField, $pDefault=NULL ) {
		if( !empty( $this->mResults[$pRow][$pField] ) ) {
			$ret = $this->mResults[$pRow][$pField];
		} else {
			$ret = $pDefault;
		}
		return( $ret );
	}

	function getResultCount() {
		$ret = 0;
		if( !empty( $this->mHits ) ) {
			$ret = $this->mHits;
		}
		return( $ret );
	}

	function verifyEngine() {
		if( !class_exists( 'Java' ) ) {
			global $gBitSystem;
			$gBitSystem->fatalError( "The search engine is not available. (java)" );
		}
	}

}

?>