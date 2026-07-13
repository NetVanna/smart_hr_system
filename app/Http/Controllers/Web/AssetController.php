<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Asset;
use App\Models\Employee;
use Illuminate\Http\Request;

class AssetController extends Controller
{
    public function index()
    {
        $assets = Asset::with('employee')->where('company_id', auth()->user()->company_id)->latest()->get();
        return view('assets.index', compact('assets'));
    }

    public function create()
    {
        $employees = Employee::where('company_id', auth()->user()->company_id)->get();
        return view('assets.create', compact('employees'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string|max:100',
            'serial_number' => 'nullable|string|max:255',
            'employee_id' => 'nullable|exists:employees,id',
            'condition' => 'required|string',
            'status' => 'required|string',
            'purchased_at' => 'nullable|date',
            'price' => 'nullable|numeric|min:0'
        ]);

        $validated['company_id'] = auth()->user()->company_id;

        Asset::create($validated);

        return redirect()->route('assets.index')->with('success', 'Asset recorded successfully.');
    }

    public function edit(Asset $asset)
    {
        $employees = Employee::where('company_id', auth()->user()->company_id)->get();
        return view('assets.edit', compact('asset', 'employees'));
    }

    public function update(Request $request, Asset $asset)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string|max:100',
            'serial_number' => 'nullable|string|max:255',
            'employee_id' => 'nullable|exists:employees,id',
            'condition' => 'required|string',
            'status' => 'required|string',
            'purchased_at' => 'nullable|date',
            'price' => 'nullable|numeric|min:0'
        ]);

        $oldEmployeeId = $asset->employee_id;
        $newEmployeeId = $request->employee_id;

        $asset->update($validated);

        // Log lifecycle events
        if ($oldEmployeeId !== $newEmployeeId) {
            // Checkin if previous assignment existed
            if ($oldEmployeeId) {
                \App\Models\AssetLog::create([
                    'asset_id' => $asset->id,
                    'employee_id' => $oldEmployeeId,
                    'action' => 'Checkin',
                    'condition' => $request->condition,
                    'notes' => 'Returned via update'
                ]);
            }
            // Checkout if new assignment exists
            if ($newEmployeeId) {
                \App\Models\AssetLog::create([
                    'asset_id' => $asset->id,
                    'employee_id' => $newEmployeeId,
                    'action' => 'Checkout',
                    'condition' => $request->condition,
                    'notes' => 'Assigned via update'
                ]);
            }
        }

        return redirect()->route('assets.index')->with('success', 'Asset updated successfully.');
    }

    public function destroy(Asset $asset)
    {
        $asset->delete();
        return redirect()->route('assets.index')->with('success', 'Asset removed successfully.');
    }
}
