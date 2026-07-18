<?php
namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Bavix\Wallet\Traits\HasWallet;
use Bavix\Wallet\Traits\HasWalletFloat;
use Bavix\Wallet\Traits\HasWallets;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Yadahan\AuthenticationLog\AuthenticationLogable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, AuthenticationLogable, SoftDeletes, HasWallets, HasWallet, HasWalletFloat;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'username',
        'email',
        'phone',
        'birthday',
        'password',
        'config',
        'referred_by',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $with = [
        'user_meta',
        'user_deposit',
        'user_payout',
        'user_trades',
        'transaction_history',
        'tournament_history',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
            'config'            => 'array',
            'address'           => 'array',
        ];
    }

    public function user_meta()
    {
        return $this->hasMany(UserMeta::class, "user_id");
    }

    public function user_deposit()
    {
        return $this->hasMany(Deposit::class, "user_id");
    }

    public function user_payout()
    {
        return $this->hasMany(Payout::class, "user_id");
    }

    public function user_trades()
    {
        return $this->hasMany(Trade::class, "user_id");
    }

    public function trades()
    {
        return $this->hasMany(Trade::class, "user_id");
    }

    public function transaction_history()
    {
        return $this->hasMany(TransactionHistory::class, "user_id");
    }

    public function tournament_history()
    {
        return $this->hasMany(TournamentSubscribers::class, "user_id");
    }

    public function kyc()
    {
        return $this->hasOne(KycVerification::class);
    }

    public function achievements()
    {
        return $this->belongsToMany(Achievement::class, 'user_achievements')
            ->withPivot('unlocked_at')
            ->withTimestamps();
    }

    public function supportTickets()
    {
        return $this->hasMany(SupportTicket::class);
    }

    /** Traders this user is copying. */
    public function following()
    {
        return $this->hasMany(TraderFollow::class, 'follower_id');
    }

    /** Users copying this trader. */
    public function copiers()
    {
        return $this->hasMany(TraderFollow::class, 'trader_id');
    }

    public function referrer()
    {
        return $this->belongsTo(User::class, 'referred_by');
    }

    public function referrals()
    {
        return $this->hasMany(User::class, 'referred_by');
    }

    public function referralCommissions()
    {
        return $this->hasMany(ReferralCommission::class, 'beneficiary_id');
    }

    public function setActiveWallet($walletSlug)
    {
        $this->wallets()->update(['currently_active' => false]);

        return $this->getWallet($walletSlug)->update([
            'currently_active' => true,
        ]);
    }

    protected static function booted()
    {
        static::creating(function ($user) {
            if (empty($user->referral_code)) {
                do {
                    $code = strtoupper(\Illuminate\Support\Str::random(8));
                } while (static::where('referral_code', $code)->exists());

                $user->referral_code = $code;
            }
        });

        static::created(function ($user) {
            createSupportedWalletsForUser($user);
        });
    }

}
