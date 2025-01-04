<?php

use App\Http\Controllers\DepositController;
use App\Http\Controllers\PayoutController;
use App\Http\Controllers\ProfileController;
use App\Models\User;
use App\Jobs\EvaluateTrade;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GitHubArtifactController;



Route::get('/', function () {

    try {
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
    } catch (\Throwable $th) {
        return response()->json(['error' => $th->getMessage()], 500);
    }
    // return view('welcome');
});


Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/dashboard-2', function () {
    return view('dash');
}); //->middleware(['auth', 'verified'])->name('dashboard-2');

Route::middleware(['auth'])->group(function () {
    Route::resource('deposits', DepositController::class);
    Route::get('deposit-history', [DepositController::class, 'getDepositHistory']);
    Route::post('deposits/{deposit}/cancel', [DepositController::class, 'cancelDeposit']);
    Route::get('deposit-stats', [DepositController::class, 'getDepositStats']);

    Route::resource('payout', PayoutController::class)->names('withdrawals');
    Route::resource('deposit', DepositController::class)->names('deposit');
    Route::resource('payout', PayoutController::class)->names('payout');
    // });


    // Route::middleware('auth')->group(function () {
    // Route::get('profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::get('profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::patch('profile/photo/update', [ProfileController::class, 'update'])->name('profile.photo.update');
    Route::delete('profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});



require __DIR__ . '/auth.php';
require __DIR__ . '/temp.php';
require __DIR__ . '/chat.php';
require __DIR__ . '/trade.php';
require __DIR__ . '/admin.php';


Route::fallback(function () {
    sleep(10);
    return response()->json([
        "status" => "error",
        "message" => "Route not found",
        "data" => null
    ], 404);
});