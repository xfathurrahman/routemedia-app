<?php

namespace App\Http\Controllers;

use App\Models\Package;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PackageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $packages = Package::withCount('clients')->get();

        return Inertia::render('Packages', [
            'packages' => $packages
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'speed' => 'required|string|max:255',
        ]);

        Package::create($validated);

        return redirect()->back();
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $package = Package::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'speed' => 'required|string|max:255',
        ]);

        $package->update($validated);

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $package = Package::withCount('clients')->findOrFail($id);

        if ($package->clients_count > 0) {
            return redirect()->back()->with('error',
                'Paket tidak dapat dihapus karena masih digunakan oleh ' . $package->clients_count . ' pelanggan. Ubah paket pelanggan terlebih dahulu.');
        }

        $package->delete();

        return redirect()->back();
    }
}
