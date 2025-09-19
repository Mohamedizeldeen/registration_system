<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Company;
use App\Models\User;
class CompanyController extends Controller
{
    function index()
    {
        $companies = Company::with(['events', 'users'])->get();
        return view('companies.index', compact('companies'));
    }
    function create()
    {
        return view('companies.create');
    }
    function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'nullable|string|max:500',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:100|unique:companies,email',
        ]);

        Company::create([
            'name' => $request->name,
            'address' => $request->address,
            'phone' => $request->phone,
            'email' => $request->email,
        ]);

        $findAcompany = Company::where('email', $request->email)->first();
        User::create([
            'name' => $request->name . ' Admin',
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => 'admin',
            'company_id' => $findAcompany->id,
        ]);

        return redirect()->route('companies.index')
            ->with('success', 'Company created successfully!');
    }
    function show($id)
    {
        $company = Company::with(['events', 'users'])->findOrFail($id);
        return view('companies.show', compact('company'));
    }
    function edit($id)
    {
        $company = Company::with(['events', 'users'])->findOrFail($id);
        return view('companies.edit', compact('company'));
    }
    function update(Request $request, $id)
    {
        $company = Company::findOrFail($id);
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'nullable|string|max:500',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:100|unique:companies,email,' . $company->id,
        ]);

        $company->update([
            'name' => $request->name,
            'address' => $request->address,
            'phone' => $request->phone,
            'email' => $request->email,
        ]);



        return redirect()->route('companies.index')
            ->with('success', 'Company updated successfully!');
    }
    function destroy($id)
    {
        $company = Company::findOrFail($id);
        $findAllUsers = User::where('company_id', $company->id)->get();
        foreach ($findAllUsers as $user) {
            $user->delete();
        }
        $company->delete();

        return redirect()->route('companies.index')
            ->with('success', 'Company deleted successfully!');
    }
}
