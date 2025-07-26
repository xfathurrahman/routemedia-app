<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Package;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $clients = Client::with('package')->get();
        $packages = Package::all();

        return Inertia::render('Clients', [
            'clients' => $clients,
            'packages' => $packages,
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $client = Client::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'whatsapp_number' => 'required|string|max:20',
            'package_id' => 'required|exists:packages,id',
        ]);

        // Handle ID card photo if provided
        if ($request->hasFile('id_card_photo')) {
            $path = $request->file('id_card_photo')->store('id_cards', 'public');
            $validated['id_card_photo_path'] = $path;
        }

        $client->update($validated);

        return redirect()->back();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'whatsapp_number' => 'required|string|max:20',
            'package_id' => 'required|exists:packages,id',
        ]);

        // Handle ID card photo if provided
        if ($request->hasFile('id_card_photo')) {
            $path = $request->file('id_card_photo')->store('id_cards', 'public');
            $validated['id_card_photo_path'] = $path;
        }

        Client::create($validated);

        return redirect()->back();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $client = Client::findOrFail($id);
        $client->delete();

        return redirect()->back();
    }
}
