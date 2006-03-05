<?php
global $gBitSystem;
$registerHash = array(
	'package_name' => 'lucene',
	'package_path' => dirname( __FILE__ ).'/',
);
$gBitSystem->registerPackage( $registerHash );

if( $gBitSystem->isPackageActive( LUCENE_PKG_NAME ) ) {
	$gBitSystem->registerAppMenu( LUCENE_PKG_DIR, 'Search', LUCENE_PKG_URL.'index.php', 'bitpackage:lucene/menu_lucene.tpl', LUCENE_PKG_NAME );
}

?>
