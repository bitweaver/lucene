<?php
/**
 * Lucene class
 *
 * @package  lucene
 * @version  $Header: /cvsroot/bitweaver/_bit_lucene/BitLucene.php,v 1.10 2007/02/14 04:14:55 spiderr Exp $
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

	function load() {
		$this->mInfo = array();
		if( $this->isValid() ) {
			if( $rows = $this->mDb->getAll( "SELECT * FROM `".BIT_DB_PREFIX."lucene_indexes` WHERE `lucene_id`=?", array( $this->mLuceneId ) ) ) {
				$this->mInfo = $rows[0];
			}
			$this->mQueries = $this->mDb->getCol( "SELECT `lucene_query` FROM `".BIT_DB_PREFIX."lucene_queries` WHERE `lucene_id`=? ORDER BY `lucene_query`", array( $this->mLuceneId ) );
		}
		return( count( $this->mInfo ) );
	}

	function verify( &$pParamHash ) {
		if( empty( $pParamHash['index_title'] ) ) {
			$this->mErrors['index_title'] = 'You must enter a title.';
		} elseif( !empty( $pParamHash['index_title'] ) && $this->getField( 'index_title' ) != $pParamHash['index_title'] ) {
			$pParamHash['lucene_store']['index_title'] = $pParamHash['index_title'];
		}

		if( empty( $pParamHash['index_fields'] ) ) {
			$this->mErrors['index_fields'] = 'You must enter index fields.';
		} elseif( !empty( $pParamHash['index_fields'] ) && $this->getField( 'index_fields' ) != $pParamHash['index_fields'] ) {
			$pParamHash['lucene_store']['index_fields'] = trim( str_replace( ' ', '', $pParamHash['index_fields'] ) );
		}

		if( $this->isValid() && empty( $pParamHash['index_interval'] ) ) {
			$this->mErrors['index_interval'] = 'You must enter an index interval.';
		} elseif( !empty( $pParamHash['index_interval'] ) && $this->getField( 'index_interval' ) != $pParamHash['index_interval'] ) {
			$pParamHash['lucene_store']['index_interval'] = $pParamHash['index_interval'];
		}

		if( !empty( $pParamHash['sort_order'] ) && is_numeric( $pParamHash['sort_order'] ) ) {
			$pParamHash['lucene_store']['sort_order'] = $pParamHash['sort_order'];
		} else {
			$pParamHash['lucene_store']['sort_order'] = NULL;
		}

		if( !$this->isValid() && empty( $pParamHash['index_path'] ) ) {
			$this->mErrors['index_path'] = 'You must enter a path to the index file.';
		} elseif( !empty( $pParamHash['index_path'] ) && $this->getField( 'title' ) != $pParamHash['index_path'] ) {
			$pParamHash['lucene_store']['index_path'] = $pParamHash['index_path'];
		}

		return( count( $this->mErrors ) == 0 && !empty( $pParamHash['lucene_store'] ) );
	}

	function store( &$pParamHash ) {
		if( $this->verify( $pParamHash ) ) {
			if( $this->isValid() ) {
				$this->mDb->associateUpdate( 'lucene_indexes', $pParamHash['lucene_store'], array( 'lucene_id' => $this->mLuceneId ) );
			} else {
				$pParamHash['lucene_store']['lucene_id'] = $this->mDb->GenID( 'lucene_id_seq' );
				$this->mDb->associateInsert( 'lucene_indexes', $pParamHash['lucene_store'] );
				$this->mLuceneId = $pParamHash['lucene_store']['lucene_id'];
			}
			$this->storeQueries( $pParamHash );
		}
		return( count( $this->mErrors ) == 0 );
	}

	function storeQueries( &$pParamHash ) {
		if( $this->isValid() ) {
			$query = "DELETE FROM `".BIT_DB_PREFIX."lucene_queries` WHERE `lucene_id`=?";
			$this->mDb->query( $query, array( $this->mLuceneId ) );
			if( !empty( $pParamHash['lucene_query'] ) ) {
				foreach( $pParamHash['lucene_query'] as $luceneQuery ) {
					if( !empty( $luceneQuery ) ) {
						$this->mDb->associateInsert( 'lucene_queries', array( 'lucene_query' => $luceneQuery, 'lucene_id' => $this->mLuceneId ) );
					}
				}
			}
		}
		return( count( $this->mErrors ) == 0 );
	}

	function storeSearchHistory( $pPhrase ) {
		if( strlen( $pPhrase ) > 255 ) {
			$pPhrase = substr( $pPhrase, 0, 255 );
		}
		if( $this->isValid() ) {
			$storeHash['last_searched'] = time();
			$storeHash['search_count'] = $this->mDb->getOne( "SELECT `search_count` FROM `".BIT_DB_PREFIX."lucene_search_history` WHERE `lucene_id`=? AND `search_phrase`=?", array( $this->mLuceneId, $pPhrase ) ) + 1;
			$storeHash['last_searched_ip'] = $_SERVER['REMOTE_ADDR'];
			if( $storeHash['search_count'] > 1 ) {
				$this->mDb->associateUpdate( BIT_DB_PREFIX."lucene_search_history", $storeHash, array( 'lucene_id' => $this->mLuceneId, 'search_phrase' => $pPhrase ) );
			} else {
				$storeHash['lucene_id'] = $this->mLuceneId;
				$storeHash['search_phrase'] = $pPhrase;
				$this->mDb->associateInsert( BIT_DB_PREFIX."lucene_search_history", $storeHash );
			}
		}
	}

	function getIndexList() {
		$this->prepGetList( $pListHash );
		$query = "SELECT `lucene_id`, `index_title` FROM `".BIT_DB_PREFIX."lucene_indexes` ORDER BY `sort_order`,`index_title`";
		return $this->mDb->getAssoc( $query );
	}

	function getList( &$pListHash ) {
		$this->prepGetList( $pListHash );
		$query = "SELECT lucene_id AS hash_key, * FROM `".BIT_DB_PREFIX."lucene_indexes` ORDER BY `sort_order`,`index_title`";
		$ret = $this->mDb->getAssoc( $query );

		$keys = array_keys( $ret );
		foreach( $keys as $k ) {
			$ret[$k]['queries'] = $this->mDb->getCol( "SELECT `lucene_query` FROM `".BIT_DB_PREFIX."lucene_queries` WHERE `lucene_id`=? ORDER BY `lucene_query`", array( $k ) );

		}
		return $ret;
	}

	function expunge() {
		if( $this->isValid() ) {
			$query = "DELETE FROM  `".BIT_DB_PREFIX."lucene_queries` WHERE `lucene_id`=?";
			$this->mDb->query( $query, array( $this->mLuceneId ) );
			$query = "DELETE FROM  `".BIT_DB_PREFIX."lucene_indexes` WHERE `lucene_id`=?";
			$this->mDb->query( $query, array( $this->mLuceneId ) );
		}
	}

	function verifySearchIndex() {
		$ret = FALSE;
		if( $this->isValid() ) {
			if( file_exists( $this->getField( 'index_path' ) ) ) {
				$ret = TRUE;
			} else {
				$this->mError = 'The search index is unavailable';
			}
		}
		return $ret;
	}

	function isValid() {
		return( is_numeric( $this->mLuceneId ) );
	}

	function search( $pQuery ) {
		$this->storeSearchHistory( $pQuery );
	}


}

?>
