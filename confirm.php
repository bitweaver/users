<?php
/**
 * $Header: /cvsroot/bitweaver/_bit_users/confirm.php,v 1.3 2005/08/01 18:42:02 squareing Exp $
 *
 * Copyright (c) 2004 bitweaver.org
 * Copyright (c) 2003 tikwiki.org
 * Copyright (c) 2002-2003, Luis Argerich, Garland Foster, Eduardo Polidor, et. al.
 * All Rights Reserved. See copyright.txt for details and a complete list of authors.
 * Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details
 *
 * $Id: confirm.php,v 1.3 2005/08/01 18:42:02 squareing Exp $
 * @package users
 * @subpackage functions
 */

/**
 * required setup
 */
require_once( '../bit_setup_inc.php' );

if( $userInfo = $gBitUser->confirmRegistration( $_REQUEST["user"], $_REQUEST["pass"] ) ) {
	$gBitSmarty->assign_by_ref( 'userInfo', $userInfo );
	$gBitSystem->display( 'bitpackage:users/change_password.tpl' );
} else {
	$gBitSmarty->assign('msg', tra("Invalid username or password"));
	$gBitSystem->display( 'error.tpl' );
}
?>
