<?php

global $gApi;

$gApi->registerRoute( USERS_PKG_DIR, 'bituser_api_handler' );

function bituser_api_handler( $pMethod, $pRequest ) {
	global $gApi, $gBitSystem, $gBitSmarty, $gBitUser;

	switch( $pMethod ) {
		case 'POST':
//		case 'PUT':
			$newUser = new BitUser();
			if( $newUser->register( $pRequest ) ) {
				$gApi->outputJson( 200, $newUser->mInfo );
			} else {
				$gApi->outputJson( HttpStatusCodes::HTTP_CONFLICT, $newUser->mErrors );
			}
			break;

		case 'GET':
			$gApi->verifyAuthorization();
			$gContent = &$gBitUser;
			$gContent->verifyViewPermission();
			$gApi->outputJson( HttpStatusCodes::HTTP_OK, $gContent->mInfo );
			break;
		
		case 'DELETE':
			$gApi->verifyAuthorization();
			if( $gContent->hasAdminPermission() ) {
//	if( is_a( $gContent, 'BitUser' ) && $gContent->isValid() ) {
				$gContent->verifyExpungePermission();
//  }
			}
			break;
	}

	$gBitSystem->display( 'bitpackage:api/api_json_output.tpl' );
}
