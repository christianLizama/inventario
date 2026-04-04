<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    suppliers: Array,
});
</script>

<template>
    <Head title="Proveedores" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <h2 class="text-lg font-semibold text-white">Proveedores</h2>
                <Link
                    :href="route('suppliers.create')"
                    class="inline-flex items-center gap-1.5 px-4 py-2 bg-cyan-600 hover:bg-cyan-500 text-white text-sm font-medium rounded-lg transition-colors"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Nuevo Proveedor
                </Link>
            </div>
        </template>

        <div class="py-8 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto">

            <!-- Sin proveedores -->
            <div v-if="suppliers.length === 0" class="text-center py-20 text-gray-500">
                <svg class="w-14 h-14 mx-auto mb-4 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                <p class="text-lg">No hay proveedores registrados.</p>
                <Link :href="route('suppliers.create')" class="mt-3 inline-block text-cyan-400 hover:text-cyan-300">
                    Crear el primer proveedor →
                </Link>
            </div>

            <!-- Tabla -->
            <div v-else class="bg-gray-900 border border-gray-800 rounded-xl overflow-hidden">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-gray-800 text-gray-400 text-xs uppercase tracking-wider">
                            <th class="px-6 py-4 text-left font-medium">Nombre</th>
                            <th class="px-6 py-4 text-left font-medium">Teléfono</th>
                            <th class="px-6 py-4 text-left font-medium">Email</th>
                            <th class="px-6 py-4 text-center font-medium">Productos</th>
                            <th class="px-6 py-4 text-right font-medium">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-800">
                        <tr
                            v-for="supplier in suppliers"
                            :key="supplier.id"
                            class="hover:bg-gray-800/50 transition-colors"
                        >
                            <td class="px-6 py-4 font-medium text-white">{{ supplier.name }}</td>
                            <td class="px-6 py-4 text-gray-400">{{ supplier.phone || '—' }}</td>
                            <td class="px-6 py-4 text-gray-400">{{ supplier.email || '—' }}</td>
                            <td class="px-6 py-4 text-center">
                                <span class="inline-block px-2.5 py-0.5 bg-cyan-900/50 text-cyan-400 text-xs font-semibold rounded-full">
                                    {{ supplier.products_count }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <Link
                                    :href="route('suppliers.show', supplier.id)"
                                    class="text-cyan-400 hover:text-cyan-300 text-sm font-medium"
                                >
                                    Ver Detalles &rarr;
                                </Link>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
