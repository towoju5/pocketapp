<?php

use App\Jobs\EvaluateTrade;
use Illuminate\Support\Facades\Broadcast;



Broadcast::channel('trade-created', function ($trade) {
    return $trade;
});

Broadcast::channel('trade-updated', function ($trade) {
    return $trade;
});

Broadcast::channel('trade-updated', function ($trade) {
    return $trade;
});


Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('trades.user.{userId}', function ($user, $userId) {
    return (int) $user->id === (int) $userId;
});
