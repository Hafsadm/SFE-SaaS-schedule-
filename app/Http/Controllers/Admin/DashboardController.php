<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Store;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $stores = Store::query()
            ->select([
                'id',
                'nom',
                'adresse',
                'ville',
                'pays',
                'phone',
                'ouvert_jusqua',
                'lien_rdv',
                'latitude',
                'longitude',
                'services'
            ])
            ->get();

        return view('admin.dashboard', compact('stores'));
    }

    public function search(Request $request)
    {
        $query = $request->input('query');
        
        $stores = Store::query()
            ->where('ville', 'LIKE', "%{$query}%")
            ->orWhere('pays', 'LIKE', "%{$query}%")
            ->orWhere('adresse', 'LIKE', "%{$query}%")
            ->get();

        return response()->json($stores);
    }

    private function getWeeklySchedule($store)
    {
        $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
        $frenchDays = [
            'monday' => 'Lundi',
            'tuesday' => 'Mardi',
            'wednesday' => 'Mercredi',
            'thursday' => 'Jeudi',
            'friday' => 'Vendredi',
            'saturday' => 'Samedi',
            'sunday' => 'Dimanche'
        ];

        $weeklySchedule = [];
        foreach ($days as $day) {
            $schedule = $store->schedules()->where('day_of_week', $day)->first();
            $weeklySchedule[] = [
                'day' => $frenchDays[$day],
                'schedule' => $schedule ? [
                    'is_closed' => $schedule->is_closed,
                    'is_holiday' => $schedule->is_holiday,
                    'time_slots' => $schedule->time_slots
                ] : null
            ];
        }

        return $weeklySchedule;
    }
} 