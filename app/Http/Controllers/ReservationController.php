<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\Tour;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    /**
     * Crear una nueva reserva.
     */
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    public function store(Request $request)
    {
        $request->validate([
            'tour_id'          => 'required|exists:tours,id',
            'quantity'         => 'required|integer|min:1',
            'reservation_date' => 'required|date',
        ]);

        $tour       = Tour::findOrFail($request->tour_id);
        $totalPrice = $tour->price * $request->quantity;

        $reservation = Reservation::create([
            'tour_id'          => $request->tour_id,
            'user_id'          => auth()->user()->id,
            'quantity'         => $request->quantity,
            'reservation_date' => $request->reservation_date,
            'total_price'      => $totalPrice,
        ]);

        return response()->json($reservation, 201);
    }

    /**
     * Obtener una reserva específica.
     */
    public function show($id)
    {
        $reservation = Reservation::with(['tour', 'user'])->findOrFail($id);
        return response()->json($reservation);
    }

    /**
     * Listar todas las reservas.
     */
    public function index()
    {
        $reservations = Reservation::with(['tour', 'user'])->get();
        return response()->json($reservations);
    }

    /**
     * Eliminar una reserva.
     */
    public function destroy($id)
    {
        $reservation = Reservation::findOrFail($id);
        $reservation->delete();
        return response()->json(null, 204);
    }
}
