<?php

namespace App\Http\Controllers;

use App\Models\payment_sp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Xendit\Xendit;

class PaymentSpController extends Controller
{
    public function __construct()
    {
        try {
            Xendit::setApiKey(env('XENDIT_API_KEY'));
            Log::info('Xendit SDK initialized successfully');
        } catch (\Exception $e) {
            Log::error('Error initializing Xendit SDK: ' . $e->getMessage());
        }
    }


    public function createInvoice(Request $request)
    {
        $validated = $request->validate([
            'id_parent' => 'required|exists:prospect_parents,id',
            'no_invoice' => 'required|string|max:255|unique:payment_sps,no_invoice',
            'payer_email' => 'required|email',
            'description' => 'required|string|max:255',
            'amount' => 'required|numeric|min:1',
        ]);

        try {
            // Buat invoice melalui Xendit
            $invoice = \Xendit\Invoice::create([
                'external_id' => $validated['no_invoice'],
                'payer_email' => $validated['payer_email'],
                'description' => $validated['description'],
                'amount' => $validated['amount'],
            ]);

            // Simpan data invoice ke database
            $paymentSp = payment_sp::create([
                'id_parent' => $validated['id_parent'],
                'no_invoice' => $validated['no_invoice'],
                'link_pembayaran' => $invoice['invoice_url'],
                'status_pembayaran' => 0, // Status: Pending
                'total' => $validated['amount'],
            ]);

            return response()->json([
                'message' => 'Invoice created successfully',
                'invoice' => $invoice,
            ], 201);
        } catch (\Exception $e) {
            Log::error('Error creating invoice: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function handleCallback(Request $request)
    {
        // Ambil data dari callback
        $data = $request->all();

        Log::info('Xendit Callback:', $data);

        // Cari data invoice berdasarkan external_id
        $paymentSp = payment_sp::where('no_invoice', $data['external_id'])->first();

        if (!$paymentSp) {
            return response()->json(['message' => 'Invoice not found'], 404);
        }

        // Perbarui status pembayaran
        $status = 0; // Default: Pending

        switch ($data['status']) {
            case 'PAID':
                $status = 1; // Paid
                break;

            case 'EXPIRED':
                $status = 2; // Expired
                break;
        }


        $paymentSp->update(['status_pembayaran' => $status]);

        return response()->json(['message' => 'Callback processed successfully'], 200);
    }

    public function index()
    {
        return response()->json(payment_sp::all());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_parent' => 'required|exists:prospect_parents,id',
            'link_pembayaran' => 'nullable|string|max:255',
            'no_invoice' => 'required|string|max:255',
            'no_pemesanan' => 'nullable|string|max:255',
            'date_paid' => 'required|date',
            'status_pembayaran' => 'required|integer',
            'biaya_admin' => 'nullable|numeric|min:0',
            'total' => 'required|integer',
        ]);

        $paymentSp = payment_sp::create($validated);

        return response()->json($paymentSp, 201);
    }

    public function show($id)
    {
        $paymentSp = payment_sp::find($id);

        if (!$paymentSp) {
            return response()->json(['message' => 'PaymentSp not found'], 404);
        }

        return response()->json($paymentSp);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'id_parent' => 'required|exists:prospect_parents,id',
            'link_pembayaran' => 'nullable|string|max:255',
            'no_invoice' => 'required|string|max:255',
            'no_pemesanan' => 'nullable|string|max:255',
            'date_paid' => 'required|date',
            'status_pembayaran' => 'required|integer',
            'total' => 'required|integer',
        ]);

        $paymentSp = payment_sp::find($id);

        if (!$paymentSp) {
            return response()->json(['message' => 'PaymentSp not found'], 404);
        }

        $paymentSp->update($validated);

        return response()->json($paymentSp);
    }

    public function destroy($id)
    {
        $paymentSp = payment_sp::find($id);

        if (!$paymentSp) {
            return response()->json(['message' => 'PaymentSp not found'], 404);
        }

        $paymentSp->delete();

        return response()->json(['message' => 'PaymentSp deleted']);
    }
}
