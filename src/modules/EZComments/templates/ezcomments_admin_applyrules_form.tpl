{include file="ezcomments_admin_menu.tpl"}
<div class="z-admincontainer">
    <div class="z-adminpageicon">{img modname='EZComments' src='admin.gif' alt='' }</div>
    <h2>{gt text="Re-apply moderation rules"}</h2>
    <p class="z-informationmsg">{gt text="This page allows to to re-apply the defined moderation rules to all existing comments allowing all comments to be re-checked if you change your moderation rules."}</p>
    <form class="z-form" action="{modurl modname='EZComments' type='admin' func='applyrules'}" method="post" enctype="application/x-www-form-urlencoded">
        <div>
            <input type="hidden" name="authid" value="{insert name='generateauthkey' module='EZComments'}" />
            <input type="hidden" name="confirmation" value="1" />
            <fieldset>
                <legend>{gt text="Choose action"}</legend>
                <div class="z-formrow">
                    <label for="ezcomments_allcomments">{gt text="Apply moderation rules to all comments"}</label>
                    <input id="ezcomments_allcomments" name="allcomments" type="checkbox" value="1" />
                </div>
                <div class="z-formrow">
                    <span class="z-label">{gt text="or"}</span>
                </div>
                <div class="z-formrow">
                    <label for="ezcomments_status">{gt text="Apply moderation rules to all comments with status"}</label>
                    <select id="ezcomments_status" name="status">
                        {html_options options=$statuslevels}
                    </select>
                </div>
            </fieldset>
            <div class="z-buttons z-formbuttons">
                {button src='button_ok.png' set='icons/small' __alt='Submit' __title='Submit'}
                <a href="{modurl modname='EZComments' type='admin' func='main'}">{img modname='core' src='button_cancel.png' set='icons/small' __alt='Cancel' __title='Cancel'}</a>
            </div>
        </div>
    </form>
</div>
