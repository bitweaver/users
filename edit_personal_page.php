<?php
/**
 * $Header: /cvsroot/bitweaver/_bit_users/edit_personal_page.php,v 1.10 2006/04/13 10:34:34 squareing Exp $
 *
 * Copyright (c) 2004 bitweaver.org
 * Copyright (c) 2003 tikwiki.org
 * Copyright (c) 2002-2003, Luis Argerich, Garland Foster, Eduardo Polidor, et. al.
 * All Rights Reserved. See copyright.txt for details and a complete list of authors.
 * Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details
 *
 * $Id: edit_personal_page.php,v 1.10 2006/04/13 10:34:34 squareing Exp $
 * @package users
 * @subpackage functions
 */

/**
 * required setup
 */
require_once( '../bit_setup_inc.php' );

// include plugin help
include_once( LIBERTY_PKG_PATH.'edit_help_inc.php' );

// Check if the page has changed
if (isset($_REQUEST["fSavePage"])) {
	$gBitUser->store( $_REQUEST );
	header( "Location:".USERS_PKG_URL."index.php?home=".$gBitUser->mUsername );
	die;
}

if ($gBitSystem->getConfig('package_quicktags','n') == 'y') {
	include_once( QUICKTAGS_PKG_PATH.'quicktags_inc.php' );
}

// see if we should show the attachments tab at all
foreach( $gLibertySystem->mPlugins as $plugin ) {
	if( ( $plugin['plugin_type'] == 'storage' ) && ( $plugin['is_active'] == 'y' ) ) {
		$gBitSmarty->assign( 'show_attachments','y' );
	}
}

$gBitSmarty->assign('preview',0);
// If we are in preview mode then preview it!
if(isset($_REQUEST["preview"])) {
	$gBitSmarty->assign('preview',1);
	$gBitUser->mInfo['title'] = $_REQUEST["title"];
	if(isset($_REQUEST["description"])) {
		$gBitUser->mInfo['description'] = $_REQUEST["description"];
	}
	$gBitUser->mInfo['data'] = $_REQUEST["edit"];

	$parsed = $gBitUser->parseData($_REQUEST["edit"], (!empty( $_REQUEST['format_guid'] ) ? $_REQUEST['format_guid'] :
		( isset($gBitUser->mInfo['format_guid']) ? $gBitUser->mInfo['format_guid'] : 'tikiwiki' ) ) );
	$gBitUser->mInfo['parsed_data'] = $parsed;
	/* SPELLCHECKING INITIAL ATTEMPT */
	//This nice function does all the job!
	$gBitSmarty->assign_by_ref( 'pageInfo', $gBitUser->mInfo );
}

$gBitSmarty->assign_by_ref( 'pageInfo', $gBitUser->mInfo );
$gBitSmarty->assign_by_ref( 'gContent', $gBitUser );

$gBitSmarty->assign('show_page_bar', 'y');
$gBitSystem->setPreference( 'wiki_description', 'n' );

$gBitSystem->display( 'bitpackage:wiki/edit_page.tpl');
?>
