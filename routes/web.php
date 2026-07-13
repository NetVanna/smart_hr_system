<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\AuthController;
use App\Http\Controllers\Web\DashboardController;
use App\Http\Controllers\Web\OnboardingController;
use App\Http\Controllers\Web\LandingController;

Route::get('/', [LandingController::class, 'index'])->name('landing');

Route::get('/language/{lang}', function ($lang) {
    if (in_array($lang, ['en', 'kh'])) {
        session(['locale' => $lang]);
        session()->save(); // Force session to persist before redirect
    }
    return redirect(url()->previous('/'));
})->name('locale.change');

use App\Http\Controllers\Web\RegisterController;

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');

    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register'])->name('register.post');

    // Password Reset Routes
    Route::get('password/reset', [\App\Http\Controllers\Web\ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('password/email', [\App\Http\Controllers\Web\ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('password/reset/{token}', [\App\Http\Controllers\Web\ResetPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('password/reset', [\App\Http\Controllers\Web\ResetPasswordController::class, 'reset'])->name('password.update');
});

// Onboarding flow (auth required, subscription check bypassed in middleware)
Route::middleware('auth')->prefix('onboarding')->name('onboarding.')->group(function () {
    Route::get('/introduction', [OnboardingController::class, 'introduction'])->name('introduction');
    Route::post('/introduction', [OnboardingController::class, 'introductionNext'])->name('introduction.next');
    Route::get('/plans', [OnboardingController::class, 'plans'])->name('plans');
    Route::post('/plans', [OnboardingController::class, 'selectPlan'])->name('plans.select');
    Route::get('/payment', [OnboardingController::class, 'payment'])->name('payment');
    Route::post('/payment', [OnboardingController::class, 'submitProof'])->name('payment.submit');
    Route::get('/pending', [OnboardingController::class, 'pending'])->name('pending');
});

Route::middleware(['auth', 'subscription'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('departments', \App\Http\Controllers\Web\DepartmentController::class);
    Route::get('/employees/export', [\App\Http\Controllers\Web\EmployeeController::class, 'export'])->name('employees.export');
    Route::post('/employees/import', [\App\Http\Controllers\Web\EmployeeController::class, 'import'])->name('employees.import');
    Route::post('/employees', [\App\Http\Controllers\Web\EmployeeController::class, 'store'])->middleware('quota:employee')->name('employees.store');
    Route::resource('employees', \App\Http\Controllers\Web\EmployeeController::class)->except(['store']);
    Route::get('/employees/{employee}/qr', [\App\Http\Controllers\Web\AttendanceController::class, 'showQr'])->name('employees.qr');
    Route::get('/employees/{employee}/id-card', [\App\Http\Controllers\Web\EmployeeController::class, 'showIdCard'])->name('employees.id_card');
    Route::get('/attendances', [\App\Http\Controllers\Web\AttendanceController::class, 'index'])->name('attendances.index');
    Route::resource('leaves', \App\Http\Controllers\Web\LeaveController::class);
    Route::resource('payrolls', \App\Http\Controllers\Web\PayrollController::class);
    Route::resource('documents', \App\Http\Controllers\Web\DocumentController::class);
    Route::post('/assets', [\App\Http\Controllers\Web\AssetController::class, 'store'])->middleware('quota:asset')->name('assets.store');
    Route::resource('assets', \App\Http\Controllers\Web\AssetController::class)->except(['store']);
    Route::resource('expenses', \App\Http\Controllers\Web\ExpenseController::class);
    Route::post('/expenses/{expense}/approve', [\App\Http\Controllers\Web\ExpenseController::class, 'approve'])->name('expenses.approve');
    Route::post('/expenses/{expense}/reject', [\App\Http\Controllers\Web\ExpenseController::class, 'reject'])->name('expenses.reject');
    Route::resource('evaluations', \App\Http\Controllers\Web\PerformanceEvaluationController::class);
    Route::resource('trainings', \App\Http\Controllers\Web\TrainingController::class);
    Route::post('/trainings/{training}/enroll', [\App\Http\Controllers\Web\TrainingController::class, 'enroll'])->name('trainings.enroll');
    Route::post('/trainings/participant/{participant}/status', [\App\Http\Controllers\Web\TrainingController::class, 'updateStatus'])->name('trainings.update_status');
    Route::get('/skills/matrix', [\App\Http\Controllers\Web\SkillMatrixController::class, 'index'])->name('skills.matrix.index');
    Route::post('/skills/matrix', [\App\Http\Controllers\Web\SkillMatrixController::class, 'store'])->name('skills.matrix.store');
    Route::post('/skills/matrix/update', [\App\Http\Controllers\Web\SkillMatrixController::class, 'updateProficiency'])->name('skills.matrix.update');
    Route::get('/recruitment', [\App\Http\Controllers\Web\RecruitmentController::class, 'index'])->name('recruitment.index');
    Route::get('/recruitment/jobs/create', [\App\Http\Controllers\Web\RecruitmentController::class, 'createJob'])->name('recruitment.jobs.create');
    Route::post('/recruitment/jobs', [\App\Http\Controllers\Web\RecruitmentController::class, 'storeJob'])->name('recruitment.jobs.store');
    Route::get('/recruitment/jobs/{job}', [\App\Http\Controllers\Web\RecruitmentController::class, 'showJob'])->name('recruitment.jobs.show');
    Route::get('/recruitment/applicants', [\App\Http\Controllers\Web\RecruitmentController::class, 'applicants'])->name('recruitment.applicants.index');
    Route::get('/recruitment/applicants/{applicant}', [\App\Http\Controllers\Web\RecruitmentController::class, 'showApplicant'])->name('recruitment.applicants.show');
    Route::post('/recruitment/applicants/{applicant}/status', [\App\Http\Controllers\Web\RecruitmentController::class, 'updateApplicantStatus'])->name('recruitment.applicants.update_status');
    Route::post('/recruitment/applicants/{applicant}/schedule', [\App\Http\Controllers\Web\RecruitmentController::class, 'scheduleInterview'])->name('recruitment.applicants.schedule');
    Route::post('/recruitment/applicants/{applicant}/hire', [\App\Http\Controllers\Web\RecruitmentController::class, 'hireApplicant'])->name('recruitment.applicants.hire');
    Route::get('/audit-logs', [\App\Http\Controllers\Web\AuditLogController::class, 'index'])->name('audit.index');
    Route::get('/health-monitor', [\App\Http\Controllers\Web\HealthMonitorController::class, 'index'])->name('health.index');
    Route::get('/verify-two-factor', [\App\Http\Controllers\Auth\TwoFactorController::class, 'index'])->name('verify.two_factor.index');
    Route::post('/verify-two-factor', [\App\Http\Controllers\Auth\TwoFactorController::class, 'verify'])->name('verify.two_factor');
    Route::get('/resend-two-factor', [\App\Http\Controllers\Auth\TwoFactorController::class, 'resend'])->name('resend.two_factor');
    Route::get('/shifts', [\App\Http\Controllers\Web\ShiftController::class, 'index'])->name('shifts.index');
    Route::post('/shifts', [\App\Http\Controllers\Web\ShiftController::class, 'store'])->name('shifts.store');
    Route::post('/shifts/assign', [\App\Http\Controllers\Web\ShiftController::class, 'assign'])->name('shifts.assign');
    Route::get('/shifts/monitor', [\App\Http\Controllers\Web\ShiftController::class, 'monitor'])->name('shifts.monitor');
    Route::resource('tickets', \App\Http\Controllers\Web\TicketController::class);
    Route::get('/settings/company', [\App\Http\Controllers\Web\CompanySettingsController::class, 'edit'])->name('settings.company.edit');
    Route::put('/settings/company', [\App\Http\Controllers\Web\CompanySettingsController::class, 'update'])->name('settings.company.update');
    Route::get('/holidays', [\App\Http\Controllers\Web\HolidayController::class, 'index'])->name('holidays.index');
    Route::get('/subscription/payment', function() {
        return view('subscriptions.payment');
    })->name('subscription.payment');
    Route::post('/subscription/payment', [\App\Http\Controllers\Web\SuperAdmin\SubscriptionController::class, 'submitProof'])->name('subscription.payment.submit');
});

// Super Admin Routes
Route::middleware(['auth', 'role:Super Admin'])->prefix('superadmin')->name('superadmin.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Web\SuperAdmin\DashboardController::class, 'index'])->name('dashboard');
    Route::resource('companies', \App\Http\Controllers\Web\SuperAdmin\CompanyController::class);
    Route::resource('subscriptions', \App\Http\Controllers\Web\SuperAdmin\SubscriptionController::class);
    Route::patch('/subscriptions/{subscription}/approve', [\App\Http\Controllers\Web\SuperAdmin\SubscriptionController::class, 'approve'])->name('subscriptions.approve');
    Route::patch('/tickets/{ticket}/status', [\App\Http\Controllers\Web\TicketController::class, 'updateStatus'])->name('tickets.updateStatus');
    Route::post('/companies/reset-password/{username}', [\App\Http\Controllers\Web\SuperAdmin\CompanyController::class, 'resetAdminPassword'])->name('companies.reset-password');
    Route::post('/companies/magic-link/{username}', [\App\Http\Controllers\Web\SuperAdmin\CompanyController::class, 'generateMagicLink'])->name('companies.magic-link');
});

Route::get('/login/magic/{token}', [\App\Http\Controllers\Web\Auth\MagicLoginController::class, 'login'])->name('login.magic');

// Public Job Board Routes
Route::get('/jobs', [\App\Http\Controllers\Web\PublicRecruitmentController::class, 'index'])->name('public.jobs.index');
Route::get('/jobs/{job}', [\App\Http\Controllers\Web\PublicRecruitmentController::class, 'show'])->name('public.jobs.show');
Route::post('/jobs/{job}/apply', [\App\Http\Controllers\Web\PublicRecruitmentController::class, 'apply'])->name('public.jobs.apply');

