<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProductController extends Controller
{
    /**
     * Lista todos los productos con su proveedor.
     */
    public function index()
    {
        $products = Product::with('supplier')
            ->orderBy('name')
            ->get();

        return Inertia::render('Products/Index', [
            'products' => $products,
        ]);
    }

    /**
     * Muestra el formulario para crear un producto.
     */
    public function create()
    {
        $suppliers = Supplier::orderBy('name')->get();

        return Inertia::render('Products/Create', [
            'suppliers' => $suppliers,
        ]);
    }

    /**
     * Guarda un nuevo producto en la base de datos.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'           => 'required|string|max:255',
            'supplier_id'    => 'required|exists:suppliers,id',
            'purchase_price' => 'required|numeric|min:0',
            'sale_price'     => 'required|numeric|min:0',
        ]);

        Product::create($validated);

        return redirect()->route('products.index')
            ->with('success', 'Producto creado correctamente.');
    }
}
