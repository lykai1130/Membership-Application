<?php

namespace App\Http\Controllers;

use App\Models\Promotion;
use Illuminate\Http\Request;

class PromotionController extends Controller
{
    public function index()
    {
        $today = now()->toDateString();

        $promotion = Promotion::query()
            ->where('status', 'A')
            ->whereDate('start_date', '<=', $today)
            ->whereDate('end_date', '>=', $today)
            ->orderByDesc('start_date')
            ->orderByDesc('id')
            ->first();

        if (!$promotion) {
            $promotion = Promotion::query()
                ->orderByDesc('start_date')
                ->orderByDesc('id')
                ->first();
        }

        return view('promotion', compact('promotion'));
    }

    public function update(Request $request, Promotion $promotion)
    {
        $validated = $request->validate([
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'status' => ['required', 'in:A,I'],
        ]);

        $promotion->update([
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'status' => $validated['status'],
        ]);

        return redirect('/promotion')->with('success', 'Promotion updated successfully.');
    }
}
