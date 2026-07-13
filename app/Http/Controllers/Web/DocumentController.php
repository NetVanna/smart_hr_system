<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DocumentController extends Controller
{
    public function index(Request $request)
    {
        $user = request()->user();
        if (!$user) {
            return redirect()->route('login');
        }

        $query = \App\Models\Document::with('employee')
            ->where('company_id', $user->company_id);

        if ($user->role === 'Employee') {
            $query->where('employee_id', $user->employee_id);
        }

        if ($request->has('category') && $request->category != 'All') {
            $query->where('category', $request->category);
        }

        $documents = $query->orderBy('created_at', 'desc')->get();
        $categories = ['General', 'Contract', 'ID', 'Certificate', 'Insurance', 'Other'];

        return view('documents.index', compact('documents', 'categories'));
    }

    public function create()
    {
        $user = request()->user();
        if (!$user) {
            return redirect()->route('login');
        }
        $employees = \App\Models\Employee::where('company_id', $user->company_id)->get();
        $categories = ['General', 'Contract', 'ID', 'Certificate', 'Insurance', 'Other'];
        return view('documents.create', compact('employees', 'categories'));
    }

    public function store(Request $request)
    {
        $user = request()->user();
        if (!$user) {
            return redirect()->route('login');
        }

        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'title' => 'required|string|max:255',
            'category' => 'required|string',
            'description' => 'nullable|string',
            'document_file' => 'required|file|mimes:pdf,doc,docx,jpg,png|max:20480'
        ]);

        $filePath = $request->file('document_file')->store('employee_documents', 'public');

        \App\Models\Document::create([
            'company_id' => $user->company_id,
            'employee_id' => $request->employee_id,
            'title' => $request->title,
            'category' => $request->category,
            'description' => $request->description,
            'file_path' => $filePath
        ]);

        return redirect()->route('documents.index')->with('success', 'Document uploaded successfully.');
    }

    public function show(\App\Models\Document $document)
    {
        return response()->download(storage_path("app/public/{$document->file_path}"));
    }

    public function destroy(\App\Models\Document $document)
    {
        if (\Illuminate\Support\Facades\Storage::disk('public')->exists($document->file_path)) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($document->file_path);
        }
        $document->delete();
        return redirect()->route('documents.index')->with('success', 'Document deleted successfully.');
    }
}
