<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = defineProps({
    suppliers: Array,
    defaultSupplierId: [String, Number],
});

const form = useForm({
    name: '',
    supplier_id: props.defaultSupplierId || '',
    purchase_price: '',
    sale_price: '',
});

const marginMoney = computed(() => {
    const purchase = parseFloat(form.purchase_price) || 0;
    const sale = parseFloat(form.sale_price) || 0;
    return sale - purchase;
});

const marginPercent = computed(() => {
    const purchase = parseFloat(form.purchase_price) || 0;
    const sale = parseFloat(form.sale_price) || 0;
    if (purchase === 0) return 0;
    return ((sale - purchase) / purchase * 100).toFixed(1);
});

const marginColor = computed(() => {
    if (marginMoney.value > 0) return 'text-emerald-400';
    if (marginMoney.value < 0) return 'text-red-400';
    return 'text-gray-500';
});

function formatCurrency(value) {
    return new Intl.NumberFormat('es-CL', {
        style: 'currency',
        currency: 'CLP',
        minimumFractionDigits: 0,
    }).format(value);
}

function submit() {
    form.post(route('products.store'));
}
</script>

<template>
    <Head title="Nuevo Producto" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-lg font-semibold text-white">Nuevo Producto</h2>
        </template>

        <div class="py-8 px-4 sm:px-6 lg:px-8 max-w-2xl mx-auto">
            <div class="bg-gray-900 border border-gray-800 rounded-xl p-8">

                <form @submit.prevent="submit" class="space-y-6">

                    <!-- Nombre -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-300 mb-1.5">
                            Nombre del producto
                        </label>
                        <input
                            id="name"
                            v-model="form.name"
                            type="text"
                            placeholder="Ej: Arroz 1kg"
                            class="w-full bg-gray-800 border border-gray-700 text-white placeholder-gray-600 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent transition"
                        />
                        <p v-if="form.errors.name" class="mt-1.5 text-xs text-red-400">{{ form.errors.name }}</p>
                    </div>

                    <!-- Proveedor -->
                    <div>
                        <label for="supplier_id" class="block text-sm font-medium text-gray-300 mb-1.5">
                            Proveedor
                        </label>
                        <select
                            id="supplier_id"
                            v-model="form.supplier_id"
                            class="w-full bg-gray-800 border border-gray-700 text-white rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent transition"
                        >
                            <option value="" disabled class="text-gray-500">Selecciona un proveedor</option>
                            <option v-for="supplier in suppliers" :key="supplier.id" :value="supplier.id">
                                {{ supplier.name }}
                            </option>
                        </select>
                        <p v-if="form.errors.supplier_id" class="mt-1.5 text-xs text-red-400">{{ form.errors.supplier_id }}</p>
                    </div>

                    <!-- Precios -->
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="purchase_price" class="block text-sm font-medium text-gray-300 mb-1.5">
                                Precio compra ($)
                            </label>
                            <input
                                id="purchase_price"
                                v-model="form.purchase_price"
                                type="number"
                                min="0"
                                step="1"
                                placeholder="0"
                                class="w-full bg-gray-800 border border-gray-700 text-white placeholder-gray-600 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent transition"
                            />
                            <p v-if="form.errors.purchase_price" class="mt-1.5 text-xs text-red-400">{{ form.errors.purchase_price }}</p>
                        </div>

                        <div>
                            <label for="sale_price" class="block text-sm font-medium text-gray-300 mb-1.5">
                                Precio venta ($)
                            </label>
                            <input
                                id="sale_price"
                                v-model="form.sale_price"
                                type="number"
                                min="0"
                                step="1"
                                placeholder="0"
                                class="w-full bg-gray-800 border border-gray-700 text-white placeholder-gray-600 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent transition"
                            />
                            <p v-if="form.errors.sale_price" class="mt-1.5 text-xs text-red-400">{{ form.errors.sale_price }}</p>
                        </div>
                    </div>

                    <!-- Vista previa del margen -->
                    <div class="bg-gray-800 rounded-xl p-5 border border-gray-700">
                        <p class="text-xs text-gray-500 uppercase font-semibold tracking-widest mb-4">Vista previa del margen</p>
                        <div class="flex items-center justify-around text-center">
                            <div>
                                <p class="text-xs text-gray-500 mb-1">Compra</p>
                                <p class="text-lg font-bold text-gray-200">{{ formatCurrency(parseFloat(form.purchase_price) || 0) }}</p>
                            </div>
                            <span class="text-gray-600 text-xl">→</span>
                            <div>
                                <p class="text-xs text-gray-500 mb-1">Venta</p>
                                <p class="text-lg font-bold text-gray-200">{{ formatCurrency(parseFloat(form.sale_price) || 0) }}</p>
                            </div>
                            <span class="text-gray-600 text-xl">=</span>
                            <div>
                                <p class="text-xs text-gray-500 mb-1">Ganancia</p>
                                <p class="text-lg font-bold" :class="marginColor">{{ formatCurrency(marginMoney) }}</p>
                                <p class="text-xs" :class="marginColor">{{ marginPercent }}%</p>
                            </div>
                        </div>
                    </div>

                    <!-- Botones -->
                    <div class="flex justify-end gap-3 pt-1">
                        <a
                            :href="route('products.index')"
                            class="px-4 py-2 text-sm text-gray-400 border border-gray-700 rounded-lg hover:bg-gray-800 transition-colors"
                        >
                            Cancelar
                        </a>
                        <button
                            type="submit"
                            :disabled="form.processing"
                            class="px-6 py-2 text-sm font-medium text-white bg-orange-600 hover:bg-orange-500 disabled:opacity-50 rounded-lg transition-colors"
                        >
                            {{ form.processing ? 'Guardando...' : 'Guardar Producto' }}
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
