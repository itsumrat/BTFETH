<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{Investment, User};
use Illuminate\Http\Request;
use Carbon\Carbon;

class InvestmentController extends Controller
{
    // Plan defaults
    const PLANS = [
        'Plan 1' => ['profit' => 1.5,  'duration' => 7,  'cycles' => 2,  'min' => 500,   'max' => 1000],
        'Plan 2' => ['profit' => 3.2,  'duration' => 15, 'cycles' => 3,  'min' => 10000, 'max' => 15000],
        'VIP 1'  => ['profit' => 5.0,  'duration' => 28, 'cycles' => 6,  'min' => 25000, 'max' => 50000],
        'VIP 2'  => ['profit' => 7.0,  'duration' => 28, 'cycles' => 5,  'min' => 50000, 'max' => null],
    ];

    public function index()
    {
        $investments = Investment::with('user')
            ->orderByRaw("FIELD(status,'active','completed','cancelled')")
            ->orderBy('created_at','desc')
            ->paginate(20);

        $customers = User::where('is_admin', false)->where('is_active', true)->orderBy('name')->get();

        return view('admin.investments', compact('investments', 'customers'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'user_id'        => 'required|exists:users,id',
            'plan_name'      => 'required|string',
            'amount'         => 'required|numeric|min:1',
            'profit_percent' => 'required|numeric|min:0.01|max:100',
            'duration_days'  => 'required|integer|min:1',
            'total_cycles'   => 'required|integer|min:1',
            'start_date'     => 'required|date',
            'notes'          => 'nullable|string|max:500',
        ]);

        $data['start_date'] = Carbon::parse($data['start_date']);
        $data['end_date']   = $data['start_date']->copy()->addDays($data['duration_days']);
        $data['status']     = 'active';

        Investment::create($data);

        return back()->with('success', 'Investment plan created successfully.');
    }

    public function update(Request $request, Investment $investment)
    {
        $data = $request->validate([
            'plan_name'         => 'required|string',
            'amount'            => 'required|numeric|min:1',
            'profit_percent'    => 'required|numeric|min:0.01|max:100',
            'duration_days'     => 'required|integer|min:1',
            'total_cycles'      => 'required|integer|min:1',
            'completed_cycles'  => 'required|integer|min:0',
            'start_date'        => 'required|date',
            'status'            => 'required|in:active,completed,cancelled',
            'notes'             => 'nullable|string|max:500',
        ]);

        $data['start_date'] = Carbon::parse($data['start_date']);
        $data['end_date']   = $data['start_date']->copy()->addDays($data['duration_days']);

        $investment->update($data);

        return back()->with('success', 'Investment updated successfully.');
    }

    public function destroy(Investment $investment)
    {
        $investment->delete();
        return back()->with('success', 'Investment deleted.');
    }

    public function planDefaults(Request $request)
    {
        $plan = self::PLANS[$request->plan] ?? null;
        return response()->json($plan);
    }
}
