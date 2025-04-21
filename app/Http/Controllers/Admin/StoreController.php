<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class StoreController extends Controller
{
    public function index()
    {
        $stores = Store::with('schedules')->get();
        return view('admin.stores.index', compact('stores'));
    }

    public function create()
    {
        return view('admin.stores.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nom' => 'required|string|max:255',
            'adresse' => 'required|string|max:255',
            'ville' => 'required|string|max:255',
            'pays' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'ouvert_jusqua' => 'required|date_format:H:i',
            'lien_rdv' => 'nullable|url',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'services' => 'nullable|array',
            'services.*' => 'string'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $store = Store::create($request->all());

        return redirect()->route('admin.stores.index')
            ->with('success', 'Point de vente créé avec succès');
    }

    public function edit(Store $store)
    {
        return view('admin.stores.edit', compact('store'));
    }

    public function update(Request $request, Store $store)
    {
        $validator = Validator::make($request->all(), [
            'nom' => 'required|string|max:255',
            'adresse' => 'required|string|max:255',
            'ville' => 'required|string|max:255',
            'pays' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'ouvert_jusqua' => 'required|date_format:H:i',
            'lien_rdv' => 'nullable|url',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'services' => 'nullable|array',
            'services.*' => 'string'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $store->update($request->all());

        return redirect()->route('admin.stores.index')
            ->with('success', 'Point de vente mis à jour avec succès');
    }

    public function destroy(Store $store)
    {
        $store->delete();
        return redirect()->route('admin.stores.index')
            ->with('success', 'Point de vente supprimé avec succès');
    }
} 