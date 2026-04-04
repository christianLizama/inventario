<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SupplierController extends Controller
{
    /**
     * Lista todos los proveedores con el conteo de sus productos.
     */
    public function index()
    {
        $suppliers = Supplier::withCount('products')
            ->orderBy('name')
            ->get();

        return Inertia::render('Suppliers/Index', [
            'suppliers' => $suppliers,
        ]);
    }

    /**
     * Muestra el formulario para crear un proveedor.
     */
    public function create()
    {
        return Inertia::render('Suppliers/Create');
    }

    /**
     * Guarda un nuevo proveedor en la base de datos.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'  => 'required|string|max:255',
            'phone' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
        ]);

        Supplier::create($validated);

        return redirect()->route('suppliers.index')
            ->with('success', 'Proveedor creado correctamente.');
    }

    /**
     * Muestra la información de un proveedor y sus productos.
     */
    public function show(Supplier $supplier)
    {
        $supplier->load(['products' => function ($query) {
            $query->orderBy('name');
        }]);

        return Inertia::render('Suppliers/Show', [
            'supplier' => $supplier,
        ]);
    }
}
