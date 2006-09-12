<?php
global $gBitSystem;
$registerHash = array(
	'package_name' => 'lucene',
	'package_path' => dirname( __FILE__ ).'/',
);
$gBitSystem->registerPackage( $registerHash );

if( $gBitSystem->isPackageActive( LUCENE_PKG_NAME ) ) {
	$menuHash = array(
		'package_name'  => LUCENE_PKG_NAME,
		'index_url'     => LUCENE_PKG_URL.'index.php',
		'menu_template' => 'bitpackage:lucene/menu_lucene.tpl',
	);
	$gBitSystem->registerAppMenu( $menuHash );
}

?>
