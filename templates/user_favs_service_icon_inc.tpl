{if $gContent && $gContent->isValid() && $gBitUser->isRegistered() && $gContent->hasService($smarty.const.CONTENT_SERVICE_USERS_FAVS) && $gBitThemes->isJavascriptEnabled()}
{strip}
{if $fav.content_id eq $gContent->mContentId && $gBitUser->getFavorites($gContent->mContentId)}
	{assign var=isBookmarked value='true'}
{else}
	{assign var=isBookmarked value='false'}
{/if}
<a title="{if $isBookmarked eq 'true'}{tr}Remove from your favorites{/tr}{else}{tr}Add to your favorites{/tr}{/if}" onclick="BitUser.toggleBookmark({$gContent->mContentId});" href="javascript:void(0); {* {$smarty.const.USERS_PKG_URL}bookmark.php?content_id={$gContent->mContentId} *}" >
	{if $isBookmarked eq 'true'}
		{booticon iname="icon-bookmark-empty" ipackage="icons" iexplain="Remove Bookmark"}
	{else}
		{booticon iname="icon-bookmark" ipackage="icons" iexplain="Bookmark"}
	{/if}
</a>
	<script type="text/javascript">/* <![CDATA[ */
		if( typeof( BitUser ) == 'undefined' ){ldelim} BitUser = {ldelim}{rdelim} {rdelim};
		BitUser.bookmarkUrl = "{$smarty.const.USERS_PKG_URL}bookmark.php";
		BitUser.isBookmarked = {$isBookmarked}; 
	{literal}
		BitUser.toggleBookmark = function( contentId ){
			var ajax = new BitBase.SimpleAjax();
			var query = 'content_id='+contentId+'&action='+(BitUser.isBookmarked?'remove':'add');
			ajax.connect( BitUser.bookmarkUrl, query, BitUser.postBookmark, "GET" );
		};
		BitUser.postBookmark = function( rslt ){
			var obj = eval( "(" + rslt.responseText + ")" );
			switch( obj.Status.code ){
			case 205:
				BitUser.isBookmarked = obj.Result.bookmark_state;
			case 400:
			case 401:
			default:
				break;
			}
			alert( obj.Status.message );
		};
	{/literal} /* ]]> */</script>
{*

     * var fnWhenDone = function ( pResponse ) {
     *       alert( pResponse.responseText );
     *     };
     *     var ajax = new BitBase.SimpleAjax();
     *     ajax.connect("mypage.php", "POST", "foo=bar&baz=qux", fnWhenDone);
     * };
	 *}
{/strip}
{/if}
