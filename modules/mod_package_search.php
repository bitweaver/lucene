<?php
/**
 * $Header: /cvsroot/bitweaver/_bit_lucene/modules/mod_package_search.php,v 1.3 2006/11/22 06:29:22 spiderr Exp $
 *
 * Copyright (c) 2004 bitweaver.org
 * Copyright (c) 2003 tikwiki.org
 * Copyright (c) 2002-2003, Luis Argerich, Garland Foster, Eduardo Polidor, et. al.
 * All Rights Reserved. See copyright.txt for details and a complete list of authors.
 * Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details
 *
 * $Id: mod_package_search.php,v 1.3 2006/11/22 06:29:22 spiderr Exp $
 * @author  Luis Argerich (lrargerich@yahoo.com)
 * @package search
 * @subpackage modules
 */

	$tplName = strtolower( ACTIVE_PACKAGE ).'_mini_search.tpl';
	$searchTemplatePath = BIT_ROOT_URL.constant( strtoupper( ACTIVE_PACKAGE ).'_PKG_PATH' ).'templates/'.$tplName;
	
	global $gLibertySystem, $gBitSmarty, $gLucene, $module_params;
	require_once( LUCENE_PKG_PATH.'lookup_lucene_inc.php' );

	if( file_exists( $searchTemplatePath ) ) {
		$searchTemplateRsrc = 'bitpackage:'.strtolower( ACTIVE_PACKAGE ).'/'.$tplName;
		$searchTitle = ucfirst( ACTIVE_PACKAGE );
	} else {
		$searchTemplateRsrc = 'bitpackage:lucene/global_mini_search.tpl';
		$searchTitle = '';
	}
	if( !empty( $module_params['search_index'] ) && is_numeric( $module_params['search_index'] ) ) {
		$gBitSmarty->assign( 'searchIndex', $module_params['search_index'] );
	} else {
		$searchIndexes = array( '' => 'All' );
		array_push( $searchIndexes, $gLucene->getIndexList() );
		$gBitSmarty->assign( 'searchIndexes', $searchIndexes );
	}

	$gBitSmarty->assign( 'searchTitle', $searchTitle );
	$gBitSmarty->assign( 'miniSearchRsrc', $searchTemplateRsrc );
?>
