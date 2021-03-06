<?php
/**
* HybridAuth
* http://hybridauth.sourceforge.net | http://github.com/hybridauth/hybridauth
* (c) 2009-2015, HybridAuth authors | http://hybridauth.sourceforge.net/licenses.html
*/

// ------------------------------------------------------------------------
//	HybridAuth End Point
// ------------------------------------------------------------------------
require_once( '../../kernel/includes/setup_inc.php' );
require_once( EXTERNAL_LIBS_PATH . 'facebook/src/Facebook/autoload.php' );

require_once( "Hybrid/Auth.php" );
require_once( "Hybrid/Endpoint.php" );

try {
	Hybrid_Endpoint::process();
} catch( Exception $e ) {
	$gBitSystem->fatalError( $e->getMessage(), NULL, NULL, HttpStatusCodes::HTTP_UNAUTHORIZED );
}
