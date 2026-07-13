@extends('layouts.app')

@section('title', 'Skill Matrix')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3><i class="fa-solid fa-layer-group me-2"></i> Skill Matrix</h3>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addSkillModal">
        <i class="fa-solid fa-plus me-1"></i> Add New Skill
    </a>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-bordered align-middle mb-0 text-center">
                <thead class="bg-light">
                    <tr>
                        <th class="text-start ps-4" style="min-width: 200px;">Employee</th>
                        @foreach($skills as $skill)
                        <th title="{{ $skill->category }}">
                            <small class="d-block text-muted">{{ $skill->category }}</small>
                            {{ $skill->name }}
                        </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach($employees as $employee)
                    <tr>
                        <td class="text-start ps-4">
                            <div class="d-flex align-items-center">
                                @if($employee->profile_photo)
                                    <img src="{{ asset('storage/' . $employee->profile_photo) }}" alt="Photo" class="rounded-circle me-2" width="32" height="32" style="object-fit:cover;">
                                @else
                                    <div class="bg-light rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 32px; height: 32px;">
                                        <span class="text-secondary small fw-bold">{{ substr($employee->first_name, 0, 1) }}</span>
                                    </div>
                                @endif
                                <div>
                                    <div class="fw-bold">{{ $employee->first_name }} {{ $employee->last_name }}</div>
                                    <small class="text-muted">{{ $employee->position }}</small>
                                </div>
                            </div>
                        </td>
                        @foreach($skills as $skill)
                        @php
                            $empSkill = $employee->skills->where('id', $skill->id)->first();
                            $level = $empSkill ? $empSkill->pivot->proficiency_level : 0;
                        @endphp
                        <td class="p-0">
                            <select class="form-select form-select-sm border-0 proficiency-select text-center" 
                                    data-employee-id="{{ $employee->id }}" 
                                    data-skill-id="{{ $skill->id }}"
                                    style="background-color: {{ $level > 0 ? 'rgba(102, 126, 234, ' . ($level * 0.15) . ')' : 'transparent' }};">
                                <option value="0" {{ $level == 0 ? 'selected' : '' }}>-</option>
                                <option value="1" {{ $level == 1 ? 'selected' : '' }}>1</option>
                                <option value="2" {{ $level == 2 ? 'selected' : '' }}>2</option>
                                <option value="3" {{ $level == 3 ? 'selected' : '' }}>3</option>
                                <option value="4" {{ $level == 4 ? 'selected' : '' }}>4</option>
                                <option value="5" {{ $level == 5 ? 'selected' : '' }}>5</option>
                            </select>
                        </td>
                        @endforeach
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add Skill Modal -->
<div class="modal fade" id="addSkillModal" tabindex="-1">
    <div class="modal-dialog">
        <form action="{{ route('skills.matrix.store') }}" method="POST">
            @csrf
            <div class="modal-content text-start">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Skill to Matrix</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Skill Name</label>
                        <input type="text" name="name" class="form-control" required placeholder="e.g. PHP, Public Speaking">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Category</label>
                        <input type="text" name="category" class="form-control" placeholder="e.g. Technical, Soft Skills">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add Skill</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
document.querySelectorAll('.proficiency-select').forEach(select => {
    select.addEventListener('change', function() {
        const employeeId = this.dataset.employeeId;
        const skillId = this.dataset.skillId;
        const level = this.value;

        fetch('{{ route("skills.matrix.update") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                employee_id: employeeId,
                skill_id: skillId,
                proficiency_level: level
            })
        }).then(response => {
            if(response.ok) {
                this.style.backgroundColor = `rgba(102, 126, 234, ${level * 0.15})`;
            }
        });
    });
});
</script>
@endsection
