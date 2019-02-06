<?php

namespace App\Http\Controllers;

use App\Company;
use App\Investment;
use Illuminate\Http\Request;

class CompaniesController extends Controller
{
	/**
	 * All companies
	 *
	 * @return view
	 */
    public function index()
    {
    	$companies = Company::with('investments')->get();

    	// 1. Open the view and implement the dollars raised
    	return view('companies.index', compact('companies'));
    }

    /**
     * Show a single company
     *
     * @param  int $id
     * @return view
     */
    public function show($id)
    {
    	// 2. Get the Company from its id
        // Always use ::query() to avoid the PAIN of static analyzers and
        // it's also more performant (it's what Eloquent's ::__call() proxies to).
        $company = Company::query()->findOrFail($id);
        $totalInvestments = $company->totalInvestments;

    	// 3. Create a view in /resources/views/companies to display the
    	//    company name, logo, and total dollars raised
        return view('companies.show', compact('company', 'totalInvestments'));
    }

    /**
     * Use VueJs to show a company
     *
     * @param  int $id
     * @return view
     */
    public function showVue($id)
    {
        return view('companies.show-vue', compact('id'));
    }

    /**
     * Create a company
     *
     * @param  Request $request
     * @return response
     */
    public function store(Request $request)
    {
    	$company = Company::create(['name' => $request->name, 'logo' => $request->logo]);
    	// Note: A test has been started for this method. Please complete it as
    	//       described in the CompaniesTest.
    	// 4. Treat this as an API endpoint. Use Postman to test.
    	// 5. Validate that the name and logo fields are required.
    	//    If validation fails, return an error message with what you feel
    	//    the appropriate response code is
    	// 6. Persist the company.
    	// 7. Add a test for validation errors

    	return $company;
    }

    /**
     * Update a company
     *
     * @param  int  $id
     * @param  Request $request
     * @return response
     */
    public function update($id, Request $request)
    {
    	// 7. Treat this as an API endpoint
    	// 8. Validate that the name and logo fields are required.
    	//    If validation fails, return an error message with what you feel
    	//    the appropriate response code is
    	// 9. Persist the company.
    	// 10. Write a test for this. Be sure to include a failure test!
    }

    /**
     * Delete a company
     *
     * @param  int $id
     * @return response
     */
    public function destroy($id)
    {
    	$company = Company::findOrFail($id);
    	$company->delete();

    	return response("Company {$company->name} was deleted.");
    }
}
