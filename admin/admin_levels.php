<?php
/**
 * $Header: /cvsroot/bitweaver/_bit_users/admin/Attic/admin_levels.php,v 1.1 2007/06/15 18:27:35 squareing Exp $
 * @package users
 */
require_once( '../../bit_setup_inc.php' );

$gBitSystem->verifyPermission( 'p_users_admin' );
$allPerms = $gBitUser->getGroupPermissions( NULL, NULL, NULL, !empty( $_REQUEST['sort_mode'] ) ? $_REQUEST['sort_mode'] : NULL );

if( !empty( $_REQUEST['allper'] )) {
	if( $_REQUEST['oper'] == 'assign' ) {
		$gBitUser->assignLevelPermission( $_REQUEST['group_id'], $_REQUEST['perm_level'] );
	} else {
		$gBitUser->removeLevelPermissions( $_REQUEST['group_id'], $_REQUEST['perm_level'] );
	}
} elseif( !empty( $_REQUEST["createlevel"] )) {
	$gBitUser->createDummyLevel( $_REQUEST['perm_level'] );
} elseif( !empty( $_REQUEST["updatelevels"] )) {
	// This is used to assign levels to individual permissions
	if( !empty( $_REQUEST['perm_level'] )) {
		foreach( array_keys( $_REQUEST['perm_level'] ) as $perm ) {
			if( $allPerms[$perm]['perm_level'] != $_REQUEST['perm_level'][$perm] ) {
				// we changed level. perm[] checkbox is not taken into account
				$gBitUser->changePermissionLevel( $perm, $_REQUEST['perm_level'][$perm] );
			}
		}
	}
	// get up to date version of levels
	$allPerms = $gBitUser->getGroupPermissions( NULL, NULL, NULL, !empty( $_REQUEST['sort_mode'] ) ? $_REQUEST['sort_mode'] : NULL );
}

$gBitSmarty->assign_by_ref( 'allPerms', $allPerms );
$gBitSmarty->assign( 'levels', $gBitUser->getPermissionLevels() );

$gBitSystem->display( 'bitpackage:users/admin_levels.tpl', tra( 'Edit permission levels' ));
?>
