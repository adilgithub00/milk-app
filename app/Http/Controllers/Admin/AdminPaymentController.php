<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AdminPaymentController extends Controller
{
    public function index(Request $request)
    {
        $month = $request->month
            ? Carbon::parse($request->month)
            : now();

        $payments = Payment::whereYear('payment_date', $month->year)
            ->whereMonth('payment_date', $month->month)
            ->orderByDesc('payment_date')
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
