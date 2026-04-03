<?php

namespace App\Http\Controllers;

use App\Models\DailySale;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SaleController extends Controller
{
    /**
     * Muestra el reporte de ventas con filtros por año y mes, y agrega ventas de hace un año.
     */
    public function index(Request $request)
    {
        $year  = (int) $request->get('year', now()->year);
        $month = $request->get('month');

        // Años disponibles en la base de datos
        $years = DailySale::selectRaw("strftime('%Y', \"date\") as year")
            ->distinct()
            ->orderByRaw('year DESC')
            ->pluck('year');

        if ($years->isEmpty()) {
            $years = collect([(string) now()->year]);
        }

        // Consultar el año actual
        $queryCurrent = DailySale::whereYear('date', $year)->orderBy('date', 'desc');
        if ($month) {
            $queryCurrent->whereMonth('date', (int) $month);
        }
        $sales = $queryCurrent->get();

        // Consultar los datos del año anterior
        $queryPrevious = DailySale::whereYear('date', $year - 1);
        if ($month) {
            $queryPrevious->whereMonth('date', (int) $month);
        }
        $previousSales = $queryPrevious->get()->keyBy('date');

        // Map sales para inyectarle el dato del "mismo día año anterior"
        $sales->map(function ($sale) use ($previousSales) {
            // Buscamos exactamente hace un año (-1 year)
            $lastYearDate = \Carbon\Carbon::parse($sale->date)->subYear()->format('Y-m-d');
            
            $sale->last_year_sales = $previousSales->has($lastYearDate) 
                                      ? $previousSales[$lastYearDate]->total_sales 
                                      : 0;
            return $sale;
        });

        // Resumen mensual del año seleccionado para el gráfico
        $monthlySummary = DailySale::whereYear('date', $year)
            ->selectRaw("strftime('%m', \"date\") as month, SUM(cash) as cash, SUM(card) as card, SUM(total_sales) as total_sales, COUNT(*) as days")
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->map(function ($item) use ($year) {
                $item->month = (int) $item->month;
                
                // Buscar ventas de este mismo mes en el año anterior
                $lastYearMonthSales = DailySale::whereYear('date', $year - 1)
                                        ->whereMonth('date', $item->month)
                                        ->sum('total_sales');
                                        
                $item->last_year_sales = $lastYearMonthSales;
                return $item;
            });

        return Inertia::render('Sales/Index', [
            'sales'          => $sales,
            'years'          => $years,
            'monthlySummary' => $monthlySummary,
            'filters'        => [
                'year'  => $year,
                'month' => $month ? (int) $month : null,
            ],
            'totals' => [
                'cash'  => $sales->sum('cash'),
                'card'  => $sales->sum('card'),
                'sales' => $sales->sum('total_sales'),
                'days'  => $sales->count(),
            ],
        ]);
    }

    /**
     * Muestra el formulario para registrar una venta diaria.
     */
    public function create()
    {
        return Inertia::render('Sales/Create');
    }

    /**
     * Guarda la venta diaria en la base de datos (Efectivo y Tarjeta).
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'cash' => 'required|numeric|min:0',
            'card' => 'required|numeric|min:0',
        ]);

        $validated['total_sales'] = $validated['cash'] + $validated['card'];

        DailySale::updateOrCreate(
            ['date' => $validated['date']], // Previene duplicar registros para la misma fecha
            $validated
        );

        return redirect()->route('sales.index')
            ->with('success', 'Venta registrada guardada con éxito.');
    }
    /**
     * Muestra el formulario para editar una venta.
     */
    public function edit(DailySale $sale)
    {
        return Inertia::render('Sales/Edit', [
            'sale' => $sale,
        ]);
    }

    /**
     * Actualiza la venta diaria en la base de datos.
     */
    public function update(Request $request, DailySale $sale)
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'cash' => 'required|numeric|min:0',
            'card' => 'required|numeric|min:0',
        ]);

        $validated['total_sales'] = $validated['cash'] + $validated['card'];

        $sale->update($validated);

        return redirect()->route('sales.index')
            ->with('success', 'Venta actualizada con éxito.');
    }

    /**
     * Elimina la venta especificada.
     */
    public function destroy(DailySale $sale)
    {
        $sale->delete();

        return redirect()->route('sales.index')
            ->with('success', 'Venta eliminada con éxito.');
    }
}
