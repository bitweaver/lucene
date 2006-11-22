<?php
/**
 * $Header: /cvsroot/bitweaver/_bit_lucene/modules/mod_global_search.php,v 1.2 2006/11/22 06:08:20 spiderr Exp $
 *
 * Copyright (c) 2004 bitweaver.org
 * Copyright (c) 2003 tikwiki.org
 * Copyright (c) 2002-2003, Luis Argerich, Garland Foster, Eduardo Polidor, et. al.
 * All Rights Reserved. See copyright.txt for details and a complete list of authors.
 * Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details
 *
 * $Id: mod_global_search.php,v 1.2 2006/11/22 06:08:20 spiderr Exp $
 * @author  Luis Argerich (lrargerich@yahoo.com)
 * @package search
 * @subpackage modules
 */
global $gLibertySystem, $gLucene;

require_once( LUCENE_PKG_PATH.'lookup_lucene_inc.php' );
	$searchIndexes = array( '' => 'All' );
	array_push( $searchIndexes, $gLucene->getIndexList() );
	$gBitSmarty->assign( 'searchIndexes', $searchIndexes );
?>
