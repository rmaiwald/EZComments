{include file="ezcomments_admin_menu.tpl"}
<div class="z-admincontainer">
    <div class="z-adminpageicon">{img modname='EZComments' src='admin.gif' alt='' }</div>
    <h2>{gt text="Delete all comments for this module"}</h2>
    <p class="z-warningmsg">{gt text="This functionality allows you to delete comments that are in the database for removed modules."}</p>
    <form class="z-form" action="{modurl modname="EZComments" type="admin" func="cleanup_go"}" method="post" enctype="application/x-www-form-urlencoded">
        <div>
            <input type="hidden" name="authid" value="{insert name='generateauthkey' module='EZComments'}" />
            <fieldset>
                <legend>{gt text="Confirmation prompt"}</legend>
                <div class="z-formrow">
                    <label for="ezcomments_module">{gt text="Select module"}</label>
                    <select id="ezcomments_module" name="ezcomments_module">{html_options options=$selectitems}</select>
                </div>
            </fieldset>
            <div class="z-buttons z-formbuttons">
                {button src='button_ok.png' set='icons/small' __alt='Delete' __title='Delete'}
                <a href="{modurl modname='EZComments' type='admin' func='main'}">{img modname='core' src='button_cancel.png' set='icons/small' __alt='Cancel' __title='Cancel'}</a>
            </div>
        </div>
    </form>
</div>
