@extends('layouts.desktop.trading')

@section('title', 'Submit Identity Documents')

@section('content')
<style>
    .omni-profile-shell { width: 100%; max-width: 700px; margin: 0 auto; padding: 40px 24px; display: flex; flex-direction: column; gap: 30px; }
    .identity-card { background: rgba(15, 23, 42, 0.85); backdrop-filter: blur(25px); border: 1px solid rgba(255, 255, 255, 0.08); border-radius: 35px; padding: 40px; }
    .input-group { display: flex; flex-direction: column; gap: 8px; width: 100%; margin-bottom: 20px; }
    .input-group label { font-size: 11px; font-weight: 800; color: #64748b; text-transform: uppercase; letter-spacing: 1.5px; }
    .vault-input { width: 100%; background: rgba(0,0,0,0.3); border: 1px solid rgba(255,255,255,0.1); padding: 16px; border-radius: 15px; color: #fff; font-size: 15px; outline: none; }
    .vault-input:focus { border-color: #3b82f6; }
    .btn-sync { background: linear-gradient(135deg, #2563eb, #3b82f6); color: #fff; border: none; padding: 18px 35px; border-radius: 18px; font-weight: 900; text-transform: uppercase; letter-spacing: 1px; cursor: pointer; width: 100%; }
    @media (max-width: 768px) { .omni-profile-shell { padding: 20px; } .identity-card { padding: 30px 20px; border-radius: 25px; } }
</style>

<div class="omni-profile-shell">
    @if ($errors->any())
        <div style="background:rgba(239,68,68,0.1); border-left:5px solid #ef4444; color:#f87171; padding:20px; border-radius:15px;">&#9888;&#65039; {{ $errors->first() }}</div>
    @endif

    <div class="identity-card">
        <h2 style="margin: 0 0 10px 0; font-weight: 950; font-size: 26px; color: #fff; letter-spacing: -1px;">Submit Identity Documents</h2>
        <p style="color:#64748b; font-size:14px; margin-bottom:30px;">Upload a government-issued document to unlock withdrawals.</p>

        <form method="POST" action="{{ route('kyc.store') }}" enctype="multipart/form-data">
            @csrf

            <div class="input-group">
                <label>Document Type</label>
                <select name="document_type" class="vault-input" required>
                    <option value="passport">Passport</option>
                    <option value="national_id">National ID</option>
                    <option value="drivers_license">Driver's License</option>
                </select>
            </div>

            <div class="input-group">
                <label>Document (Front)</label>
                <input type="file" name="document_front" class="vault-input" accept="image/*,.pdf" required>
            </div>

            <div class="input-group">
                <label>Document (Back) &mdash; Optional</label>
                <input type="file" name="document_back" class="vault-input" accept="image/*,.pdf">
            </div>

            <div class="input-group">
                <label>Selfie Holding Document &mdash; Optional</label>
                <input type="file" name="selfie" class="vault-input" accept="image/*">
            </div>

            <button type="submit" class="btn-sync">Submit for Review</button>
        </form>
    </div>
</div>
@endsection
