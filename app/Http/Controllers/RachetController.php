<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Ratchet\Client;

class RachetController extends Controller
{
    public function test()
    {

        \Ratchet\Client\connect('wss://streamer.finance.yahoo.com:443')->then(function ($conn) {
            $conn->on('message', function ($msg) use ($conn) {
                echo "Received: {$msg}\n";
                $packed = base64_decode($msg);
                $msg = new PricingData();
                $msg->mergeFromString($packed);
                var_dump($msg->serializeToJsonString());
            });

            $conn->send('{"subscribe":["BTC-USD","ETH-USD","XRP-USD","USDT-USD","BCH-USD","BA","TSLA","AXSM","UBER","MIRM","GRKZF","SCGPY","BDVSF","WPX","BIPSX","ENPIX","ENPSX","BPTUX","BPTIX","CL=F","GC=F","SI=F","EURUSD=X","GBPUSD=X","JPY=X","EZA","IXC","IYE","FILL","EWT","CGIX1191220P00005000","TORC191220P00002500","RIOT191213C00001000","TPCO191220C00002500","DHR","AMRN","AMD","PCG","VIX191218P00012500","VIX191218P00014000","EEM191220P00039000","EEM200117C00045000","BTCUSD=X","ETHUSD=X","AUDUSD=X","NZDUSD=X","EURJPY=X","GBPJPY=X","EURGBP=X","EURCAD=X","EURSEK=X","EURCHF=X","EURHUF=X","CNY=X","HKD=X","SGD=X","INR=X","MXN=X","PHP=X","IDR=X","THB=X","MYR=X","ZAR=X","RUB=X","ZG=F","ZI=F","PL=F","HG=F","PA=F","HO=F","NG=F","RB=F","BZ=F","B0=F","C=F","O=F","KW=F","RR=F","SM=F","BO=F","S=F","FC=F","LH=F","LC=F","CC=F","KC=F","CT=F","LB=F","OJ=F","SB=F","IFF","CRS","RLLCF","BGNE","^GSPC","^DJI","^IXIC","^RUT","^TNX","^VIX","^CMC200","^FTSE","^N225"]}');

        }, function ($e) {
            echo "Could not connect: {$e->getMessage()}\n";
        });
    }
}
