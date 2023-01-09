<?php

use Illuminate\Support\Facades\Broadcast;
use Modules\Customer\Models\Customer;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('chat.{customerId}', function ($user, $customerId) {
    if (\Request::path() == 'broadcasting/auth') {
        return Customer::find($customerId);
    } else {
        return (int) $user->id === (int) $customerId;
    }
});

Broadcast::channel('chatnotification.{userId}', function ($user, $userId) {
    return (int) $user->id === (int) $userId;
});

Broadcast::channel('chatnotificationcustomer.{customerId}', function ($user, $customerId) {
    return (int) $user->id === (int) $customerId;
});

Broadcast::channel('usernotifications.{userId}', function ($user, $userId) {
    return (int) $user->id === (int) $userId;
});

Broadcast::channel('customernotifications.{customerId}', function ($user, $customerId) {
    return (int) $user->id === (int) $customerId;
});
