<?php
/**
 * Lucene class
 *
 * @package  lucene
 * @version  $Header: /cvsroot/bitweaver/_bit_lucene/BitLucene.php,v 1.2 2006/03/06 00:09:18 spiderr Exp $
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
			$rows = $this->mDb->getAll( "SELECT * FROM `".BIT_DB_PREFIX."lucene_indices` WHERE `lucene_id`=?", array( $this->mLuceneId ) );
			$this->mInfo = $rows[0];
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
			$pParamHash['lucene_store']['index_fields'] = $pParamHash['index_fields'];
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
				$this->mDb->associateUpdate( 'lucene_indices', $pParamHash['lucene_store'], array( 'lucene_id' => $this->mLuceneId ) );
			} else {
				$pParamHash['lucene_store']['lucene_id'] = $this->mDb->GenID( 'lucene_id_seq' );
				$this->mDb->associateInsert( 'lucene_indices', $pParamHash['lucene_store'] );
				$this->mLuceneId = $pParamHash['lucene_store']['lucene_id'];
			}
			$this->storeQueries( $pParamHash );
		}
		return( count( $this->mErrors ) == 0 );
	}

	function storeQueries( &$pParamHash ) {
		if( $this->isValid() ) {
			$query = "DELETE FROM  `".BIT_DB_PREFIX."lucene_queries` WHERE `lucene_id`=?";
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

	function getList( &$pListHash ) {
		$this->prepGetList( $pListHash );
		$query = "SELECT * FROM `".BIT_DB_PREFIX."lucene_indices` ORDER BY `index_title`";
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
			$query = "DELETE FROM  `".BIT_DB_PREFIX."lucene_indices` WHERE `lucene_id`=?";
			$this->mDb->query( $query, array( $this->mLuceneId ) );
		}
	}

	function isValid() {
		return( is_numeric( $this->mLuceneId ) );
	}

	function search( $pQuery ) {
		global $gBitSystem;
		if( file_exists( $this->getField( 'index_path' ) ) ) {
			// do we have & want php-clucene ?
			if( class_exists( 'IndexSearcher' ) && $gBitSystem->isFeatureActive( 'lucene_php-clucene' ) ) {
				/* Creation of an IndexSearcher instance */
				try {
					$searcher = new IndexSearcher( $indexDir, $field );
					$query = $_REQUEST['search_phrase'];
					$hits = $searcher->search( $query );
					$this->mHits = $hits->length();
					$gBitSmarty->assign_by_ref( 'searchResults', $hits );
				} catch (Exception $e) {
					$this->mError = 'The search index is unavailable: '.$e->getMessage();
				}
			} elseif( class_exists( 'Java' ) ) {
				// use java wddx
				$query = $_POST["search_phrase"];

				java_require( LUCENE_PKG_PATH.'indexer/lucene.jar;'.LUCENE_PKG_PATH.'indexer/wddx.jar;'.LUCENE_PKG_PATH.'indexer' );
				$obj = new Java("org.bitweaver.lucene.SearchEngine");
				$result = $obj->search( $this->getField('index_path'),"OR",$query);

				$rs = wddx_deserialize((string)$result);
				$meta = $rs["meta_data"];
				$this->mHits = $meta['hits'];
				if( !empty( $rs["rows"] ) ) {
					$this->mResults = $rs['rows'];
				}
			} else {
				$this->mError = 'The search engine is unavailable';
			}
		} else {
			$this->mError = 'The search index is unavailable';
		}
		return count( $this->mResults );
	}

	function getResult( $pRow, $pField ) {
		$ret = NULL;
		if( !empty( $this->mResults[$pRow][$pField] ) ) {
			$ret = $this->mResults[$pRow][$pField];
		}
		return( $ret );
	}


}

?>