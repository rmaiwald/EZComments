{include file="ezcomments_admin_menu.tpl"}
<div class="z-admincontainer">
    <div class="z-adminpageicon">{img modname='EZComments' src='admin.gif' alt='' }</div>
    <h2>{gt text="Settings"}</h2>
    {form cssClass="z-form"}
    {formvalidationsummary}
    <fieldset>
        <legend>{gt text="Miscellaneous"}</legend>
        <div class="z-formrow">
            {formlabel for="ezcomments_template" __text='Default template'}
            {formdropdownlist id="ezcomments_template" items=$templates selectedValue=$pncore.EZComments.template}
        </div>
        <div class="z-formrow">
            {formlabel for="ezcomments_css" __text='Default stylesheet'}
            {formtextinput id="ezcomments_css" text=$pncore.EZComments.css|safetext size="30" maxLength="30"}
            <p class="z-informationmsg z-formnote">
                {gt text="By default, the following possibilities exists:"}<br />
                <strong>Standard:</strong> style1.css, style2.css, style3.css, style4.css<br />
                <strong>Dizkus:</strong> style.css
            </p>
        </div>
        <div class="z-formrow">
            {formlabel for="ezcomments_anonusersinfo" __text='Allow unregistered users to set user information'}
            {formcheckbox id="ezcomments_anonusersinfo" checked=$pncore.EZComments.anonusersinfo}
        </div>
        <div class="z-formrow">
            {formlabel for="ezcomments_anonusersrequirename" __text='Require name for unregistered user'}
            {formcheckbox id="ezcomments_anonusersrequirename" checked=$pncore.EZComments.anonusersrequirename}
        </div>
        <div class="z-formrow">
            {formlabel for="ezcomments_logip" __text='Log IP addresses'}
            {formcheckbox id="ezcomments_logip" checked=$pncore.EZComments.logip}
        </div>
        <div class="z-formrow">
            {formlabel for="ezcomments_itemsperpage" __text='Comments per page in admin view'}
            {formtextinput id="ezcomments_itemsperpage" text=$pncore.EZComments.itemsperpage|safetext size="5" maxLength="5"}
        </div>
        <div class="z-formrow">
            {formlabel for="ezcomments_enablepager" __text='Enable pager in user view'}
            {formcheckbox id="ezcomments_enablepager" checked=$pncore.EZComments.enablepager}
        </div>
        <div class="z-formrow">
            {formlabel for="ezcomments_commentsperpage" __text='Comments per page in user view'}
            {formtextinput id="ezcomments_commentsperpage" text=$pncore.EZComments.commentsperpage|safetext size="5" maxLength="5"}
        </div>
        <div class="z-formrow">
            {formlabel for="ezcomments_useaccountpage" __text='Activate account page in user section'}
            {formcheckbox id="ezcomments_useaccountpage" checked=$pncore.EZComments.useaccountpage}
        </div>
    </fieldset>
    <fieldset>
        <legend>{gt text="Notification"}</legend>
        <p class="z-formnote z-informationmsg">{gt text="The notification email will be sent to the owner of the content. If there is no owner known, the notification mail will be sent to the site administrator."}</p>
        <div class="z-formrow">
            {formlabel for="ezcomments_MailToAdmin" __text='Send mail on new comment'}
            {formcheckbox id="ezcomments_MailToAdmin" checked=$pncore.EZComments.MailToAdmin}
        </div>
        <div class="z-formrow">
            {formlabel for="ezcomments_moderationmail" __text='Send mail on comment requiring moderation'}
            {formcheckbox id="ezcomments_moderationmail" checked=$pncore.EZComments.moderationmail}
        </div>
    </fieldset>
    <fieldset>
        <legend>{gt text="Moderation"}</legend>
        <div class="z-formrow">
            {formlabel for="ezcomments_moderation" __text='Enable comment moderation'}
            {formcheckbox id="ezcomments_moderation" checked=$pncore.EZComments.moderation}
        </div>
        <div class="z-formrow">
            {formlabel for="ezcomments_alwaysmoderate" __text='All comments require moderation'}
            {formcheckbox id="ezcomments_alwaysmoderate" checked=$pncore.EZComments.alwaysmoderate}
        </div>
        <div class="z-formrow">
            {formlabel for="ezcomments_dontmoderateifcommented" __text="Do not require moderation for comments from users who have already commented"}
            {formcheckbox id="ezcomments_dontmoderateifcommented" checked=$pncore.EZComments.dontmoderateifcommented}
        </div>
        <div class="z-formrow">
            {formlabel for="ezcomments_modlinkcount" __text='Number of links in comment before moderation'}
            {formtextinput id="ezcomments_modlinkcount" text=$pncore.EZComments.modlinkcount|safetext size="5" maxLength="5"}
        </div>
        <div class="z-formrow">
            {formlabel for="ezcomments_modlist" __text='Words to trigger moderation'}
            {formtextinput id="ezcomments_modlist" textMode="multiline" rows="5" cols="50" text=$pncore.EZComments.modlist|safetext}
        </div>
    </fieldset>
    <fieldset>
        <legend>{gt text="Blacklisting"}</legend>
        <div class="z-formrow">
            {formlabel for="ezcomments_blacklinkcount" __text='Number of links in comment before blacklisting'}
            {formtextinput id="ezcomments_blacklinkcount" text=$pncore.EZComments.blacklinkcount|safetext size="5" maxLength="5"}
        </div>
        <div class="z-formrow">
            {formlabel for="ezcomments_blacklist" __text='Words to blacklist from comments'}
            {formtextinput id="ezcomments_blacklist" textMode="multiline" rows="5" cols="50" text=$pncore.EZComments.blacklist|safetext}
            <em class="z-formnote z-sub">{gt text="Notice: Comments containing words listed here will be completely ignored by the comments module"}</em>
        </div>
        <div class="z-formrow">
            {formlabel for="ezcomments_proxyblacklist" __text='Blacklist comments from insecure proxies'}
            {formcheckbox id="ezcomments_proxyblacklist" checked=$pncore.EZComments.proxyblacklist}
        </div>
        <div class="z-formrow">
            {formlabel for="ezcomments_modifyowntime" __text='Number of hours where users are allowed to modify the text of own comments.'}
            {formintinput id="ezcomments_modifyowntime" text=$pncore.EZComments.modifyowntime minValue="-1"}
            <em class="z-formnote z-sub">{gt text='The value -1 deactivates this function.'}</em>
        </div>
    </fieldset>
    <fieldset>
        <legend>{gt text="Akismet spam dectection service"}</legend>
        <p class="z-formnote z-informationmsg">{gt text='Notice: <a href="http://akismet.com/">Akismet</a> is a spam detection service that can, in many cases, eliminate comment and trackback spam. To use Akismet you need to install and configure the <a href="http://code.zikula.org/ezcomments/">Akismet module</a>.'}</p>
        {if $akismetavailable}
        <div class="z-formrow">
            {formlabel for="ezcomments_akismet" __text='Enable akismet'}
            {formcheckbox id="ezcomments_akismet" checked=$pncore.EZComments.akismet}
        </div>
        <div class="z-formrow">
            {formlabel for="ezcomments_akismetstatus" __text='Status level to apply to comments flagged as spam by akismet'}
            {formdropdownlist id="ezcomments_akismetstatus" items=$statuslevels selectedValue=$pncore.EZComments.akismetstatus}
        </div>
        {/if}
    </fieldset>
    <fieldset>
        <legend>{gt text="Feeds"}</legend>
        <p class="z-formnote z-informationmsg">{gt text="Notice: both the feed type and feed count can be overriden using feedtype and feedcount parameters appended to the feed URL"}</p>
        <div class="z-formrow">
            {formlabel for="ezcomments_feedtype" __text='Type of feed'}
            {formdropdownlist id="ezcomments_feedtype" items=$feeds selectedValue=$pncore.EZComments.feedtype}
        </div>
        <div class="z-formrow">
            {formlabel for="ezcomments_feedcount" __text='Number of items to display in feed'}
            {formtextinput id="ezcomments_feedcount" text=$pncore.EZComments.feedcount|safetext size="5" maxLength="5"}
        </div>
    </fieldset>
    
    <div class="z-formbuttons">
        {formbutton id="submit" commandName="submit" __text="Submit"}
    </div>
    {/form}
</div>