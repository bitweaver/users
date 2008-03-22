<?php
/**
 * $Header: /cvsroot/bitweaver/_bit_users/index.php,v 1.26 2008/03/22 19:07:43 jht001 Exp $
 *
 * $Id: index.php,v 1.26 2008/03/22 19:07:43 jht001 Exp $
 * @package users
 * @subpackage functions
 */
global $gQueryUserId, $gBitSystem;

/**
 * required setup
 */
// Todo: use a different $_SERVER variable to properly determine the active package
if( !defined( 'ACTIVE_PACKAGE' )) {
	define( 'ACTIVE_PACKAGE', 'users' );
}

require_once( '../bit_setup_inc.php' );
require_once( LIBERTY_PKG_PATH."LibertyStructure.php" );

// custom userfields
if( $gBitSystem->getConfig( 'custom_user_fields' )) {
	$customFields= explode( ',', $gBitSystem->getConfig( 'custom_user_fields' ));
	$gBitSmarty->assign( 'customFields', $customFields );
}

// lookup may be via content_id which will then return user_id for search request
require_once( USERS_PKG_PATH.'lookup_user_inc.php' );

// i think we should always allow looking at yourself - regardless of permissions
if( !empty( $_REQUEST['home'] ) && $gQueryUser->isValid() && (( $gBitUser->hasPermission( 'p_users_view_user_homepage' ) || $gBitUser->hasPermission( 'p_users_admin' )) || $gQueryUser->mUserId == $gBitUser->mUserId )) {
	$gQueryUserId = $gQueryUser->mUserId;
	if( $gQueryUser->isValid() ) {
		$gBitSmarty->assign( 'gQueryUserId', $gQueryUserId );
	}

	if( $gBitSystem->isPackageActive('stars') && $gBitSystem->isFeatureActive('stars_user_ratings')) {
		require( STARS_PKG_PATH."templates/user_ratings.php" );
	}

	if( $gQueryUser->canCustomizeTheme() ) {
		$userHomeStyle = $gQueryUser->getPreference( 'theme' );
		if( !empty( $userHomeStyle )) {
			$gBitThemes->setStyle( $userHomeStyle );
			$gBitThemes->mStyles['styleSheet'] = $gBitThemes->getStyleCss( $userHomeStyle, $gQueryUser->mUserId );
			$gBitSmarty->assign( 'userStyle', $userHomeStyle );
		}
	}

	$userHomeTitle = $gQueryUser->getPreference( 'homepage_title' );
	if( empty( $userHomeTitle )) {
		$userHomeTitle = $gQueryUser->getDisplayName()."'s Homepage";
	}
	$browserTitle = $userHomeTitle;


	if( $gBitSystem->verifyPermission( 'p_liberty_list_content' ) ) {
		// some content specific offsets and pagination settings
		if( !empty( $_REQUEST['sort_mode'] ) ) {
			$content_sort_mode = $_REQUEST['sort_mode'];
			$gBitSmarty->assign( 'sort_mode', $content_sort_mode );
		}

		$max_content = $gBitSystem->getConfig( 'max_records' );
		$offset_content = !empty( $_REQUEST['offset'] ) ? $_REQUEST['offset'] : 0;
		$gBitSmarty->assign( 'curPage', $page = !empty( $_REQUEST['page'] ) ? $_REQUEST['page'] : 1 );
		$offset_content = ( $page - 1 ) * $gBitSystem->getConfig( 'max_records' );

		// set the user_id to only display content viewing user
		$_REQUEST['user_id'] = $gBitUser->mUserId;
		$_REQUEST['user_id'] = $gQueryUserId;
		$gBitSmarty->assign( 'user_id', $gQueryUserId);

		// now that we have all the offsets, we can get the content list
		include_once( LIBERTY_PKG_PATH.'get_content_list_inc.php' );

		// calculate page number
		$numPages = ceil( $contentList['cant'] / $gBitSystem->getConfig( 'max_records' ) );
		$gBitSmarty->assign( 'numPages', $numPages );

		//$gBitSmarty->assign_by_ref('offset', $offset);
		$gBitSmarty->assign( 'contentSelect', $contentSelect );
		$gBitSmarty->assign( 'contentTypes', $contentTypes );
		$gBitSmarty->assign( 'contentList', $contentList['data'] );
		$gBitSmarty->assign( 'contentCount', $contentList['cant'] );

		// needed by pagination
                $contentList['listInfo']['parameters']['content_type_guid'] = $contentSelect;
		$contentList['listInfo']['parameters']['user_id'] = $gQueryUserId;
                                
		$gBitSmarty->assign( 'listInfo', $contentList['listInfo'] );
		$gBitSmarty->assign( 'display_content_list', 1 );
		// end of content listing
	}



	// need to load layout now that we can check for center pieces
	$layoutHash['layout'] = $gQueryUser->getField( 'login' );
	$layoutHash['fallback'] = TRUE;
	$layoutHash['fallback_layout'] = 'home';
	$gBitThemes->loadLayout( $layoutHash );
	$gBitSmarty->assign( 'pageCssId', 'userhomepage' );
	$centerDisplay = ( count( $gCenterPieces ) ? 'bitpackage:kernel/dynamic.tpl' : 'bitpackage:users/center_user_wiki_page.tpl' );

} else {
	$gBitSystem->verifyPermission( 'p_users_view_user_list' );
	$gQueryUser->getList( $_REQUEST );
	$gBitSmarty->assign_by_ref( 'users', $_REQUEST["data"] );
	$gBitSmarty->assign_by_ref( 'usercount', $_REQUEST["cant"] );
	// display an error message
	if( !empty( $_REQUEST['home'] )) {
		$feedback['error'] = tra( 'The following user could not be found' ).': '.$_REQUEST['home'];
		$gBitSmarty->assign( 'feedback', $feedback );
	}
	$_REQUEST['listInfo']["URL"] = USERS_PKG_URL."index.php";
	$gBitSmarty->assign_by_ref( 'control', $_REQUEST['listInfo'] );
	$gBitSmarty->assign_by_ref( 'listInfo', $_REQUEST['listInfo'] );
	$browserTitle = $gBitSystem->getConfig( 'site_title' ).' '.tra( 'Members' );
	$centerDisplay = 'bitpackage:users/index_list.tpl';
}

$gBitSmarty->assign( 'gBitLanguage', $gBitLanguage );
$gBitSystem->display( $centerDisplay, $browserTitle );
?>
