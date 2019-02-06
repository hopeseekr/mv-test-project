<?php

namespace Tests\Feature;

use App\Company;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CompaniesTest extends TestCase
{
    use DatabaseTransactions;
    
    /** @test */
    public function it_shows_the_companies_page()
    {
        $response = $this->get('/companies');

        $response->assertStatus(200);
    }

    /** @test */
    public function it_creates_a_company()
    {
        $createParams = [
            'name' => 'Hello',
            'logo' => 'http://logo.com/logo.gif',
        ];
        $response = $this->json('POST', '/companies', $createParams);

        $response->assertStatus(200);

        // Add a database assertion here
        $this->assertDatabaseHas('companies', $createParams);
    }

    // Write other tests
    /** @test */
    public function it_requires_a_company_name()
    {
        try {
            Company::create(['logo' => 'http://asdfasdf']);
            $this->fail('It created a company without a name...');
        } catch (ValidationException $e) {
            $this->assertEquals(
                ['The name field is required.'],
                $e->getResponse()->get('name')
            );
        }
    }

    /** @test */
    public function it_requires_a_company_logo()
    {
        try {
            Company::create(['name' => 'PHP Experts, Inc.']);
            $this->fail('It created a company without a logo...');
        } catch (ValidationException $e) {
            $this->assertEquals(
                ['The logo field is required.'],
                $e->getResponse()->get('logo')
            );
        }
    }

    /** @test */
    public function it_deletes_a_company()
    {
        $company = factory(Company::class)->create();

        $response = $this->json('DELETE', '/companies/' . $company->id);
        $response->assertStatus(200);

        $this->assertDatabaseMissing('companies', ['id' => $company->id]);
    }
}
