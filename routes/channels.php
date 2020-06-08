<?php

use App\Broadcasting\ComeChannel;

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
Broadcast::channel('editor-notify', function ($user) {
    return (bool) $user->hasPermission('manage-articles');
});
Broadcast::channel('App.User.{id}', function ($user, $id) {
    return (bool) $user->hasPermission('manage-articles') && $user->id == $id;
});
