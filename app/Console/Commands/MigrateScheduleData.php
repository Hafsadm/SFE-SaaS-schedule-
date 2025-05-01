<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Schedule;
use App\Models\Exception;
use App\Models\Holiday;

class MigrateScheduleData extends Command
{
    protected $signature = 'schedules:migrate';
    protected $description = 'Migre les données de la table schedules vers les tables exceptions et holidays';

    public function handle()
    {
        $this->info('--- Début de la migration des données ---');

        // Désactiver les contraintes de clés étrangères temporairement
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        try {
            // MIGRATION DES EXCEPTIONS
            $exceptions = DB::table('schedules')
                ->whereNotNull('exception_date')
                ->where('is_holiday', false)
                ->get();

            $this->info('Exceptions trouvées : ' . $exceptions->count());

            foreach ($exceptions as $exception) {
                Exception::create([
                    'store_id' => $exception->store_id,
                    'exception_date' => $exception->exception_date,
                    'exception_raison' => $exception->exception_raison,
                    'is_closed' => $exception->is_closed,
                    'time_slots' => $exception->time_slots,
                ]);
            }

            // MIGRATION DES JOURS FÉRIÉS
            $holidays = DB::table('schedules')
                ->where('is_holiday', true)
                ->get();

            $this->info('Jours fériés trouvés : ' . $holidays->count());

            foreach ($holidays as $holiday) {
                Holiday::create([
                    'store_id' => $holiday->store_id,
                    'holiday_date' => $holiday->exception_date,
                    'holiday_name' => $holiday->exception_raison,
                ]);
            }

            $this->info('✅ Migration terminée avec succès !');

        } catch (\Exception $e) {
            $this->error('❌ Erreur lors de la migration : ' . $e->getMessage());
            Log::error('Erreur de migration des schedules', ['exception' => $e]);
        } finally {
            // Réactiver les contraintes de clés étrangères
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        }
    }
}
