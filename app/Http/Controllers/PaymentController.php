<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function __construct()
    {
        // Todas las rutas de este controlador requieren estar autenticado
        $this->middleware('auth:sanctum');
    }

    /**
     * Listar todos los pagos, con su reserva, usuario y tour relacionados.
     */
    public function index()
    {
        $payments = Payment::with([
            'reservation.user',
            'reservation.tour'
        ])->get();

        return response()->json($payments);
    }

    /**
     * Realizar un pago.
     */
    public function store(Request $request)
    {
        $request->validate([
            'reservation_id' => 'required|exists:reservations,id',
            'payment_method' => 'required|string',
            'amount'         => 'required|numeric',
            'status'         => 'required|string|in:completed,pending,canceled',
        ]);

        $payment = Payment::create([
            'reservation_id' => $request->reservation_id,
            'payment_method' => $request->payment_method,
            'amount'         => $request->amount,
            'status'         => $request->status,
        ]);

        return response()->json($payment, 201);
    }

    /**
     * Obtener detalles de un pago específico, incluyendo reserva, usuario y tour.
     */
    public function show($id)
    {
        $payment = Payment::with([
            'reservation.user',
            'reservation.tour'
        ])->findOrFail($id);

        return response()->json($payment);
    }
}
