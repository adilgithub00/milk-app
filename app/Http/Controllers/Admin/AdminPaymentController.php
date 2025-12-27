<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MilkEntry;
use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AdminPaymentController extends Controller
{
    public function index(Request $request)
    {
        $month = $request->month
            ? Carbon::parse($request->month)
            : now();

        $payments = Payment::whereYear('payment_date', $month->year)
            ->whereMonth('payment_date', $month->month)
            ->orderBy('payment_date')
            ->get();

        $total = $payments->sum('amount');

        return view('admin.payments.index', compact('payments', 'month', 'total'));
    }

    public function create()
    {
        return view('admin.payments.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'payment_date' => 'required|date|before_or_equal:today',
            'amount' => 'required|integer|min:1'
        ]);

        $monthStart = Carbon::parse($request->payment_date)->startOfMonth();
        $monthEnd = Carbon::parse($request->payment_date)->endOfMonth();

        $total = MilkEntry::whereBetween('entry_date', [$monthStart, $monthEnd])
            ->sum(DB::raw('quantity_kg * rate_per_kg'));

        $paid = Payment::whereBetween('payment_date', [$monthStart, $monthEnd])
            ->sum('amount');

        Log::info('info', ['total' => $total, 'paid' => $paid]);
        if ($request->amount > ($total - $paid)) {
            return back()->withErrors('Amount exceeds remaining balance.');
        }

        Payment::create($request->only('payment_date', 'amount'));

        return redirect()
            ->route('payments.index')
            ->with('success', 'Payment added successfully.');
    }

    public function edit(Payment $payment)
    {
        return view('admin.payments.edit', compact('payment'));
    }

    public function update(Request $request, Payment $payment)
    {
        $request->validate([
            'payment_date' => 'required|date|before_or_equal:today',
            'amount' => 'required|integer|min:1'
        ]);

        $monthStart = Carbon::parse($request->payment_date)->startOfMonth();
        $monthEnd = Carbon::parse($request->payment_date)->endOfMonth();

        $total = MilkEntry::whereBetween('entry_date', [$monthStart, $monthEnd])
            ->sum(DB::raw('quantity_kg * rate_per_kg'));

        $paid = Payment::whereBetween('payment_date', [$monthStart, $monthEnd])
            ->where('id', '!=', $payment->id)
            ->sum('amount');

        if ($request->amount > ($total - $paid)) {
            return back()->withErrors('Amount exceeds remaining balance.');
        }

        $payment->update($request->only('payment_date', 'amount'));

        return redirect()
            ->route('payments.index')
            ->with('success', 'Payment updated successfully.');
    }

    public function destroy(Payment $payment)
    {
        $payment->delete();

        return redirect()
            ->route('payments.index')
            ->with('success', 'Payment deleted successfully.');
    }
}
