{strip}
{if $packageMenuTitle}<a href="#"> {tr}{$packageMenuTitle}{/tr}</a>{/if}
<ul class="{$packageMenuClass}">
	<li><a class="item" href="{$smarty.const.USERS_PKG_URL}admin/">{tr}Edit Users{/tr}</a></li>
	<li><a class="item" href="{$smarty.const.KERNEL_PKG_URL}admin/?page=users">{tr}User Settings{/tr}</a></li>
		{if $gBitSystem->isPackageActive('tidbits')}
			<li><a class="item" href="{$smarty.const.KERNEL_PKG_URL}admin/?page=tidbits">{tr}Users Tidbits{/tr}</a></li>
		{/if}
	<li><a class="item" href="{$smarty.const.KERNEL_PKG_URL}admin/?page=login">{tr}Login Options{/tr}</a></li>
	<li><a class="item" href="{$smarty.const.USERS_PKG_URL}admin/user_activity.php">{tr}User Activity{/tr}</a></li>
	<li><a class="item" href="{$smarty.const.USERS_PKG_URL}admin/users_import.php">{tr}Import Users{/tr}</a></li>
		{if $gBitSystem->isPackageActive('protector')}
			<li><a class="item" href="{$smarty.const.USERS_PKG_URL}admin/edit_role.php">{tr}Role &amp; Permissions{/tr}</a></li>
			<li><a class="item" href="{$smarty.const.USERS_PKG_URL}admin/role_permissions.php">{tr}Permission Maintenance{/tr}</a></li>
		{else}
			<li><a class="item" href="{$smarty.const.USERS_PKG_URL}admin/edit_group.php">{tr}Groups &amp; Permissions{/tr}</a></li>
			<li><a class="item" href="{$smarty.const.USERS_PKG_URL}admin/permissions.php">{tr}Permission Maintenance{/tr}</a></li>
		{/if}
</ul>
{/strip}
