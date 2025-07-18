<?php

// app/Http/Controllers/SalaryInfoController.php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\SalaryInfo;
use Illuminate\Http\Request;

class SalaryInfoController extends Controller
{
    public function index()
    {
        $salaryInfos = SalaryInfo::with('user')->get();
        return view('salary-info.index', compact('salaryInfos'));
    }

    public function create()
    {
        $users = User::doesntHave('salaryInfo')->get();
        return view('salary-info.create', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'basic_salary' => 'required|numeric',
            'ot_rate_per_hour' => 'required|numeric',
            'late_deduction_per_hour' => 'required|numeric',
        ]);

        SalaryInfo::create($request->all());

        return redirect()->route('salary-info.index')->with('success', 'Salary info added.');
    }

    public function edit($id)
    {
        $salaryInfo = SalaryInfo::with('user')->findOrFail($id);
        return view('salary-info.edit', compact('salaryInfo'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'basic_salary' => 'required|numeric',
            'ot_rate_per_hour' => 'required|numeric',
            'late_deduction_per_hour' => 'required|numeric',
        ]);

        $salaryInfo = SalaryInfo::findOrFail($id);
        $salaryInfo->update($request->all());

        return redirect()->route('salary-info.index')->with('success', 'Salary info updated.');
    }
}
