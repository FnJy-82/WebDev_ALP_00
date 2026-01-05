<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Models\Event;
use App\Models\Ticket;
use Illuminate\Http\Request;

class EventSeatController extends Controller
{
    public function generate(Request $request, $eventId)
    {
        $event = Event::findOrFail($eventId);

        // 1. Validation (Using your original names)
        $request->validate([
            'section_name' => 'required|string',
            'price'        => 'required|numeric|min:0',
            'rows'         => 'required|integer|min:1',
            'columns'      => 'required|integer|min:1',
        ]);

        DB::transaction(function () use ($request, $event) {
            
            // 2. Create the Category (Your original logic)
            $category = $event->ticketCategories()->create([
                'name'        => $request->section_name,
                'price'       => $request->price,
                'stock'       => $request->rows * $request->columns,
                'total_quota' => $request->rows * $request->columns, // Sync both fields to be safe
            ]);

            // 3. Generate Seats (Your original loop logic)
            $rows = $request->rows;
            $columns = $request->columns;
            $ticketsData = [];
            $letters = range('A', 'Z'); // A, B, C...

            for ($r = 0; $r < $rows; $r++) {
                // Logic for Row Label
                $rowLabel = $letters[$r] ?? 'Row-' . ($r + 1);

                for ($c = 1; $c <= $columns; $c++) {
                    $seatNumber = $rowLabel . '-' . $c; // A-1, A-2...

                    $ticketsData[] = [
                        'event_id'    => $event->id,
                        'category_id' => $category->id,
                        'seat_number' => $seatNumber,
                        'row_label'   => $rowLabel, // This is crucial for your View grouping
                        'status'      => 'available',
                        'created_at'  => now(),
                        'updated_at'  => now(),
                    ];
                }
            }

            // 4. Bulk Insert
            Ticket::insert($ticketsData);

            // 5. UPDATE EVENT DATA (The Fix for "Harga/Kuota Kosong")
            // Recalculate totals for the event
            $totalQuota = Ticket::where('event_id', $event->id)->count();
            $minPrice = $event->ticketCategories()->min('price');

            $event->update([
                'quota' => $totalQuota,
                'price' => $minPrice ?? 0
            ]);
        });

        return back()->with('success', "Berhasil membuat section {$request->section_name}!");
    }
}