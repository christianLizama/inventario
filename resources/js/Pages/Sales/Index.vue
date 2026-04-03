<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { ref, computed, watch } from 'vue';

const props = defineProps({
    sales: Array,
    years: Array,
    monthlySummary: Array,
    filters: Object,
    totals: Object,
});

const monthNames = [
    'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio',
    'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre',
];

const selectedYear = ref(props.filters.year);
const selectedMonth = ref(props.filters.month ?? '');
const search = ref('');

function applyFilter() {
    router.get(route('sales.index'), {
        year: selectedYear.value,
        month: selectedMonth.value || undefined,
    }, { preserveState: true, replace: true });
}

watch([selectedYear, selectedMonth], () => applyFilter());

function formatCurrency(value) {
    return new Intl.NumberFormat('es-CL', {
        style: 'currency',
        currency: 'CLP',
        minimumFractionDigits: 0,
    }).format(value ?? 0);
}

function formatDate(dateStr) {
    return new Date(dateStr + 'T12:00:00').toLocaleDateString('es-CL', {
        weekday: 'short',
        day: 'numeric',
        month: 'long',
    });
}

// Para el gráfico sacamos la barra más alta, ya sea de este año o del pasado
const maxSales = computed(() => {
    let max = 0;
    props.monthlySummary.forEach(m => {
        if (m.total_sales > max) max = m.total_sales;
        if (m.last_year_sales > max) max = m.last_year_sales;
    });
    return Math.max(max, 1);
});

const filteredSales = computed(() => {
    if (!search.value.trim()) return props.sales;
    const q = search.value.toLowerCase();
    return props.sales.filter(s => s.date.includes(q));
});

function monthName(num) {
    return monthNames[(parseInt(num) - 1)] ?? num;
}

// Calculamos la diferencia de dinero por fila
function getDiff(current, previous) {
    if (!previous || previous === 0) return null;
    return current - previous;
}

// Variables para el Modal custom
const showDeleteModal = ref(false);
const saleToDelete = ref(null);

function confirmDelete(id) {
    saleToDelete.value = id;
    showDeleteModal.value = true;
}

function deleteSale() {
    if (saleToDelete.value) {
        router.delete(route('sales.destroy', saleToDelete.value), {
            preserveScroll: true,
            onSuccess: () => {
                showDeleteModal.value = false;
                saleToDelete.value = null;
            }
        });
    }
}
</script>

