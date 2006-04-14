<?php
/**
 * Lucene class
 *
 * @package  lucene
 * @version  $Header: /cvsroot/bitweaver/_bit_lucene/BitCLucene.php,v 1.3 2006/04/14 23:00:07 spiderr Exp $
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

class BitCLucene extends BitLucene {

	function search( $pQuery ) {
		global $gBitSystem;
		$this->mResults = array();
		if( $this->verifySearchIndex() ) {
			parent::search( $pQuery );
			// do we have & want php-clucene ?
			/* Creation of an IndexSearcher instance */
			try {
				$fields = explode( ',', $this->getField( 'index_fields' ) );
				if( in_array( 'title', $fields ) ) {
					$searchField = 'title';
				} elseif( in_array( 'data', $fields ) )  {
					$searchField = 'data';
				}
				$searcher = new IndexSearcher( $this->getField( 'index_path' ), 'title' );
				$this->mResults = $searcher->search( $pQuery );
			} catch (Exception $e) {
				$this->mError = 'The search index is unavailable: '.$e->getMessage();
			}
		}
		return count( $this->mResults );
	}

	function getResult( $pRow, $pField, $pDefault=NULL ) {
		$ret = NULL;
		if( !empty( $this->mResults ) ) {
			// return ZSearchDocument object for this hit
			$ret = $this->mResults->get( $pRow, $pField );
		}
		return( $ret );
	}

	function getScore() {
		$ret = NULL;
		if( !empty( $this->mResults ) ) {
			// return ZSearchDocument object for this hit
			$ret = $this->mResults->score( $pRow );
		}
		return( $ret );
	}

	function getResultCount() {
		$ret = 0;
		if( !empty( $this->mResults ) ) {
			$ret = $this->mResults->length();
		}
		return( $ret );
	}

	function verifyEngine() {
		if( !class_exists( 'IndexSearcher' ) ) {
			global $gBitSystem;
			$gBitSystem->fatalError( "The search engine is not available. (clucene)" );
		}
	}
}

?>