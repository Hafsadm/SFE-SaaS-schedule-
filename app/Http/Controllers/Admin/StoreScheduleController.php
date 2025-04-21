<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Store;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StoreScheduleController extends Controller
{
    public function index(Store $store)
    {
        $schedules = $store->schedules()->get();
        return view('admin.schedules.index', compact('store', 'schedules'));
    }

    public function create(Request $request, Store $store)
    {
        $type = $request->get('type');
        if (!in_array($type, ['regular', 'exception', 'holiday'])) {
            return redirect()->route('admin.stores.schedules.select-type', $store)
                ->with('error', 'Type d\'horaire invalide.');
        }
        return view("admin.schedules.{$type}", compact('store'));
    }

    public function store(Request $request, Store $store)
    {
        DB::beginTransaction();
        try {
            $data = [
                'store_id' => $store->id,
                'is_closed' => $request->boolean('is_closed', false),
                'time_slots' => $request->input('time_slots', [])
            ];

            switch ($request->input('type')) {
                case 'regular':
                    $data['day_of_week'] = $request->input('day_of_week');
                    break;

                case 'exception':
                    $data['exception_date'] = $request->input('exception_date');
                    $data['exception_reason'] = $request->input('exception_reason');
                    break;

                case 'holiday':
                    $data['is_holiday'] = true;
                    $data['exception_date'] = $request->input('exception_date');
                    $data['exception_reason'] = $request->input('exception_reason');
                    break;
            }

            Schedule::create($data);

            DB::commit();
            return redirect()->route('admin.stores.schedules.index', $store)
                ->with('success', 'L\'horaire a été créé avec succès.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Une erreur est survenue lors de la création de l\'horaire.');
        }
    }

    public function update(Request $request, Store $store)
    {
        DB::beginTransaction();
        try {
            foreach ($request->schedules as $day => $data) {
                $schedule = $store->schedules()->updateOrCreate(
                    ['day_of_week' => $day],
                    [
                        'is_closed' => $data['is_closed'] ?? false,
                        'is_holiday' => $data['is_holiday'] ?? false,
                        'exception_date' => $data['exception_date'] ?? null,
                        'exception_reason' => $data['exception_reason'] ?? null,
                        'time_slots' => $data['time_slots'] ?? []
                    ]
                );
            }

            DB::commit();
            return redirect()->route('admin.stores.schedules.index', $store)
                ->with('success', 'Les horaires ont été mis à jour avec succès.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Une erreur est survenue lors de la mise à jour des horaires.');
        }
    }
} 