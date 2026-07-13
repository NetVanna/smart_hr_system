@extends('layouts.app')

@section('title', 'Document Management')

@section('content')
<<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fa-solid fa-folder-open me-2 text-primary"></i> Document Vault</h2>
    <a href="{{ route('documents.create') }}" class="btn btn-primary"><i class="fa-solid fa-upload"></i> Upload Document</a>
</div>

<div class="mb-4">
    <div class="btn-group" role="group">
        <a href="{{ route('documents.index') }}" class="btn btn-outline-primary {{ !request('category') || request('category') == 'All' ? 'active' : '' }}">All</a>
        @foreach($categories as $cat)
            <a href="{{ route('documents.index', ['category' => $cat]) }}" 
               class="btn btn-outline-primary {{ request('category') == $cat ? 'active' : '' }}">
                {{ $cat }}
            </a>
        @endforeach
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4">Title & Category</th>
                        <th>Employee</th>
                        <th>Uploaded At</th>
                        <th class="pe-4 text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($documents as $doc)
                    <tr>
                        <td class="ps-4">
                            <div class="d-flex align-items-center">
                                <div class="bg-light p-2 rounded me-3 text-center" style="width: 40px;">
                                    <i class="fa-solid fa-file-pdf text-danger"></i>
                                </div>
                                <div>
                                    <strong class="d-block">{{ $doc->title }}</strong>
                                    <span class="badge bg-secondary-subtle text-secondary small">{{ $doc->category }}</span>
                                    @if($doc->description)
                                        <small class="text-muted d-block mt-1">{{ Str::limit($doc->description, 50) }}</small>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td>
                            <strong>{{ optional($doc->employee)->first_name }} {{ optional($doc->employee)->last_name }}</strong><br>
                            <small class="text-muted">{{ optional($doc->employee)->employee_id }}</small>
                        </td>
                        <td>{{ \Carbon\Carbon::parse($doc->created_at)->format('d M Y') }}</td>
                        <td class="pe-4 text-end">
                            <a href="{{ route('documents.show', $doc) }}" class="btn btn-sm btn-outline-primary" title="Download">
                                <i class="fa-solid fa-download"></i>
                            </a>
                            @if(auth()->user()->role === 'Super Admin' || auth()->user()->role === 'Company Admin' || auth()->user()->role === 'HR Manager')
                            <form action="{{ route('documents.destroy', $doc) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this document?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </form>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center py-5 text-muted">
                            <i class="fa-solid fa-folder-open f-large d-block mb-3 opacity-25" style="font-size: 3rem;"></i>
                            No documents found in this category.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
