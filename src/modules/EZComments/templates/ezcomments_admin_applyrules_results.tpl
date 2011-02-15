{include file="ezcomments_admin_menu.tpl"}
<div class="z-admincontainer">
    <div class="z-adminpageicon">{img modname='EZComments' src='admin.gif' alt='' }</div>
    <h2>{gt text="Re-apply moderation rules"}</h2>
    <form id="ezcomments_view" action="{modurl modname=EZComments type=admin func=applyrules}" method="post">
        <div>
            <input type="hidden" name="confirmation" value="2" />
            <input type="hidden" name="authid" value="{insert name='generateauthkey' module='EZComments'}" />
            <input type="hidden" name="allcomments" value="{$allcomments|safetext}" />
            <input type="hidden" name="status" value="{$status|safetext}" />
            <h3>{gt text="The following comments will become rejected"}</h3>
            <table class="z-admintable">
                <thead>
                    <tr>
                        <th>{gt text="Status"}</th>
                        <th>{gt text="Commentator"}</th>
                        <th>{gt text="Module"}</th>
                        <th>{gt text="Comment type"}</th>
                        <th>{gt text="Comment"}</th>
                        <th>{gt text="Options"}</th>
                    </tr>
                </thead>
                <tbody>
                    {foreach from=$blacklistedcomments item=item}
                    <tr class="{cycle values=z-odd,z-even}">
                        <td>
                            {if $item.status eq 0}{img src='green.gif'}{/if}
                            {if $item.status eq 1}{img src='yellow.gif'}{/if}
                            {if $item.status eq 2}{img src='red.gif'}{/if}
                        </td>
                        <td>
                            {if $item.uid neq 0}
                            {usergetvar name=uname uid=$item.uid}
                            {else}
                            {$item.anonname|safetext}
                            {/if}
                            <br />{$item.date|dateformat}
                        </td>
                        <td><a href="{$item.url}">{$item.mod}</a></td>
                        <td>{$item.type|safetext|default:'&nbsp;'}</td>
                        <td>{$item.comment|strip_tags|truncate:20}</td>
                        <td>
                            {assign var="options" value=$item.options}
                            {strip}
                            [{section name=options loop=$options}
                            <a href="{$options[options].url|safetext}">{$options[options].title}</a>
                            {if !$smarty.section.options.last} | {/if}
                            {/section}]
                            {/strip}
                        </td>
                    </tr>
                    {foreachelse}
                    <tr class="z-admintableempty"><td colspan="6">{gt text="No items found"}</td></tr>
                    {/foreach}
                </tbody>
            </table>

            <h3>{gt text="The following comments will require moderation"}</h3>

            <table class="z-admintable">
                <thead>
                    <tr>
                        <th>{gt text="Status"}</th>
                        <th>{gt text="Commentator"}</th>
                        <th>{gt text="Module"}</th>
                        <th>{gt text="Comment type"}</th>
                        <th>{gt text="Comment"}</th>
                        <th>{gt text="Options"}</th>
                    </tr>
                </thead>
                <tbody>
                    {foreach from=$moderatedcomments item=item}
                    <tr class="{cycle values=z-odd,z-even}">
                        <td>
                            {if $item.status eq 0}{img src='green.gif'}{/if}
                            {if $item.status eq 1}{img src='yellow.gif'}{/if}
                            {if $item.status eq 2}{img src='red.gif'}{/if}
                        </td>
                        <td>
                            {if $item.uid neq 0}
                            {usergetvar name=uname uid=$item.uid}
                            {else}
                            {$item.anonname|safetext}
                            {/if}
                            <br />{$item.date|dateformat}
                        </td>
                        <td><a href="{$item.url}">{$item.mod}</a></td>
                        <td>{$item.type|safetext|default:'&nbsp;'}</td>
                        <td>{$item.comment|strip_tags|truncate:20}</td>
                        <td>
                            {assign var="options" value=$item.options}
                            {strip}
                            [{section name=options loop=$options}
                            <a href="{$options[options].url|safetext}">{$options[options].title}</a>
                            {if !$smarty.section.options.last} | {/if}
                            {/section}]
                            {/strip}
                        </td>
                    </tr>
                    {foreachelse}
                    <tr class="z-admintableempty"><td colspan="6">{gt text="No items found"}</td></tr>
                    {/foreach}
                </tbody>
            </table>

            <div class="z-formbuttons">
                {button src='button_ok.png' set='icons/small' __alt='Apply' __title='Apply'}
                <a href="{modurl modname='EZComments' type='admin' func='main'}">{img modname='core' src='button_cancel.png' set='icons/small' __alt='Cancel' __title='Cancel'}</a>
            </div>
        </div>
    </form>
</div>