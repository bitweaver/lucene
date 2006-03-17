<?php
/**
 * Lucene class
 *
 * @package  lucene
 * @version  $Header: /cvsroot/bitweaver/_bit_lucene/BitZendLucene.php,v 1.1 2006/03/11 06:56:57 spiderr Exp $
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

class BitZendLucene extends BitLucene {

	function search( $pQuery ) {
		$this->mResults = array();
		if( $this->verifySearchIndex() ) {
			require_zend_file( 'Search/Lucene.php' );

			$index = new ZSearch( $this->getField( 'index_path' ) );
			$fields = explode( ',', $this->getField( 'index_fields' ) );
			$query = '';
			$lowQuery = strtolower( $pQuery );
			foreach( $fields as $f ) {
				$query .= "$f:$lowQuery OR ";
			}
			$query = preg_replace( '/ OR $/', '', $query );
			$this->mResults = $index->find( $query );
		}
		return( count( $this->mResults ) );
	}

	function getResult( $pRow, $pField, $pDefault=NULL ) {
		$ret = NULL;
		if( !empty( $this->mResults[$pRow] ) ) {
			// return ZSearchDocument object for this hit
			$document = $this->mResults[$pRow]->getDocument();

			try {
				// return a ZSearchField object from the ZSearchDocument
				$ret = $document->$pField;
			} catch( Exception $e ) {
			}
		}
		return( $ret );
	}

	function getResultCount() {
		$ret = 0;
		if( !empty( $this->mResults ) ) {
			$ret = count( $this->mResults );
		}
		return( $ret );
	}

	function verifyEngine() {
		global $gBitSystem;
		$gBitSystem->verifyPackage( 'zend' );
	}
}

?>