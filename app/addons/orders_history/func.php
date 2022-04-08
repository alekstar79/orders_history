<?php
/***************************************************************************
*                                                                          *
*   (c) 2004 Vladimir V. Kalynyak, Alexey V. Vinokurov, Ilya M. Shalnev    *
*                                                                          *
* This  is  commercial  software,  only  users  who have purchased a valid *
* license  and  accept  to the terms of the  License Agreement can install *
* and use this program.                                                    *
*                                                                          *
****************************************************************************
* PLEASE READ THE FULL TEXT  OF THE SOFTWARE  LICENSE   AGREEMENT  IN  THE *
* "copyright.txt" FILE PROVIDED WITH THIS DISTRIBUTION PACKAGE.            *
****************************************************************************/

/**
* @noinspection PhpMultipleClassDeclarationsInspection
* @noinspection PhpUnusedParameterInspection
* @noinspection PhpUnused
*/

if (!defined('BOOTSTRAP')) { die('Access denied'); }

function fn_get_orders_history(): array
{
    return db_get_array(
        "SELECT logs.*, users.firstname, users.lastname FROM ?:orders_history as logs " .
        "LEFT JOIN ?:users as users USING(user_id) ORDER BY logs.log_id ASC"
    );
}

function fn_orders_history_status($status, $lang_code = CART_LANGUAGE)
{
    switch ($lang_code) {
        case 'ru':
            return [
                'P' => 'Обработан',
                'C' => 'Выполнен',
                'O' => 'Открыт',
                'F' => 'Неудача',
                'D' => 'Отклонен',
                'B' => 'Отложен',
                'I' => 'Аннулирован',
                'Y' => 'Ожидает звонка',
                'A' => 'На удержании',
                'E' => 'Возвращено',
                'N' => 'Незавершенный',
                'T' => '_parent_order'
            ][$status];

        case 'en':
            return [
                'P' => 'Paid',
                'C' => 'Complete',
                'O' => 'Open',
                'F' => 'Failed',
                'D' => 'Declined',
                'B' => 'Backordered',
                'I' => 'Canceled',
                'Y' => 'Waiting call',
                'A' => 'Hold',
                'E' => 'Returned',
                'N' => 'Incomplete',
                'T' => '_parent_order'
            ][$status];
    }

    return $status;
}

/** Hook */

/**
 * @param string $status_to
 * @param string $status_from
 * @param array $order_info
 * @param array $force_notification
 * @param array $order_statuses
 * @param bool $place_order
 */
function fn_orders_history_change_order_status(
    string $status_to,
    string $status_from,
    array $order_info,
    array $force_notification,
    array $order_statuses,
    bool $place_order
)
{
    if (AREA === 'A' && $status_to !== $status_from && $status_to !== 'N' && !$place_order) {
        db_query("INSERT INTO ?:orders_history SET ?u", array(
            'order_id' => $order_info['order_id'],
            'user_id' => $_SESSION['auth']['user_id'],
            'status_old' => $status_from,
            'status_new' => $status_to,
            'timestamp' => TIME
        ));
    }
}

/** \ Hook */
