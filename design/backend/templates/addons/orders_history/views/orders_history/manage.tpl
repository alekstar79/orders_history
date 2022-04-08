{capture name="mainbox"}

{capture name="sidebar"}
    {hook name="orders_history:manage_sidebar"}
        {include file="addons/orders_history/views/orders_history/components/sidebar.tpl"}
    {/hook}
{/capture}

{include file="common/pagination.tpl"}

<div class="cm-pagination-container" id="pagination_contents">
    {if $orders_history}
        <div class="table-responsive-wrapper longtap-selection">
            <table class="table table--relative table-responsive">
                <thead>
                    <tr>
                        <th>{__("order")}</th>
                        <th>{__("orders_history_status_old")}</th>
                        <th>{__("orders_history_status_new")}</th>
                        <th>{__("user")}</th>
                        <th>{__("time")}</th>
                    </tr>
                </thead>
                <tbody>
                {foreach from=$orders_history item="log"}
                    {assign var="_action" value="`$log.action`"}
                    <tr>
                        <td class="wrap" data-th="{__("content")}">
                            <a href="{"orders.details?order_id=`$log.order_id`"|fn_url}">
                                #{$log.order_id}
                            </a>
                        </td>

                        <td class="wrap" data-th="{__("status_old")}">
                            {$log.status_old|fn_orders_history_status}
                        </td>

                        <td class="wrap" data-th="{__("status_new")}">
                            {$log.status_new|fn_orders_history_status}
                        </td>

                        <td data-th="{__("user")}">
                            {if $log.user_id}
                                <a href="{"profiles.update?user_id=`$log.user_id`"|fn_url}">
                                    {$log.lastname}{if $log.firstname || $log.lastname}&nbsp;{/if}{$log.firstname}
                                </a>
                            {else}
                                &mdash;
                            {/if}
                        </td>

                        <td data-th="{__("time")}">
                            <span class="nowrap">
                                {$log.timestamp|date_format:"`$settings.Appearance.date_format`, `$settings.Appearance.time_format`"}
                            </span>
                        </td>
                    </tr>
                {/foreach}
                </tbody>
            </table>
        </div>
    {else}
        <p class="no-items">{__("no_data")}</p>
    {/if}
</div>

{include file="common/pagination.tpl"}

{/capture}

{include file="common/mainbox.tpl"
    title=__("orders_history")
    sidebar=$smarty.capture.sidebar
    content=$smarty.capture.mainbox
}
