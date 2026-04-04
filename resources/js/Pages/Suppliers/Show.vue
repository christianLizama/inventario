<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

const props = defineProps({
    supplier: Object,
});

function formatCurrency(value) {
    return new Intl.NumberFormat('es-CL', {
        style: 'currency',
        currency: 'CLP',
        minimumFractionDigits: 0,
    }).format(value);
}

function marginMoney(product) {
    return product.sale_price - product.purchase_price;
}

function marginPercent(product) {
    if (!product.purchase_price || product.purchase_price === 0) return 0;
    return ((product.sale_price - product.purchase_price) / product.purchase_price * 100).toFixed(1);
}
</script>

<template>
    <Head :title="`Proveedor: ${supplier.name}`" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <div>
                    <Link :href="route('suppliers.index')" class="text-sm font-medium text-gray-400 hover:text-white transition-colors flex items-center gap-1 mb-1">
                        &larr; Volver a proveedores
                    </Link>
                    <h2 class="text-xl font-bold text-white">{{ supplier.name }}</h2>
                    <p class="text-sm text-gray-400 mt-0.5">
                        <span v-if="supplier.phone" class="mr-3">📞 {{ supplier.phone }}</span>
                        <span v-if="supplier.email">✉️ {{ supplier.email }}</span>
                    </p>
                </div>
                <Link
                    :href="route('products.create', { supplier_id: supplier.id })"
                    class="inline-flex items-center gap-1.5 px-4 py-2 bg-orange-600 hover:bg-orange-500 text-white text-sm font-medium rounded-lg transition-colors"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Añadir Producto
                </Link>
            </div>
        </template>

        <div class="py-8 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto">

            <!-- Sin productos -->
            <div v-if="!supplier.products || supplier.products.length === 0" class="text-center py-20 text-gray-500 bg-gray-900 border border-gray-800 rounded-xl">
                <svg class="w-14 h-14 mx-auto mb-4 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                </svg>
                <p class="text-lg">Este proveedor aún no tiene productos registrados.</p>
                <Link :href="route('products.create', { supplier_id: supplier.id })" class="mt-3 inline-block text-orange-400 hover:text-orange-300 font-medium">
                    Crear el primer producto →
                </Link>
            </div>

            <!-- Tabla de productos -->
            <div v-else class="bg-gray-900 border border-gray-800 rounded-xl overflow-hidden">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-gray-800 text-gray-400 text-xs uppercase tracking-wider bg-gray-900/50">
                            <th class="px-6 py-4 text-left font-medium">Nombre de Producto</th>
                            <th class="px-6 py-4 text-right font-medium">Precio Compra</th>
                            <th class="px-6 py-4 text-right font-medium">Precio Venta</th>
                            <th class="px-6 py-4 text-right font-medium">Margen $</th>
                            <th class="px-6 py-4 text-right font-medium">Margen %</th>
                            <th class="px-6 py-4 text-right font-medium">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-800">
                        <tr
                            v-for="product in supplier.products"
                            :key="product.id"
                            class="hover:bg-gray-800/50 transition-colors"
                        >
                            <td class="px-6 py-4 font-medium text-white">{{ product.name }}</td>
                            <td class="px-6 py-4 text-right text-gray-300">{{ formatCurrency(product.purchase_price) }}</td>
                            <td class="px-6 py-4 text-right text-gray-300">{{ formatCurrency(product.sale_price) }}</td>
                            <td class="px-6 py-4 text-right font-medium text-emerald-400">{{ formatCurrency(marginMoney(product)) }}</td>
                            <td class="px-6 py-4 text-right">
                                <span
                                    class="inline-block px-2 py-0.5 rounded-full text-xs font-semibold"
                                    :class="marginPercent(product) >= 20
                                        ? 'bg-emerald-900/50 text-emerald-400'
                                        : 'bg-amber-900/50 text-amber-400'"
                                >
                                    {{ marginPercent(product) }}%
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right space-x-3 text-sm">
                                <Link
                                    :href="route('products.edit', product.id)"
                                    class="text-amber-400 hover:text-amber-300 font-medium transition-colors"
                                >
                                    Editar
                               </Link>
                                <Link
                                    :href="route('products.destroy', product.id)"
                                    method="delete"
                                    as="button"
                                    type="button"
                                    class="text-red-400 hover:text-red-300 font-medium transition-colors focus:outline-none"
                                    @click="(e) => { if(!window.confirm('¿Seguro quieres eliminar este producto?')) e.preventDefault(); }"
                                >
                                    Eliminar
                                </Link>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
