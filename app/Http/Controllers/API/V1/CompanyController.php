<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Requests\Companies\CompanyRequest;
use App\Models\Company;
use Illuminate\Http\Request;

class CompanyController extends BaseController
{

    protected $company = '';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Company $company)
    {
        $this->middleware('auth:api');
        $this->company = $company;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $companies = $this->company->latest();
        if ($request->has('name')) {
            $companies = $companies->where('company_name', 'LIKE', '%'.$request->get('name').'%');
        }
        if ($request->has('favorite') && $request->get('favorite')) {
            $companies = $companies->where('is_favorite', 1);
        }
        $companies = $companies->paginate(10);
        return $this->sendResponse($companies, 'Company list');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  App\Http\Requests\Companies\CompanyRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CompanyRequest $request)
    {
        $company = $this->company->create($request->validated(),true);
        return $this->sendResponse($company, 'Company Created Successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $company = $this->company->findOrFail($id);
        return $this->sendResponse($company, 'Company Details');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function update(CompanyRequest $request, $id)
    {
        $company = $this->company->findOrFail($id);
        $company->update($request->validated());
        return $this->sendResponse($company, 'Company Information has been updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        $this->authorize('isAdmin');
        $company = $this->company->findOrFail($id);
        $company->delete();
        return $this->sendResponse($company, 'Company has been Deleted');
    }

    
}
