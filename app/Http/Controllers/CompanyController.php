<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Company;
use App\Http\Requests\Company\CreateCompanyRequest;

class CompanyController extends Controller
{
    public function index()
    {
        $companies = Company::orderBy('name')->get();
        return view('companies.index', compact('companies'));
    }

    public function create()
    {
        return view('companies.create');
    }

    public function store(CreateCompanyRequest $request)
    {
        $data = $request->only(['name', 'address', 'phone', 'email', 'website']);
        $data['user_id'] = auth()->user()->id;
        Company::create($data);
        return redirect()->route('companies')->with('success', 'Компания успешно создана');
    }

    public function edit(Company $company)
    {
        return view('companies.edit', compact('company'));
    }

    public function update(CreateCompanyRequest $request, Company $company)
    {
        $data = $request->only(['name', 'address', 'phone', 'email', 'website']);
        $data['user_id'] = auth()->user()->id;
        $company->update($data);
        return redirect()->route('companies')->with('success', 'Компания успешно обновлена');
    }

    public function delete(Company $company)
    {
        $company->delete();
        return redirect()->route('companies')->with('success', 'Компания успешно удалена');
    }
}
