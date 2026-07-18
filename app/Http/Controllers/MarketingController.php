<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MarketingController extends Controller
{
    public function home(): View
    {
        return view('marketing.home');
    }

    public function about(): View
    {
        return view('marketing.about');
    }

    public function features(): View
    {
        return view('marketing.features');
    }

    public function pricing(): View
    {
        // Real investment tiers are wired in once the Plan model lands
        // (staking/plans feature); until then these mirror the reference
        // app's pricing.php card layout with representative placeholder tiers.
        $plans = class_exists(\App\Models\Plan::class)
            ? \App\Models\Plan::query()->where('is_active', true)->orderBy('sort_order')->get()
            : collect();

        return view('marketing.pricing', ['plans' => $plans]);
    }

    public function faq(): View
    {
        return view('marketing.faq');
    }

    public function blog(): View
    {
        return view('marketing.blog', ['news' => $this->dailyNews()]);
    }

    /**
     * Deterministically "generates" 5 news items per calendar day, mirroring
     * the reference blog.php's daily-seeded PRNG approach.
     */
    private function dailyNews(): array
    {
        $seed = (int) date('Ymd');
        mt_srand($seed);

        $topics = [
            'Trading volume hits new record as more traders go live this month.',
            'Platform update improves chart load times and price feed stability.',
            'New OTC assets added, keeping weekend trading open around the clock.',
            'Security review confirms wallet encryption and KYC controls remain strong.',
            '50,000 new trading accounts opened in the last 48 hours.',
            'Signal accuracy improves following latest market-analysis engine update.',
            'Express Trading multipliers expanded to more crypto and forex pairs.',
            'Monthly payout totals exceed projections as trader activity grows.',
            'Faster withdrawal processing now live for verified accounts.',
            'Why demo accounts are the best way to start trading with confidence.',
        ];
        $categories = ['PLATFORM', 'MARKETS', 'SECURITY', 'PAYOUTS', 'TRADING'];
        $brand = config('app.name');
        $body = "At {$brand}, we're constantly refining the trading experience — from faster chart rendering to more reliable live pricing. Today's platform telemetry shows steady growth in daily active traders and continued uptime across all supported markets.";

        shuffle($topics);

        $news = [];
        for ($i = 0; $i < 5; $i++) {
            $news[] = [
                'id' => $i,
                'title' => $topics[$i],
                'timestamp' => date('F j, Y'),
                'category' => $categories[$i],
                'body' => $body,
            ];
        }

        mt_srand();

        return $news;
    }

    public function contact(): View
    {
        return view('marketing.contact');
    }

    public function contactStore(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email', 'max:255'],
            'subject' => ['required', 'string', 'max:255'],
            'message' => ['required', 'string', 'max:5000'],
        ]);

        // Persisted as a real support ticket once the support-tickets
        // feature lands; for now this just confirms receipt.
        if (class_exists(\App\Models\SupportTicket::class) && auth()->check()) {
            $ticket = auth()->user()->supportTickets()->create([
                'subject' => $request->subject,
            ]);
            $ticket->replies()->create([
                'user_id' => auth()->id(),
                'is_admin_reply' => false,
                'message' => $request->message,
            ]);
        }

        return back()->with('status', "Thanks — we've received your message and will reply to {$request->email} shortly.");
    }
}
