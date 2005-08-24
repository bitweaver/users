{strip}

<div class="floaticon">{bithelp}</div>
<div class="display userpreferences">
	<div class="header">
		<h1>{tr}User Preferences{/tr}</h1>
	</div>

	{include file="bitpackage:users/my_bitweaver_bar.tpl"}

	<div class="body">
		{formfeedback warning=$warningMsg success=$successMsg error=$errorMsg}
		{jstabs}
			{jstab title="User Information"}
				{form legend="User Information"}
					<input type="hidden" name="view_user" value="{$editUser.user_id}" />

					<div class="row">
						{formlabel label="Real Name" for="real_name"}
						{forminput}
							<input type="text" name="real_name" id="real_name" value="{$editUser.real_name|escape}" />
							{if !$gBitSystemPrefs.display_name or $gBitSystemPrefs.display_name eq 'real_name'}
								{formhelp note="This is the name that is visible to other users when viewing information added by you."}
							{/if}
						{/forminput}
					</div>

					<div class="row">
						{formlabel label="Username"}
						{forminput}
							{$editUser.login}
							{if $gBitSystemPrefs.display_name eq 'login'}
								{formhelp note="This is the name that is visible to other users when viewing information added by you."}
							{/if}
						{/forminput}
					</div>

					<div class="row">
						{formlabel label="Last login"}
						{forminput}
							{$editUser.last_login|bit_long_datetime}
							{formhelp note=""}
						{/forminput}
					</div>

					<div class="row">
						{formlabel label="Is email public?" for="email_isPublic"}
						{forminput}
							<select name="email_isPublic" id="email_isPublic">
								{section name=ix loop=$scramblingMethods}
									<option value="{$scramblingMethods[ix]|escape}" {if $email_isPublic eq $scramblingMethods[ix]}selected="selected"{/if}>{$scramblingEmails[ix]}</option>
								{/section}
							</select>
							{formhelp note="Pick the scrambling method to prevent spam."}
						{/forminput}
					</div>

					<div class="row">
						{formlabel label="Country" for="country"}
						{forminput}
							{if $userPrefs.flag}{biticon iforce=icon ipackage="users" ipath="flags/" iname="`$userPrefs.flag`" iexplain="`$userPrefs.flag`"}{/if}
							<select name="country" id="country">
								<option value="" />
								{sortlinks}
									{section name=ix loop=$flags}  
										<option value="{$flags[ix]|escape}" {if $userPrefs.flag eq $flags[ix]}selected="selected"{/if}>{tr}{$flags[ix]}{/tr}</option>  
									{/section}
								{/sortlinks}
							</select>
							{formhelp note=""}
						{/forminput}
					</div>

					{if $change_language eq 'y'}
						<div class="row">
							{formlabel label="Language" for="language"}
							{forminput}
								<select name="language" id="language">
									{foreach from=$languages key=langCode item=lang}
										<option value="{$langCode}"{if $gBitLanguage->mLanguage eq $langCode} selected="selected"{/if}>
											{$lang.full_name}
										</option>
									{/foreach}
								</select>
								{formhelp note=""}
							{/forminput}
						</div>
					 {/if}

					{foreach from=$customFields key=i item=field}
						<div class="row">
							{formlabel label="$field}
							{forminput}
								<input type="text" name="CUSTOM[{$field}]" value="{$userPrefs.$field}" maxlength="250" />
							{/forminput}
						</div>
					{/foreach}

					<div class="row">
						{formlabel label="User information" for="user_information"}
						{forminput}
							<select name="user_information" id="user_information">
								<option value="private" {if $user_information eq 'private'}selected="selected"{/if}>{tr}private{/tr}</option>
								<option value="public" {if $user_information eq 'public'}selected="selected"{/if}>{tr}public{/tr}</option>
							</select>
							{formhelp note=""}
						{/forminput}
					</div>

					<div class="row">
						{formlabel label="Number of visited pages to remember" for="userbreadCrumb"}
						{forminput}
							<select name="userbreadCrumb" id="userbreadCrumb">
								<option value="1" {if $editUser.userbreadCrumb eq 1}selected="selected"{/if}>{tr}1{/tr}</option>
								<option value="2" {if $editUser.userbreadCrumb eq 2}selected="selected"{/if}>{tr}2{/tr}</option>
								<option value="3" {if $editUser.userbreadCrumb eq 3}selected="selected"{/if}>{tr}3{/tr}</option>
								<option value="4" {if $editUser.userbreadCrumb eq 4}selected="selected"{/if}>{tr}4{/tr}</option>
								<option value="5" {if $editUser.userbreadCrumb eq 5}selected="selected"{/if}>{tr}5{/tr}</option>
								<option value="10" {if $editUser.userbreadCrumb eq 10}selected="selected"{/if}>{tr}10{/tr}</option>
							</select>
							{formhelp note=""}
						{/forminput}
					</div>

					<div class="row">
						{formlabel label="HomePage" for="homePage"}
						{forminput}
							<input size="50" type="text" name="homePage" id="homePage" value="{$editUser.homePage|escape}" />
							{formhelp note=""}
						{/forminput}
					</div>

					<div class="row">
						{formlabel label="Displayed time zone"}
						{forminput}
							<label><input type="radio" name="display_timezone" value="UTC" {if $display_timezone eq 'UTC'}checked="checked"{/if} />{tr}UTC{/tr}</label>
							<br />
							<label><input type="radio" name="display_timezone" value="Local" {if $display_timezone ne 'UTC'}checked="checked"{/if} />{tr}Local{/tr}</label>
							{formhelp note=""}
						{/forminput}
					</div>

					<div class="row">
						{formlabel label="Use double-click to edit pages" for="user_dbl"}
						{forminput}
							<input type="checkbox" name="user_dbl" id="user_dbl" {if $user_dbl eq 'y'}checked="checked"{/if} />
							{formhelp note="Enabling this feature will allow you to double click on any wiki page and it will automatically take you to the edit page. Note that this does not work in all browsers."}
						{/forminput}
					</div>

					<div class="row submit">
						<input type="submit" name="prefs" value="{tr}Change preferences{/tr}" />
					</div>
				{/form}
			{/jstab}

			{jstab title="Pictures and Icons"}
				{legend legend="Pictures and Icons"}
					<div class="row">
						{formlabel label="Pictures"}
						{forminput}
							<a href="{$smarty.const.USERS_PKG_URL}my_images.php">{tr}Upload new pictures{/tr}</a>
							{formhelp note=""}
						{/forminput}
					</div>

					<div class="row">
						{formlabel label="Avatar"}
						{forminput}
							{if $editUser.avatar_url}
								<img src="{$editUser.avatar_url}" />
							{/if}
							{formhelp note="Small icon used for your posts or comments."}
						{/forminput}
					</div>

					<div class="row">
						{formlabel label="Self Portrait"} {forminput}
							{if $editUser.portrait_url}
								<img src="{$editUser.portrait_url}" />
							{/if}
							{formhelp note="Larger picture used on your bio page."}
						{/forminput}
					</div>

					<div class="row">
						{formlabel label="Logo" for=""}
						{forminput}
							{if $editUser.logo_url}
								<img src="{$editUser.logo_url}" /><br />
							{/if}
							{formhelp note="Image used for your organization."}
						{/forminput}
					</div>
				{/legend}
			{/jstab}

			{jstab title="e-mail"}
				{form legend="Change your email address"}
					<input type="hidden" name="view_user" value="{$editUser.user_id}" />
					<div class="row">
						{formlabel label="Email" for="email"}
						{forminput}
							<input size="50" type="text" name="email" id="email" value="{$editUser.email|escape}" />
							{formhelp note=""}
						{/forminput}
					</div>

					<div class="row">
						{formlabel label="Password" for="pass"}
						{forminput}
							<input type="password" name="pass" id="pass" />
							{formhelp note=""}
						{/forminput}
					</div>

					<div class="row submit">
						<input type="submit" name="chgemail" value="{tr}change email{/tr}" />
					</div>
				{/form}
			{/jstab}

			{jstab title="Password"}
				{form legend="Change your password"}
					<input type="hidden" name="view_user" value="{$editUser.user_id}" />

					{if !$view_user or ( $gBitUser->hasPermission('bit_p_admin_users') and $view_user )}
						<div class="row">
							{formlabel label="Old password" for="old"}
							{forminput}
								<input type="password" name="old" id="old" />
								{formhelp note=""}
							{/forminput}
						</div>
					{else}
						<input type="hidden" name="old" value="" />
					{/if}

					<div class="row">
						{formlabel label="New password" for="pass1"}
						{forminput}
							<input type="password" name="pass1" id="pass1" />
							{formhelp note=""}
						{/forminput}
					</div>

					<div class="row">
						{formlabel label="Again please" for="pass2"}
						{forminput}
							<input type="password" name="pass2" id="pass2" />
							{formhelp note=""}
						{/forminput}
					</div>

					<div class="row submit">
						<input type="submit" name="chgpswd" value="{tr}change password{/tr}" />
					</div>
				{/form}
			{/jstab}

			{if $gBitSystem->isPackageActive( 'messu' )}
				{jstab title="User Messages"}
					{include file='bitpackage:messu/messu_preferences_inc.tpl'}
				{/jstab}
			{/if}

			{if $gBitSystem->isPackageActive( 'calendar' )}
				{jstab title="Calendar"}
				{debug}
					{include file='bitpackage:calendar/calendar_preferences_inc.tpl' settings=$userPrefs}
				{/jstab}
			{/if}

			{if $gBitSystem->isFeatureActive( 'feature_tasks' )}
				{jstab title="User Tasks"}
					{form legend="User Tasks"}
						<div class="row">
							{formlabel label="Tasks per page" for="tasks_maxRecords"}
							{forminput}
								<select name="tasks_maxRecords" id="tasks_maxRecords">
									<option value="2" {if $tasks_maxRecords eq 2}selected="selected"{/if}>{tr}2{/tr}</option>
									<option value="5" {if $tasks_maxRecords eq 5}selected="selected"{/if}>{tr}5{/tr}</option>
									<option value="10" {if $tasks_maxRecords eq 10}selected="selected"{/if}>{tr}10{/tr}</option>
									<option value="20" {if $tasks_maxRecords eq 20}selected="selected"{/if}>{tr}20{/tr}</option>
									<option value="30" {if $tasks_maxRecords eq 30}selected="selected"{/if}>{tr}30{/tr}</option>
									<option value="40" {if $tasks_maxRecords eq 40}selected="selected"{/if}>{tr}40{/tr}</option>
									<option value="50" {if $tasks_maxRecords eq 50}selected="selected"{/if}>{tr}50{/tr}</option>
								</select>
								{formhelp note=""}
							{/forminput}
						</div>

						<div class="row">
							{formlabel label="Use dates" for="tasks_use_dates"}
							{forminput}
								<input type="checkbox" name="tasks_use_dates" id="tasks_use_dates" {if $tasks_use_dates eq 'y'}checked="checked"{/if} />
								{formhelp note=""}
							{/forminput}
						</div>

						<div class="row submit">
							<input type="submit" name="tasksprefs" value="{tr}Change preferences{/tr}" />
						</div>
					{/form}
				{/jstab}
			{/if}
		{/jstabs}
	</div><!-- end .body -->
</div><!-- end .userpreferences -->

{/strip}
