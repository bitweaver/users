<?php
/**
 * $Header$
 *
 * $Id$
 * @package users
 * @subpackage functions
 */
global $gQueryUserId, $gBitSystem;

/**
 * required setup
 */
require_once( '../kernel/includes/setup_inc.php' );

$gBitSystem->setActivePackage( 'users' );

// custom userfields
if( $gBitSystem->getConfig( 'custom_user_fields' )) {
	$customFields= explode( ',', $gBitSystem->getConfig( 'custom_user_fields' ));
	$gBitSmarty->assign( 'customFields', $customFields );
}

// lookup may be via content_id which will then return user_id for search request
require_once( USERS_PKG_INCLUDE_PATH.'lookup_user_inc.php' );

// i think we should always allow looking at yourself - regardless of permissions
if( !empty( $_REQUEST['home'] ) ) {
	if( $gQueryUser->isValid() && (( $gBitUser->hasPermission( 'p_users_view_user_homepage' ) || $gBitUser->hasPermission( 'p_users_admin' )) || $gQueryUser->mUserId == $gBitUser->mUserId )) {
		$gQueryUserId = $gQueryUser->mUserId;
		if( !$gBitUser->hasPermission('p_user_admin') && $gQueryUser->isValid() ) {
			$gBitSmarty->assign( 'gQueryUserId', $gQueryUserId );
			$gQueryUser->verifyPermission( 'p_users_edit_user_homepage' );
		}

		if( $gBitSystem->isPackageActive('stars') && $gBitSystem->isFeatureActive('stars_user_ratings')) {
			require( STARS_PKG_PATH."templates/user_ratings.php" );
		}

		$gBitSystem->setCanonicalLink( $gQueryUser->getDisplayUrl() );

		if( $gQueryUser->canCustomizeTheme() ) {
			$userHomeStyle = $gQueryUser->getPreference( 'theme' );
			if( !empty( $userHomeStyle )) {
				$gBitThemes->setStyle( $userHomeStyle );
				$gBitThemes->mStyles['styleSheet'] = $gBitThemes->getStyleCssFile( $userHomeStyle, $gQueryUser->mUserId );
				$gBitSmarty->assign( 'userStyle', $userHomeStyle );
			}
		}

		$userHomeTitle = $gQueryUser->getPreference( 'homepage_title' );
		if( empty( $userHomeTitle )) {
			$userHomeTitle = $gQueryUser->getDisplayName()."'s Homepage";
		}
		$browserTitle = $userHomeTitle;

		if( $gBitSystem->isFeatureActive( 'display_users_content_list' ) && $gBitUser->hasPermission( 'p_liberty_list_content' ) ) {

			// some content specific offsets and pagination settings
			if( !empty( $_REQUEST['sort_mode'] ) ) {
				$content_sort_mode = $_REQUEST['sort_mode'];
			}

			$max_content = $gBitSystem->getConfig( 'max_records' );

			// set the user_id to only display content viewing user
			$_REQUEST['user_id'] = $gQueryUserId;
			$gBitSmarty->assign( 'user_id', $gQueryUserId);

			// now that we have all the offsets, we can get the content list
			include_once( LIBERTY_PKG_INCLUDE_PATH.'get_content_list_inc.php' );

			//$gBitSmarty->assignByRef('offset', $offset);
			$gBitSmarty->assign( 'contentSelect', $contentSelect );
			$gBitSmarty->assign( 'contentTypes', $contentTypes );
			$gBitSmarty->assign( 'contentList', $contentList );

			// needed by pagination
			$contentListHash['listInfo']['ihash']['content_type_guid'] = $contentSelect[0];
			$contentListHash['listInfo']['ihash']['user_id'] = $gQueryUserId;
			$contentListHash['listInfo']['ihash']['find'] = $contentListHash['find'];

			$gBitSmarty->assign( 'listInfo', $contentListHash['listInfo'] );
			$gBitSmarty->assign( 'display_content_list', 1 );
			// end of content listing
		}

		$gQueryUser->invokeServices( 'content_display_function' );

		// need to load layout now that we can check for center pieces
		$layoutHash['layout'] = $gQueryUser->getField( 'login' );
		$layoutHash['fallback'] = TRUE;
		$layoutHash['fallback_layout'] = 'home';
		$gBitThemes->loadLayout( $layoutHash );
		$gBitSmarty->assign( 'pageCssId', 'userhomepage' );
		$centerDisplay = ( count( $gCenterPieces ) ? 'bitpackage:kernel/dynamic.tpl' : 'bitpackage:users/center_user_wiki_page.tpl' );
	} else {
		$gBitSmarty->assign( 'metaNoIndex', TRUE );
		$gBitSystem->fatalError( tra( "Page not found." ), NULL, NULL, HttpStatusCodes::HTTP_NOT_FOUND );
	}
} else {
	$gBitSystem->verifyPermission( 'p_users_view_user_list' );
	$users = $gQueryUser->getList( $_REQUEST );
	$gBitSmarty->assignByRef( 'users', $users );
	$gBitSmarty->assignByRef( 'usercount', $_REQUEST["cant"] );
	// display an error message
	if( !empty( $_REQUEST['home'] )) {
		$gBitSystem->setHttpStatus( HttpStatusCodes::HTTP_GONE );
		$feedback['error'] = tra( 'The following user could not be found' ).': '.$_REQUEST['home'];
		$gBitSmarty->assign( 'feedback', $feedback );
	}
	$_REQUEST['listInfo']["URL"] = USERS_PKG_URL."index.php";
	$gBitSmarty->assignByRef( 'control', $_REQUEST['listInfo'] );
	$gBitSmarty->assignByRef( 'listInfo', $_REQUEST['listInfo'] );
	$browserTitle = $gBitSystem->getConfig( 'site_title' ).' '.tra( 'Members' );
	$centerDisplay = 'bitpackage:users/index_list.tpl';
}

$gBitSmarty->assign( 'gBitLanguage', $gBitLanguage );
$gBitSystem->display( $centerDisplay, $browserTitle , array( 'display_mode' => 'display' ));
?>
