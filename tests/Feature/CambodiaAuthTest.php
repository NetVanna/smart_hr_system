<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class CambodiaAuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_login_with_email(): void
    {
        $unique = time() . rand(100, 999);
        $company = Company::create([
            'name'                => 'Test Co ' . $unique,
            'email'               => 'test_' . $unique . '@co.com',
            'subscription_plan'   => '14-Day Free Trial',
            'subscription_status' => 'Active',
        ]);

        $user = User::create([
            'company_id'  => $company->id,
            'name'        => 'Test Admin',
            'email'       => 'testadmin_' . $unique . '@co.com',
            'phone'       => '012' . rand(100000, 999999),
            'employee_id' => 'EMP-' . rand(100, 999),
            'password'    => Hash::make('secret123'),
            'role'        => 'Company Admin',
        ]);

        // 1. Login with Email
        $response = $this->post('/login', [
            'login'    => $user->email,
            'password' => 'secret123',
        ]);
        $response->assertRedirect('/dashboard');
        $this->assertAuthenticatedAs($user);
    }

    public function test_user_can_login_with_phone(): void
    {
        $unique = time() . rand(100, 999);
        $phone = '012' . rand(100000, 999999);
        $company = Company::create([
            'name'                => 'Phone Co ' . $unique,
            'email'               => 'phone_' . $unique . '@co.com',
            'subscription_plan'   => '14-Day Free Trial',
            'subscription_status' => 'Active',
        ]);

        $user = User::create([
            'company_id'  => $company->id,
            'name'        => 'Phone Admin',
            'email'       => 'phoneuser_' . $unique . '@co.com',
            'phone'       => $phone,
            'password'    => Hash::make('secret123'),
            'role'        => 'Company Admin',
        ]);

        $response = $this->post('/login', [
            'login'    => $phone,
            'password' => 'secret123',
        ]);
        $response->assertRedirect('/dashboard');
        $this->assertAuthenticatedAs($user);
    }

    public function test_user_can_login_with_employee_id(): void
    {
        $unique = time() . rand(100, 999);
        $empId = 'EMP-' . rand(1000, 9999);
        $company = Company::create([
            'name'                => 'ID Co ' . $unique,
            'email'               => 'id_' . $unique . '@co.com',
            'subscription_plan'   => '14-Day Free Trial',
            'subscription_status' => 'Active',
        ]);

        $user = User::create([
            'company_id'  => $company->id,
            'name'        => 'ID Admin',
            'email'       => 'iduser_' . $unique . '@co.com',
            'employee_id' => $empId,
            'password'    => Hash::make('secret123'),
            'role'        => 'Company Admin',
        ]);

        $response = $this->post('/login', [
            'login'    => $empId,
            'password' => 'secret123',
        ]);
        $response->assertRedirect('/dashboard');
        $this->assertAuthenticatedAs($user);
    }

    public function test_cambodia_streamlined_registration_grants_instant_free_trial(): void
    {
        $uniqueEmail = 'angkor.' . time() . rand(100, 999) . '@solution.kh';
        $uniquePhone = '012' . rand(100000, 999999);

        $response = $this->post('/register', [
            'company_name'          => 'Angkor Tech Solution',
            'name'                  => 'Sokha Seng',
            'email'                 => $uniqueEmail,
            'phone'                 => $uniquePhone,
            'telegram_username'     => 'sokhakh',
            'password'              => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertAuthenticated();

        $company = Company::where('email', $uniqueEmail)->first();
        $this->assertNotNull($company);
        $this->assertEquals('Active', $company->subscription_status);
        $this->assertEquals('14-Day Free Trial', $company->subscription_plan);
    }

    public function test_language_switcher_persists_in_session(): void
    {
        $responseKh = $this->get('/language/kh');
        $responseKh->assertSessionHas('locale', 'kh');

        $responseEn = $this->get('/language/en');
        $responseEn->assertSessionHas('locale', 'en');
    }
}
