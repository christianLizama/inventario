<script setup>
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <div class="fixed bottom-6 left-1/2 -translate-x-1/2 z-50">
        <nav class="bg-gray-900/90 backdrop-blur-xl border border-gray-700/50 rounded-2xl px-4 py-3 flex items-center gap-2 shadow-2xl shadow-black/60">

            <!-- Inicio -->
            <Link :href="route('dashboard')" class="group relative flex flex-col items-center">
                <div class="w-11 h-11 rounded-xl flex items-center justify-center transition-all duration-150 group-hover:scale-110"
                    :class="route().current('dashboard') ? 'bg-indigo-600 shadow-lg shadow-indigo-900/60' : 'bg-gray-800 hover:bg-gray-700'">
                    <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z" />
                    </svg>
                </div>
                <span class="absolute -top-9 left-1/2 -translate-x-1/2 bg-gray-800 text-white text-xs px-2 py-1 rounded-lg whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none border border-gray-700">
                    Inicio
                </span>
            </Link>

            <div class="w-px h-7 bg-gray-700/80 mx-1"></div>

            <!-- Proveedores -->
            <Link :href="route('suppliers.index')" class="group relative flex flex-col items-center">
                <div class="w-11 h-11 rounded-xl flex items-center justify-center transition-all duration-150 group-hover:scale-110"
                    :class="route().current('suppliers.*') ? 'bg-cyan-600 shadow-lg shadow-cyan-900/60' : 'bg-gray-800 hover:bg-gray-700'">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </div>
                <span class="absolute -top-9 left-1/2 -translate-x-1/2 bg-gray-800 text-white text-xs px-2 py-1 rounded-lg whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none border border-gray-700">
                    Proveedores
                </span>
            </Link>

            <!-- Productos -->
            <Link :href="route('products.index')" class="group relative flex flex-col items-center">
                <div class="w-11 h-11 rounded-xl flex items-center justify-center transition-all duration-150 group-hover:scale-110"
                    :class="route().current('products.*') ? 'bg-orange-600 shadow-lg shadow-orange-900/60' : 'bg-gray-800 hover:bg-gray-700'">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                    </svg>
                </div>
                <span class="absolute -top-9 left-1/2 -translate-x-1/2 bg-gray-800 text-white text-xs px-2 py-1 rounded-lg whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none border border-gray-700">
                    Productos
                </span>
            </Link>

            <!-- Reportes -->
            <Link :href="route('sales.index')" class="group relative flex flex-col items-center">
                <div class="w-11 h-11 rounded-xl flex items-center justify-center transition-all duration-150 group-hover:scale-110"
                    :class="route().current('sales.index') ? 'bg-violet-600 shadow-lg shadow-violet-900/60' : 'bg-gray-800 hover:bg-gray-700'">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                </div>
                <span class="absolute -top-9 left-1/2 -translate-x-1/2 bg-gray-800 text-white text-xs px-2 py-1 rounded-lg whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none border border-gray-700">
                    Reportes
                </span>
            </Link>

            <!-- Nueva venta -->
            <Link :href="route('sales.create')" class="group relative flex flex-col items-center">
                <div class="w-11 h-11 rounded-xl flex items-center justify-center transition-all duration-150 group-hover:scale-110"
                    :class="route().current('sales.create') ? 'bg-emerald-600 shadow-lg shadow-emerald-900/60' : 'bg-gray-800 hover:bg-gray-700'">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                </div>
                <span class="absolute -top-9 left-1/2 -translate-x-1/2 bg-gray-800 text-white text-xs px-2 py-1 rounded-lg whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none border border-gray-700">
                    Nueva venta
                </span>
            </Link>

            <div class="w-px h-7 bg-gray-700/80 mx-1"></div>

            <!-- Cerrar sesión -->
            <Link :href="route('logout')" method="post" as="button" class="group relative flex flex-col items-center">
                <div class="w-11 h-11 rounded-xl bg-gray-800 hover:bg-gray-700 flex items-center justify-center transition-all duration-150 group-hover:scale-110">
                    <svg class="w-5 h-5 text-gray-400 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                </div>
                <span class="absolute -top-9 left-1/2 -translate-x-1/2 bg-gray-800 text-white text-xs px-2 py-1 rounded-lg whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none border border-gray-700">
                    Salir
                </span>
            </Link>

        </nav>
    </div>
</template>