<template>
    <Head title="Reportes de Ventas" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <h2 class="text-lg font-semibold text-white">Reportes de Ingresos</h2>

                <div class="flex items-center gap-2">
                    <select
                        v-model="selectedYear"
                        class="bg-gray-800 border border-gray-700 text-white text-sm rounded-lg px-3 py-1.5 focus:outline-none focus:ring-2 focus:ring-violet-500 cursor-pointer"
                    >
                        <option v-for="year in years" :key="year" :value="year">{{ year }}</option>
                    </select>

                    <select
                        v-model="selectedMonth"
                        class="bg-gray-800 border border-gray-700 text-white text-sm rounded-lg px-3 py-1.5 focus:outline-none focus:ring-2 focus:ring-violet-500 cursor-pointer"
                    >
                        <option value="">Todos los meses</option>
                        <option v-for="(name, i) in monthNames" :key="i + 1" :value="i + 1">{{ name }}</option>
                    </select>

                    <Link
                        :href="route('sales.create')"
                        class="px-4 py-1.5 bg-emerald-600 hover:bg-emerald-500 text-white text-sm rounded-lg font-semibold transition-colors"
                    >
                        + Registrar
                    </Link>
                </div>
            </div>
        </template>

        <div class="py-8 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto space-y-6">

            <!-- Tarjetas resumen -->
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                <div class="bg-gray-900 border border-gray-800 rounded-xl p-5 relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-16 h-16 bg-white/5 rounded-bl-full"></div>
                    <p class="text-xs text-gray-500 uppercase tracking-widest mb-1 font-bold">Total Ingresos</p>
                    <p class="text-white text-2xl font-black mt-1">{{ formatCurrency(totals.sales) }}</p>
                </div>
                <div class="bg-gray-900 border border-gray-800 rounded-xl p-5 relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-16 h-16 bg-emerald-500/10 rounded-bl-full"></div>
                    <p class="text-xs text-gray-500 uppercase tracking-widest mb-1 font-bold">Efectivo 💵</p>
                    <p class="text-emerald-400 text-2xl font-black mt-1">{{ formatCurrency(totals.cash) }}</p>
                </div>
                <div class="bg-gray-900 border border-gray-800 rounded-xl p-5 relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-16 h-16 bg-cyan-500/10 rounded-bl-full"></div>
                    <p class="text-xs text-gray-500 uppercase tracking-widest mb-1 font-bold">Tarjeta 💳</p>
                    <p class="text-cyan-400 text-2xl font-black mt-1">{{ formatCurrency(totals.card) }}</p>
                </div>
                <div class="bg-gray-900 border border-gray-800 rounded-xl p-5">
                    <p class="text-xs text-gray-500 uppercase tracking-widest mb-1 font-bold">Días trabajados</p>
                    <p class="text-amber-400 text-2xl font-black mt-1">{{ totals.days }}</p>
                </div>
            </div>

            <!-- Gráfico mensual comparación (solo si se ven todos los meses) -->
            <div v-if="!filters.month && monthlySummary.length > 0" class="bg-gray-900 border border-gray-800 rounded-xl p-6">
                <div class="flex items-center gap-3 mb-6">
                    <h3 class="text-sm font-semibold text-gray-400 uppercase tracking-widest">
                        Ingresos por mes — {{ selectedYear }}
                    </h3>
                    <div class="flex-1 border-b border-gray-800"></div>
                </div>

                <div class="space-y-5">
                    <div
                        v-for="month in monthlySummary"
                        :key="month.month"
                        class="flex items-center gap-4 group"
                    >
                        <span class="text-xs font-medium text-gray-500 w-24 shrink-0 uppercase tracking-wider">{{ monthName(month.month) }}</span>

                        <div class="flex-1 space-y-1.5">
                            <!-- Barra de este año -->
                            <div class="h-3 rounded-r-md bg-gradient-to-r from-violet-600 to-violet-400 transition-all duration-500 relative flex items-center"
                                :style="`width: ${(parseFloat(month.total_sales) / maxSales * 100).toFixed(1)}%`">
                                <span class="absolute -right-2 translate-x-full text-[10px] text-white font-bold opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap pl-2">
                                    Actual: {{ formatCurrency(month.total_sales) }}
                                </span>
                            </div>

                            <!-- Barra del año pasado -->
                            <div v-if="month.last_year_sales > 0" 
                                class="h-1.5 rounded-r-md bg-gray-700 transition-all duration-500 relative flex items-center"
                                :style="`width: ${(parseFloat(month.last_year_sales) / maxSales * 100).toFixed(1)}%`">
                                <span class="absolute -right-2 translate-x-full text-[10px] text-gray-500 opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap pl-2">
                                    Año ant: {{ formatCurrency(month.last_year_sales) }}
                                </span>
                            </div>
                        </div>

                        <!-- Indicador resumen -->
                        <div class="w-24 text-right shrink-0">
                            <span class="text-sm font-black text-white block">{{ formatCurrency(month.total_sales) }}</span>
                            <span v-if="month.last_year_sales > 0" class="text-[10px] font-bold block" 
                                :class="month.total_sales >= month.last_year_sales ? 'text-emerald-500' : 'text-red-500'">
                                {{ month.total_sales >= month.last_year_sales ? '↑' : '↓' }} 
                                {{ formatCurrency(Math.abs(getDiff(month.total_sales, month.last_year_sales))) }}
                            </span>
                        </div>
                    </div>
                </div>
                
                <!-- Leyenda del gráfico -->
                <div class="mt-6 flex items-center justify-end gap-4 text-xs font-medium text-gray-500">
                    <div class="flex items-center gap-1.5"><div class="w-3 h-3 rounded-sm bg-violet-500"></div> Año Actual</div>
                    <div class="flex items-center gap-1.5"><div class="w-3 h-1.5 rounded-sm bg-gray-700"></div> Año Anterior</div>
                </div>
            </div>

            <!-- Tabla de registros -->
            <div class="bg-gray-900 border border-gray-800 rounded-xl overflow-hidden shadow-sm">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between px-6 py-4 border-b border-gray-800 gap-4">
                    <h3 class="text-sm font-semibold text-gray-400 uppercase tracking-widest">Detalle Diario</h3>

                    <div class="relative">
                        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        <input
                            v-model="search"
                            type="text"
                            placeholder="Buscar por fecha (YYYY-MM-DD)..."
                            class="bg-gray-950 border border-gray-700 text-white text-sm pl-9 pr-3 py-1.5 rounded-lg w-full sm:w-64 focus:outline-none focus:ring-2 focus:ring-violet-500 placeholder-gray-600"
                        />
                    </div>
                </div>

                <div v-if="filteredSales.length === 0" class="py-16 text-center">
                    <svg class="w-12 h-12 mx-auto mb-4 text-gray-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <p class="text-gray-500 font-medium">No hay registros de ventas que coincidan.</p>
                </div>

                <div v-else class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="bg-gray-950/50 text-gray-500 text-xs uppercase tracking-wider border-b border-gray-800/80">
                                <th class="px-6 py-4 text-left font-semibold">Fecha</th>
                                <th class="px-6 py-4 text-right font-semibold">Efectivo 💵</th>
                                <th class="px-6 py-4 text-right font-semibold">Tarjeta 💳</th>
                                <th class="px-6 py-4 text-right font-bold text-white">Total Día</th>
                                <th class="px-6 py-4 text-right font-semibold">vs Año Pasado</th>
                                <th class="px-6 py-4 text-right font-semibold">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-800/50">
                            <tr
                                v-for="sale in filteredSales"
                                :key="sale.id"
                                class="hover:bg-gray-800/40 transition-colors"
                            >
                                <td class="px-6 py-4">
                                    <p class="text-white font-medium capitalize">{{ formatDate(sale.date) }}</p>
                                    <p class="text-xs text-gray-500 mt-0.5">{{ sale.date }}</p>
                                </td>
                                <td class="px-6 py-4 text-right text-emerald-400 font-medium">
                                    {{ formatCurrency(sale.cash) }}
                                </td>
                                <td class="px-6 py-4 text-right text-cyan-400 font-medium">
                                    {{ formatCurrency(sale.card) }}
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <span class="inline-block px-3 py-1 bg-white/5 rounded-lg text-white font-black border border-white/10">
                                        {{ formatCurrency(sale.total_sales) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <!-- Comparativa con año anterior -->
                                    <div v-if="sale.last_year_sales > 0">
                                        <div v-if="getDiff(sale.total_sales, sale.last_year_sales) > 0" class="inline-flex items-center gap-1 px-2 py-1 rounded bg-emerald-500/10 text-emerald-400 text-xs font-bold border border-emerald-500/20">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 10l7-7m0 0l7 7m-7-7v18"/></svg>
                                            +{{ formatCurrency(getDiff(sale.total_sales, sale.last_year_sales)) }}
                                        </div>
                                        <div v-else-if="getDiff(sale.total_sales, sale.last_year_sales) < 0" class="inline-flex items-center gap-1 px-2 py-1 rounded bg-red-500/10 text-red-500 text-xs font-bold border border-red-500/20">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 14l-7 7m0 0l-7-7m7 7V3"/></svg>
                                            -{{ formatCurrency(Math.abs(getDiff(sale.total_sales, sale.last_year_sales))) }}
                                        </div>
                                        <div v-else class="text-xs text-gray-500 font-medium">Igual</div>
                                    </div>
                                    <span v-else class="text-xs text-gray-600">—</span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <!-- Botón Editar -->
                                        <Link :href="route('sales.edit', sale.id)" class="p-1.5 text-gray-400 hover:text-white hover:bg-violet-600 rounded-md transition-colors" title="Editar">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                                        </Link>
                                        <!-- Botón Eliminar Custom -->
                                        <button 
                                            @click="confirmDelete(sale.id)"
                                            class="p-1.5 text-gray-400 hover:text-white hover:bg-red-600 rounded-md transition-colors" 
                                            title="Eliminar"
                                        >
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                        <!-- Tfoot con totales gruesos -->
                        <tfoot>
                            <tr class="border-t-2 border-gray-700 bg-gray-950">
                                <td class="px-6 py-5 text-sm font-black text-gray-300 uppercase tracking-wider">Total Resumen</td>
                                <td class="px-6 py-5 text-right font-black text-emerald-500">{{ formatCurrency(totals.cash) }}</td>
                                <td class="px-6 py-5 text-right font-black text-cyan-500">{{ formatCurrency(totals.card) }}</td>
                                <td class="px-6 py-5 text-right font-black text-white text-base">{{ formatCurrency(totals.sales) }}</td>
                                <td class="px-6 py-5"></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

        </div>

        <!-- Modal Custom de Eliminación -->
        <div v-if="showDeleteModal" class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <!-- Overlay oscuro -->
            <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" @click="showDeleteModal = false"></div>
            
            <!-- Contenido Modal -->
            <div class="relative bg-gray-900 border border-gray-800 rounded-2xl p-6 md:p-8 max-w-sm w-full shadow-2xl overflow-hidden">
                <div class="absolute top-0 right-0 w-32 h-32 bg-red-500/10 rounded-bl-full"></div>
                
                <h3 class="text-xl font-bold text-white mb-2">Eliminar Venta</h3>
                <p class="text-sm text-gray-400 mb-6">¿Estás completamente seguro de que deseas eliminar este registro de venta? Esta acción eliminará permanentemente la información de la base de datos.</p>
                
                <div class="flex justify-end gap-3 relative z-10">
                    <button 
                        @click="showDeleteModal = false"
                        class="px-5 py-2.5 rounded-xl text-sm font-semibold text-gray-300 hover:bg-gray-800 transition-colors"
                    >
                        Cancelar
                    </button>
                    <button 
                        @click="deleteSale"
                        class="px-5 py-2.5 rounded-xl text-sm font-semibold text-white bg-red-600 hover:bg-red-500 transition-colors shadow-lg shadow-red-900/50"
                    >
                        Sí, eliminar registro
                    </button>
                </div>
            </div>
        </div>

    </AuthenticatedLayout>
</template>
