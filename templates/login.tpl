{strip}
<div class="width50p floatleft display login">
	<div class="body">

		{include file=bitpackage:users/login_inc.tpl}

		<ul>
			{if $gBitSystem->isFeatureActive('users_forgot_pass')}
				<li><a href="{$smarty.const.USERS_PKG_URL}remind_password.php">{tr}Retrieve your password.{/tr}</a></li>
			{/if}

			{if $gBitSystem->isFeatureActive('users_allow_register')}
				<li><a href="{$smarty.const.USERS_PKG_URL}register.php">{tr}Register with us.{/tr}</a></li>
			{/if}
		</ul>
	</div><!-- end .body -->
</div><!-- end .login -->
{/strip}
