<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Employee;
use Illuminate\Http\Request;

class BranchController extends Controller
{
    public function index(Request $request)
    {
        $query = Branch::withCount('employees')->latest();

        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%")
                  ->orWhere('address', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->where('is_active', true);
            } elseif ($request->status === 'inactive') {
                $query->where('is_active', false);
            }
        }

        $branches = $query->paginate(12)->withQueryString();

        // 4 Metric Stats for HR Attendee Top Grid
        $totalBranches    = Branch::count();
        $activeBranches   = Branch::where('is_active', true)->count();
        $assignedStaff    = Employee::whereNotNull('branch_id')->count();
        $geofencedBranches = Branch::whereNotNull('latitude')->whereNotNull('longitude')->count();

        return view('branches.index', compact(
            'branches',
            'totalBranches',
            'activeBranches',
            'assignedStaff',
            'geofencedBranches'
        ));
    }

    public function create()
    {
        return view('branches.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'            => 'required|string|max:255',
            'code'            => 'nullable|string|max:50',
            'phone'           => 'nullable|string|max:25',
            'address'         => 'nullable|string|max:500',
            'latitude'        => 'nullable|numeric|between:-90,90',
            'longitude'       => 'nullable|numeric|between:-180,180',
            'geofence_radius' => 'nullable|numeric|min:10|max:10000',
            'is_active'       => 'boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);
        $validated['geofence_radius'] = $request->input('geofence_radius', 100);

        Branch::create($validated);

        return redirect()->route('branches.index')
            ->with('success', 'Branch created successfully.');
    }

    public function edit(Branch $branch)
    {
        return view('branches.edit', compact('branch'));
    }

    public function update(Request $request, Branch $branch)
    {
        $validated = $request->validate([
            'name'            => 'required|string|max:255',
            'code'            => 'nullable|string|max:50',
            'phone'           => 'nullable|string|max:25',
            'address'         => 'nullable|string|max:500',
            'latitude'        => 'nullable|numeric|between:-90,90',
            'longitude'       => 'nullable|numeric|between:-180,180',
            'geofence_radius' => 'nullable|numeric|min:10|max:10000',
            'is_active'       => 'boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active');
        $validated['geofence_radius'] = $request->input('geofence_radius', 100);

        $branch->update($validated);

        return redirect()->route('branches.index')
            ->with('success', 'Branch updated successfully.');
    }

    public function destroy(Branch $branch)
    {
        $branch->delete();

        return redirect()->route('branches.index')
            ->with('success', 'Branch deleted successfully.');
    }
}
