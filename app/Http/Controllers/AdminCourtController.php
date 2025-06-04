<?php

namespace App\Http\Controllers;

use App\Models\Court;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Cloudinary\Cloudinary;
use Cloudinary\Configuration\Configuration;

class AdminCourtController extends Controller
{
    protected $cloudinary;

    public function __construct()
    {
        Configuration::instance([
            'cloud' => [
                'cloud_name' => config('cloudinary.cloud_name'),
                'api_key'    => config('cloudinary.api_key'),
                'api_secret' => config('cloudinary.api_secret'),
            ],
            'url' => [
                'secure' => config('cloudinary.secure', true)
            ]
        ]);
        $this->cloudinary = new Cloudinary();
    }

    public function index(Request $request)
    {
        $query = Court::query();

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('type', 'like', "%{$search}%");
            });
        }

        // Type filter
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Status filter
        if ($request->filled('status')) {
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
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $result = $this->cloudinary->uploadApi()->upload($image->getRealPath(), [
                'folder' => 'courts',
                'resource_type' => 'image',
                'public_id' => 'court_' . time() . '_' . uniqid()
            ]);
            $validated['image_path'] = $result['public_id'];
        }

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
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($court->image_path) {
                $this->cloudinary->uploadApi()->destroy($court->image_path);
            }

            // Upload new image
            $image = $request->file('image');
            $result = $this->cloudinary->uploadApi()->upload($image->getRealPath(), [
                'folder' => 'courts',
                'resource_type' => 'image',
                'public_id' => 'court_' . time() . '_' . uniqid()
            ]);
            $validated['image_path'] = $result['public_id'];
        }

        $court->update($validated);

        return redirect()
            ->route('admin.courts.index')
            ->with('success', 'Court updated successfully.');
    }

    public function destroy(Court $court)
    {
        // Delete the court's image if it exists
        if ($court->image_path) {
            $this->cloudinary->uploadApi()->destroy($court->image_path);
        }

        $court->delete();

        return back()->with('success', 'Court deleted successfully.');
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
