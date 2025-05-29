<?php

namespace App\Http\Controllers;

use App\Models\Court;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminCourtController extends Controller
{
    public function index(Request $request)
    {
        $query = Court::query();

        // Search
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Type filter
        if ($request->has('type') && $request->type !== '') {
            $query->where('type', $request->type);
        }

        // Status filter
        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }

        // Get courts with pagination
        $courts = $query->orderBy('type')
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        // Get statistics
        $stats = [
            'total_courts' => Court::count(),
            'available_courts' => Court::where('status', 'available')->count(),
            'professional_courts' => Court::where('type', 'professional')->count(),
            'standard_courts' => Court::where('type', 'standard')->count(),
            'training_courts' => Court::where('type', 'training')->count(),
        ];

        return view('admin.courts.index', compact('courts', 'stats'));
    }

    public function create()
    {
        return view('admin.courts.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:professional,standard,training',
            'description' => 'required|string',
            'status' => 'required|in:available,unavailable,maintenance',
            'rate_per_hour' => 'required|numeric|min:0',
            'weekend_rate_per_hour' => 'required|numeric|min:0',
            'opening_time' => 'required|date_format:H:i',
            'closing_time' => 'required|date_format:H:i|after:opening_time',
        ]);

        $court = Court::create($validated);

        return redirect()
            ->route('admin.courts.index')
            ->with('success', 'Court created successfully.');
    }

    public function edit(Court $court)
    {
        return view('admin.courts.edit', compact('court'));
    }

    public function update(Request $request, Court $court)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:professional,standard,training',
            'description' => 'required|string',
            'status' => 'required|in:available,unavailable,maintenance',
            'rate_per_hour' => 'required|numeric|min:0',
            'weekend_rate_per_hour' => 'required|numeric|min:0',
            'opening_time' => 'required|date_format:H:i',
            'closing_time' => 'required|date_format:H:i|after:opening_time',
        ]);

        $court->update($validated);

        return redirect()
            ->route('admin.courts.index')
            ->with('success', 'Court updated successfully.');
    }

    public function destroy(Court $court)
    {
        // Check if court has any bookings
        if ($court->bookings()->exists()) {
            return back()->with('error', 'Cannot delete court with existing bookings.');
        }

        $court->delete();

        return redirect()
            ->route('admin.courts.index')
            ->with('success', 'Court deleted successfully.');
    }

    public function updateStatus(Court $court, Request $request)
    {
        $validated = $request->validate([
            'status' => 'required|in:available,unavailable,maintenance'
        ]);

        $court->update($validated);

        return response()->json([
            'message' => 'Court status updated successfully',
            'status' => $court->status
        ]);
    }
}
