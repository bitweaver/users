<?php
/**
 * @version  $Revision$
 * @package  liberty
 * @subpackage plugins_data
 * @author bigwasp bigwasp@sourceforge.net
 */
// +----------------------------------------------------------------------+
// | Copyright (c) 2005, bitweaver.org
// +----------------------------------------------------------------------+
// | All Rights Reserved. See below for details and a complete list of authors.
// | Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See http://www.gnu.org/copyleft/lesser.html for details
// |
// | For comments, please use phpdocu.sourceforge.net documentation standards!!!
// | -> see http://phpdocu.sourceforge.net/
// +----------------------------------------------------------------------+
// | Author (TikiWiki): Damian Parker <damosoft@users.sourceforge.net>
// | Reworked & Undoubtedly Screwed-Up for (Bitweaver)
// | by: StarRider <starrrider@sourceforge.net>
// | Reworked from: wikiplugin_usercount.php - see deprecated code below
// +----------------------------------------------------------------------+
// $Id$

/**
 * definitions
 */
define( 'PLUGIN_GUID_DATAUSERLINK', 'datauserlink' );
global $gLibertySystem;
$pluginParams = array (
	'tag' => 'USERLINK',
	'auto_activate' => FALSE,
	'requires_pair' => FALSE,
	'load_function' => 'data_userlink',
	'title' => 'UserLink per login or email',
	'help_page' => 'DataPluginUserLink',
	'description' => tra("Will show a link to the userpage for a given login name or email."),
	'help_function' => 'data_userlink_help',
	'syntax' => "{USERLINK login='bigwasp'}",
	'plugin_type' => DATA_PLUGIN
);
$gLibertySystem->registerPlugin( PLUGIN_GUID_DATAUSERLINK, $pluginParams );
$gLibertySystem->registerDataTag( $pluginParams['tag'], PLUGIN_GUID_DATAUSERLINK );

// Help Function
function data_userlink_help() {
	$help =
	        '<table class="data help">'
		        .'<tr>'
			        .'<th>' . tra( "Key" ) . '</th>'
				.'<th>' . tra( "Type" ) . '</th>'
				.'<th>' . tra( "Comments") . '</th>'
			.'</tr>'
			.'<tr class="odd">'
			        .'<td>login</td>'
			        .'<td>' . tra( "string" ) . '<br />' . tra( "(optional)") . '</td>'
				.'<td>' . tra( "The login name to generate the link" ) . '</td>'
			.'</tr>'
			.'<tr class="even">'
			        .'<td>email</td>'
				.'<td>' . tra( "string" ) . '<br />' . tra( "(optional)") . '</td>'
				.'<td>' . tra( "The e-mail address to generate the link" ) . '</td>'
			.'</tr>'
			.'<tr class="odd">'
			        .'<td>label</td>'
				.'<td>' . tra( "string" ) . '<br />' . tra( "(optional)") . '</td>'
				.'<td>' . tra( "The label to show; default is user's name" ) . '</td>'
			.'</tr>'
		.'</table>'
		.tra("Example: ") . "{USERLINK login='admin' label='Site Administrator'}";
	return $help;
}

// Load Function
function data_userlink($data, $params) {
	global $gBitUser;

	$myHash = array();
	$ret = '';
	extract ($params, EXTR_SKIP);

	if ( isset( $login ) ) {
		$myHash['login'] = $login;
	} else if ( isset( $email ) ) {
		$myHash['email'] = $email;
	} else if ( isset( $user_id ) ) {
		$myHash['user_id'] = $user_id;
	}

	$user = $gBitUser->userExists($myHash);

	if( $user != Null ) {
		$tmpUser = $gBitUser->getUserInfo( array( 'user_id' => $user ) );
		if ( isset( $label ) ) {
			$tmpUser['link_label'] = $label;
		}
		$ret = $gBitUser->getDisplayName( TRUE, $tmpUser );
	}
	return $ret;
}
?>
