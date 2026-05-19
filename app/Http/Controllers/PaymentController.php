<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    /**
     * Display payment list
     */
    public function index(Request $request)
    {
        $search = $request->search;

        $payments = Payment::with('student')
            ->when($search, function ($query) use ($search) {
                $query->where('transaction_id', 'LIKE', "%{$search}%")
                      ->orWhere('payment_type', 'LIKE', "%{$search}%")
                      ->orWhere('status', 'LIKE', "%{$search}%");
            })
            ->latest()
            ->paginate(10);

        return response()->json([
            'success' => true,
            'message' => 'Payment List',
            'data' => $payments
        ], 200);
    }

    /**
     * Store new payment
     */
    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'amount' => 'required|numeric|min:1',
            'payment_type' => 'required|string|max:255',
            'payment_date' => 'required|date',
            'transaction_id' => 'required|unique:payments,transaction_id',
            'status' => 'required|in:paid,pending,failed'
        ]);

        $payment = Payment::create([
            'student_id' => $request->student_id,
            'amount' => $request->amount,
            'payment_type' => $request->payment_type,
            'payment_date' => $request->payment_date,
            'transaction_id' => $request->transaction_id,
            'status' => $request->status
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Payment Created Successfully',
            'data' => $payment
        ], 201);
    }

    /**
     * Display single payment
     */
    public function show(string $id)
    {
        $payment = Payment::with('student')->find($id);

        if (!$payment) {
            return response()->json([
                'success' => false,
                'message' => 'Payment Not Found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Payment Detail',
            'data' => $payment
        ], 200);
    }

    /**
     * Update payment
     */
    public function update(Request $request, string $id)
    {
        $payment = Payment::find($id);

        if (!$payment) {
            return response()->json([
                'success' => false,
                'message' => 'Payment Not Found'
            ], 404);
        }

        $request->validate([
            'student_id' => 'required|exists:students,id',
            'amount' => 'required|numeric|min:1',
            'payment_type' => 'required|string|max:255',
            'payment_date' => 'required|date',
            'transaction_id' => 'required|unique:payments,transaction_id,' . $id,
            'status' => 'required|in:paid,pending,failed'
        ]);

        $payment->update([
            'student_id' => $request->student_id,
            'amount' => $request->amount,
            'payment_type' => $request->payment_type,
            'payment_date' => $request->payment_date,
            'transaction_id' => $request->transaction_id,
            'status' => $request->status
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Payment Updated Successfully',
            'data' => $payment
        ], 200);
    }

    /**
     * Delete payment
     */
    public function destroy(string $id)
    {
        $payment = Payment::find($id);

        if (!$payment) {
            return response()->json([
                'success' => false,
                'message' => 'Payment Not Found'
            ], 404);
        }

        $payment->delete();

        return response()->json([
            'success' => true,
            'message' => 'Payment Deleted Successfully'
        ], 200);
    }
}