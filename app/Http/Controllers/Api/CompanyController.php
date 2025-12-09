<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCompanyRequest;
use App\Http\Requests\UpdateCompanyRequest;
use App\Http\Resources\CompanyResource;
use App\Models\Company;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    public function index(Request $request)
    {
        $query = Company::with(['projects', 'placements']);

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('contact_person', 'like', "%{$search}%")
                  ->orWhere('contact_email', 'like', "%{$search}%");
            });
        }

        if ($request->has('type')) {
            $query->where('type', $request->type);
        }

        $sortBy = $request->get('sort_by', 'name');
        $sortOrder = $request->get('sort_order', 'asc');
        $query->orderBy($sortBy, $sortOrder);

        $perPage = $request->get('per_page', 15);
        $companies = $query->paginate($perPage);

        return CompanyResource::collection($companies);
    }

    public function store(StoreCompanyRequest $request)
    {
        $this->authorize('create', Company::class);

        $company = Company::create($request->validated());

        return new CompanyResource($company);
    }

    public function show(Company $company)
    {
        return new CompanyResource($company->load(['projects', 'placements']));
    }

    public function update(UpdateCompanyRequest $request, Company $company)
    {
        $this->authorize('update', $company);

        $company->update($request->validated());

        return new CompanyResource($company);
    }

    public function destroy(Company $company)
    {
        $this->authorize('delete', $company);

        $company->delete();

        return response()->json(['message' => 'Company deleted successfully'], 200);
    }
}

