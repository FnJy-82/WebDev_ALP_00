<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Ticket;
use Illuminate\Http\Request;

class EventSeatController extends Controller
{
    public function generate(Request $request, $eventId)
    {
        $event = Event::findOrFail($eventId);

        // 1. Validation
        $request->validate([
            'section_name' => 'required|string',
            'price' => 'required|numeric',
            'rows' => 'required|integer|min:1|max:50',
            'columns' => 'required|integer|min:1|max:50',
        ]);

        // 2. Create the Category (VIP, Festival, etc.)
        // Ensure you have the relationship 'categories' in your Event model
        $category = $event->ticketCategories()->create([
            'name' => $request->section_name,
            'price' => $request->price,
            'stock' => $request->rows * $request->columns,
        ]);

        // 3. Generate Seats
        $rows = $request->rows;
        $columns = $request->columns;
        $ticketsData = [];
        $letters = range('A', 'Z'); // A, B, C...

        for ($r = 0; $r < $rows; $r++) {
            // Logic for Row Label (A, B... AA, AB...)
            $rowLabel = $letters[$r] ?? 'Row-' . ($r + 1);

            for ($c = 1; $c <= $columns; $c++) {
                $seatNumber = $rowLabel . '-' . $c; // A-1, A-2...

                $ticketsData[] = [
                    'event_id' => $event->id,
                    'category_id' => $category->id,
                    'seat_number' => $seatNumber,
                    'row_label' => $rowLabel,
                    'status' => 'available',
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        // 4. Bulk Insert
        Ticket::insert($ticketsData);

        return back()->with('success', "Berhasil membuat section {$request->section_name} dengan " . count($ticketsData) . " kursi!");
    }
}
