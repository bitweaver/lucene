<?php
// $Header: /cvsroot/bitweaver/_bit_lucene/admin/admin_lucene_inc.php,v 1.2 2006/03/11 06:56:58 spiderr Exp $
// Copyright (c) 2002-2003, Luis Argerich, Garland Foster, Eduardo Polidor, et. al.
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.

$availableIndexes = array();

if( class_exists( 'IndexSearcher' ) ) {
	$available['BitCLucene'] = '';
}

if( class_exists( 'IndexSearcher' ) ) {
	$available['BitCLucene'] = 'php-clucene';
}

if( class_exists( 'Java' ) ) {
	$available['BitJavaLucene'] = 'php-java';
}

if( $gBitSystem->isPackageActive( 'zend' ) ) {
	$available['BitZendLucene'] = 'ZendLucene';
}

if( isset( $_REQUEST['lucene_engine'] ) ) {
	$gBitSystem->storeConfig( 'lucene_engine', $_REQUEST['lucene_engine'] );
}

$gBitSmarty->assign( 'luceneEngines', $available );
?>
