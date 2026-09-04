<?php

namespace App\Http\Controllers\Web\Company;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Display a listing of company users and team members.
     */
    public function index(Request $request)
    {
        $companyId = auth()->user()->company_id;

        $query = User::where('company_id', $companyId);

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('telegram_username', 'like', "%{$search}%");
            });
        }

        $users = $query->latest()->paginate(12)->withQueryString();

        $stats = [
            'total'        => User::where('company_id', $companyId)->count(),
            'admins'       => User::where('company_id', $companyId)->where('role', 'Company Admin')->count(),
            'hrManagers'   => User::where('company_id', $companyId)->where('role', 'HR Manager')->count(),
            'employees'    => User::where('company_id', $companyId)->where('role', 'Employee')->count(),
        ];

        return view('company.users.index', compact('users', 'stats'));
    }

    /**
     * Show the form for creating a new user / inviting a team member.
     */
    public function create()
    {
        $companyId = auth()->user()->company_id;
        $employees = Employee::where('company_id', $companyId)->orderBy('first_name')->get();

        return view('company.users.create', compact('employees'));
    }

    /**
     * Store a newly created user in storage.
     */
    public function store(Request $request)
    {
        $companyId = auth()->user()->company_id;

        $validated = $request->validate([
            'name'              => ['required', 'string', 'max:255'],
            'email'             => ['required', 'email', 'max:255', 'unique:users,email'],
            'phone'             => ['nullable', 'string', 'max:20', 'unique:users,phone'],
            'telegram_username' => ['nullable', 'string', 'max:100'],
            'role'              => ['required', 'string', Rule::in(['Company Admin', 'HR Manager', 'Employee'])],
            'password'          => ['required', 'string', 'min:6', 'confirmed'],
            'employee_id'       => ['nullable', 'string', 'max:50'],
        ]);

        $telegram = $validated['telegram_username'] ?? null;
        if ($telegram) {
            $telegram = ltrim($telegram, '@');
        }

        $user = User::create([
            'name'              => $validated['name'],
            'email'             => $validated['email'],
            'phone'             => $validated['phone'] ?? null,
            'telegram_username' => $telegram,
            'role'              => $validated['role'],
            'password'          => Hash::make($validated['password']),
            'company_id'        => $companyId,
            'employee_id'       => $validated['employee_id'] ?? null,
        ]);

        return redirect()->route('company.users.index')
            ->with('success', "✅ Team member {$user->name} has been created with role: {$user->role}.");
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $user)
    {
        $this->authorizeCompanyAccess($user);

        $companyId = auth()->user()->company_id;
        $employees = Employee::where('company_id', $companyId)->orderBy('first_name')->get();

        return view('company.users.edit', compact('user', 'employees'));
    }

    /**
     * Update the specified user in storage.
     */
    public function update(Request $request, User $user)
    {
        $this->authorizeCompanyAccess($user);

        $validated = $request->validate([
            'name'              => ['required', 'string', 'max:255'],
            'email'             => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'phone'             => ['nullable', 'string', 'max:20', Rule::unique('users', 'phone')->ignore($user->id)],
            'telegram_username' => ['nullable', 'string', 'max:100'],
            'role'              => ['required', 'string', Rule::in(['Company Admin', 'HR Manager', 'Employee'])],
            'password'          => ['nullable', 'string', 'min:6', 'confirmed'],
            'employee_id'       => ['nullable', 'string', 'max:50'],
        ]);

        // Prevent removing the last Company Admin
        if ($user->role === 'Company Admin' && $validated['role'] !== 'Company Admin') {
            $adminCount = User::where('company_id', auth()->user()->company_id)
                ->where('role', 'Company Admin')
                ->count();
            if ($adminCount <= 1) {
                return back()->with('error', 'Cannot downgrade the only Company Admin. Assign another admin first.');
            }
        }

        $telegram = $validated['telegram_username'] ?? null;
        if ($telegram) {
            $telegram = ltrim($telegram, '@');
        }

        $data = [
            'name'              => $validated['name'],
            'email'             => $validated['email'],
            'phone'             => $validated['phone'] ?? null,
            'telegram_username' => $telegram,
            'role'              => $validated['role'],
            'employee_id'       => $validated['employee_id'] ?? null,
        ];

        if (!empty($validated['password'])) {
            $data['password'] = Hash::make($validated['password']);
        }

        $user->update($data);

        return redirect()->route('company.users.index')
            ->with('success', "✅ Team member {$user->name} has been updated successfully.");
    }

    /**
     * Remove the specified user from company storage.
     */
    public function destroy(User $user)
    {
        $this->authorizeCompanyAccess($user);

        // Cannot delete self
        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot delete your own account.');
        }

        // Cannot delete the only Company Admin
        if ($user->role === 'Company Admin') {
            $adminCount = User::where('company_id', auth()->user()->company_id)
                ->where('role', 'Company Admin')
                ->count();
            if ($adminCount <= 1) {
                return back()->with('error', 'Cannot delete the only Company Admin in this organization.');
            }
        }

        $name = $user->name;
        $user->delete();

        return redirect()->route('company.users.index')
            ->with('success', "✅ User \"{$name}\" has been removed from company workspace.");
    }

    /**
     * Authorize that the user belongs to the current company.
     */
    protected function authorizeCompanyAccess(User $user): void
    {
        if ($user->company_id !== auth()->user()->company_id) {
            abort(403, 'Unauthorized company access.');
        }
    }
}
