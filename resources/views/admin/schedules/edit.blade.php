<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Modifier l\'horaire pour : ') }} {{ $service->nom_service }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form method="POST" action="{{ route('admin.schedules.update', [$service, $schedule]) }}" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <div>
                            <x-input-label for="jour" :value="__('Jour')" />
                            <select id="jour" name="jour" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                <option value="">Sélectionnez un jour</option>
                                <option value="Lundi" {{ old('jour', $schedule->jour) == 'Lundi' ? 'selected' : '' }}>Lundi</option>
                                <option value="Mardi" {{ old('jour', $schedule->jour) == 'Mardi' ? 'selected' : '' }}>Mardi</option>
                                <option value="Mercredi" {{ old('jour', $schedule->jour) == 'Mercredi' ? 'selected' : '' }}>Mercredi</option>
                                <option value="Jeudi" {{ old('jour', $schedule->jour) == 'Jeudi' ? 'selected' : '' }}>Jeudi</option>
                                <option value="Vendredi" {{ old('jour', $schedule->jour) == 'Vendredi' ? 'selected' : '' }}>Vendredi</option>
                                <option value="Samedi" {{ old('jour', $schedule->jour) == 'Samedi' ? 'selected' : '' }}>Samedi</option>
                                <option value="Dimanche" {{ old('jour', $schedule->jour) == 'Dimanche' ? 'selected' : '' }}>Dimanche</option>
                            </select>
                            <x-input-error :messages="$errors->get('jour')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="heure_ouverture" :value="__('Heure d\'ouverture')" />
                            <x-text-input id="heure_ouverture" name="heure_ouverture" type="time" class="mt-1 block w-full" :value="old('heure_ouverture', $schedule->heure_ouverture)" required />
                            <x-input-error :messages="$errors->get('heure_ouverture')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="heure_fermeture" :value="__('Heure de fermeture')" />
                            <x-text-input id="heure_fermeture" name="heure_fermeture" type="time" class="mt-1 block w-full" :value="old('heure_fermeture', $schedule->heure_fermeture)" required />
                            <x-input-error :messages="$errors->get('heure_fermeture')" class="mt-2" />
                        </div>

                        <div class="flex items-center gap-4">
                            <x-primary-button>{{ __('Mettre à jour') }}</x-primary-button>
                            <a href="{{ route('admin.schedules.index', $service) }}" class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                {{ __('Annuler') }}
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 