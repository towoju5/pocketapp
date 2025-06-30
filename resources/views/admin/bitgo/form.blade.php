<div class="row g-3">
    <div class="col-md-6">
        <label class="form-label">Wallet ID</label>
        <input type="text" name="wallet_id" class="form-control" value="{{ old('wallet_id', $wallet->wallet_id ?? '') }}" required>
    </div>
    <div class="col-md-6">
        <label class="form-label">Wallet Name</label>
        <input type="text" name="wallet_name" class="form-control" value="{{ old('wallet_name', $wallet->wallet_name ?? '') }}" required>
    </div>
    <div class="col-md-6">
        <label class="form-label">Wallet Ticker</label>
        <input type="text" name="wallet_ticker" class="form-control" value="{{ old('wallet_ticker', $wallet->wallet_ticker ?? '') }}" required>
    </div>
    <div class="col-md-6">
        <label class="form-label">Type</label>
        <input type="text" name="type" class="form-control" value="{{ old('type', $wallet->type ?? '') }}" required>
    </div>
    <div class="col-md-6">
        <label class="form-label">Require Memo</label>
        <select name="require_memo" class="form-select" required>
            <option value="1" {{ old('require_memo', $wallet->require_memo ?? '') == 1 ? 'selected' : '' }}>Yes</option>
            <option value="0" {{ old('require_memo', $wallet->require_memo ?? '') == 0 ? 'selected' : '' }}>No</option>
        </select>
    </div>
    <div class="col-md-6">
        <label class="form-label">Can Deposit</label>
        <select name="can_deposit" class="form-select" required>
            <option value="1" {{ old('can_deposit', $wallet->can_deposit ?? '') == 1 ? 'selected' : '' }}>Yes</option>
            <option value="0" {{ old('can_deposit', $wallet->can_deposit ?? '') == 0 ? 'selected' : '' }}>No</option>
        </select>
    </div>
    <div class="col-md-6">
        <label class="form-label">Can Payout</label>
        <select name="can_payout" class="form-select" required>
            <option value="1" {{ old('can_payout', $wallet->can_payout ?? '') == 1 ? 'selected' : '' }}>Yes</option>
            <option value="0" {{ old('can_payout', $wallet->can_payout ?? '') == 0 ? 'selected' : '' }}>No</option>
        </select>
    </div>
    <div class="col-md-6">
        <label class="form-label">Coin Logo (URL)</label>
        <input type="url" name="coin_logo" class="form-control" value="{{ old('coin_logo', $wallet->coin_logo ?? '') }}">
    </div>
    <div class="col-12">
        <label class="form-label">Meta Data (JSON)</label>
        @php
            $metaData = is_array($wallet->meta_data ?? null)
                ? $wallet->meta_data
                : json_decode($wallet->meta_data ?? '[]', true);
        @endphp

        <textarea name="meta_data" class="form-control" rows="4">
        {{ old('meta_data', json_encode($metaData, JSON_PRETTY_PRINT)) }}
        </textarea>

    </div>
</div>
