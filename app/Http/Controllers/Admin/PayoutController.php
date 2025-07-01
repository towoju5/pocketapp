<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payout;
use Illuminate\Http\Request;

class PayoutController extends Controller
{
    /**
     * Display a listing of the payout requests.
     */
    public function index(Request $request)
    {
        // Optional: filter by status via query param (e.g., ?status=pending)
        $query = Payout::query();

        if ($request->has('status') && $request->status !== 'all') {
            $query->where('payout_status', $request->status);
        }

        $payouts = $query->with('method')->orderBy('created_at', 'desc')->paginate(15);

        return view('admin.payouts.index', compact('payouts'));
    }

    /**
     * Update the status of a payout request.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,approved,rejected,paid',
        ]);

        $payout = Payout::findOrFail($id);

        if ($payout->payout_status == "rejected") {
            return back()->withErrors('Transaction already cancelled/rejected');
        }
        $payout->payout_status = $request->status;
        $payout->save();

        if ($request->status === "rejected") {
            if (! credit_user('qt_real_usd', $payout['payout_amount'], "Failed Payout Refund")) {
                return back()->with('error', 'Could not refund customer.')->withInput();
            }
        }

        return redirect()->back()->with('success', 'Payout status updated successfully.');
    }
}
