<?php

use App\Jobs\EvaluateTrade;
use Illuminate\Support\Facades\Broadcast;


Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});


Broadcast::channel('trade.created', function ($trade) {
    return $trade;
});
