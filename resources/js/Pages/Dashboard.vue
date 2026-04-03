<script setup>
import LauncherLayout from '@/Layouts/LauncherLayout.vue';
import { Head, Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = defineProps({
    todayCash: Number,
    todayCard: Number,
    todayTotal: Number,
    lastYearTotal: Number,
    lastYearDate: String,
});

const page = usePage();
const userName = computed(() => page.props.auth?.user?.name ?? 'Usuario');

const today = new Date().toLocaleDateString('es-CL', {
    weekday: 'long',
    year: 'numeric',
    month: 'long',
    day: 'numeric',
});

function formatCurrency(value) {
    return new Intl.NumberFormat('es-CL', {
        style: 'currency',
        currency: 'CLP',
        minimumFractionDigits: 0,
    }).format(value ?? 0);
}

// Calculamos la diferencia en dinero con el mismo día del año anterior
const differenceMoney = computed(() => {
    if (!props.lastYearTotal) return null;
    return props.todayTotal - props.lastYearTotal;
});

const modules = [
    {
        label: 'Proveedores',
        route: 'suppliers.index',
        gradient: 'from-cyan-400 to-blue-600',
        shadow: 'shadow-cyan-900/50',
        icon: `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />`,
    },
    {
        label: 'Productos',
        route: 'products.index',
        gradient: 'from-orange-400 to-red-600',
        shadow: 'shadow-orange-900/50',
        icon: `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />`,
    },
    {
        label: 'Reportes',
        route: 'sales.index',
        gradient: 'from-violet-500 to-purple-700',
        shadow: 'shadow-violet-900/50',
        icon: `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />`,
    },
    {
        label: 'Ventas',
        route: 'sales.create',
        gradient: 'from-emerald-400 to-green-600',
        shadow: 'shadow-emerald-900/50',
        icon: `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v16m8-8H4" />`,
    },
];
</script>

<template>
    <Head title="Inicio" />
    <LauncherLayout>
        <div class="min-h-screen flex flex-col items-center justify-center px-8 select-none">

            <!-- Saludo -->
            <div class="text-center mb-10">
                <h1 class="text-white text-3xl font-light tracking-wide">
                    Hola, <span class="font-semibold">{{ userName }}</span>
                </h1>
                <p class="text-gray-500 text-sm mt-2 capitalize">{{ today }}</p>
            </div>

            <!-- Tarjetas de estadísticas -->
            <div class="flex flex-wrap justify-center gap-4 mb-16">
                <!-- Ventas hoy -->
                <div class="bg-gray-900 border border-gray-800 rounded-2xl px-6 py-5 text-center min-w-[150px]">
                    <p class="text-gray-500 text-xs uppercase tracking-widest mb-1">Ventas totales hoy</p>
                    <p class="text-white text-2xl font-bold">{{ formatCurrency(todayTotal) }}</p>
                </div>

                <!-- Desglose Efectivo/Tarjeta -->
                <div class="bg-gray-900 border border-gray-800 rounded-2xl p-5 flex items-center min-w-[200px]">
                    <div class="flex-1 text-center border-r border-gray-800 pr-5">
                        <p class="text-gray-500 text-[10px] uppercase font-bold tracking-widest mb-1">Efectivo</p>
                        <p class="text-emerald-400 text-lg font-semibold">{{ formatCurrency(todayCash) }}</p>
                    </div>
                    <div class="flex-1 text-center pl-5">
                        <p class="text-gray-500 text-[10px] uppercase font-bold tracking-widest mb-1">Tarjeta</p>
                        <p class="text-cyan-400 text-lg font-semibold">{{ formatCurrency(todayCard) }}</p>
                    </div>
                </div>

                <!-- Año Pasado -->
                <div class="bg-gray-900 border border-gray-800 rounded-2xl px-6 py-5 min-w-[180px] relative overflow-hidden">
                    <p class="text-gray-500 text-xs uppercase tracking-widest mb-1">Mismo día año pasado</p>
                    <p class="text-gray-300 text-2xl font-bold">{{ formatCurrency(lastYearTotal) }}</p>
                    
                    <!-- Indicador de diferencia -->
                    <div v-if="lastYearTotal > 0" class="mt-2">
                        <span v-if="differenceMoney > 0" class="text-emerald-500 text-xs font-bold flex items-center gap-1">
                            ↑ +{{ formatCurrency(differenceMoney) }} vs {{ lastYearDate }}
                        </span>
                        <span v-else-if="differenceMoney < 0" class="text-red-500 text-xs font-bold flex items-center gap-1">
                            ↓ {{ formatCurrency(Math.abs(differenceMoney)) }} vs {{ lastYearDate }}
                        </span>
                        <span v-else class="text-gray-500 text-xs font-bold flex items-center gap-1">
                            = Igual vs {{ lastYearDate }}
                        </span>
                    </div>
                    <div v-else class="mt-2 text-gray-600 text-xs font-bold">
                        Sin datos anteriores
                    </div>
                </div>
            </div>

            <!-- Íconos de módulos -->
            <div class="flex flex-wrap justify-center gap-12">
                <Link
                    v-for="mod in modules"
                    :key="mod.label"
                    :href="route(mod.route)"
                    class="flex flex-col items-center gap-3 group"
                >
                    <div
                        class="w-24 h-24 rounded-[22px] flex items-center justify-center shadow-xl transition-all duration-200 group-hover:scale-110 group-hover:brightness-110 bg-gradient-to-br"
                        :class="[mod.gradient, mod.shadow]"
                    >
                        <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" v-html="mod.icon" />
                    </div>
                    <span class="text-gray-200 text-sm font-medium group-hover:text-white transition-colors">
                        {{ mod.label }}
                    </span>
                </Link>
            </div>

        </div>
    </LauncherLayout>
</template>
