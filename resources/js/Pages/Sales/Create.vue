<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { computed } from 'vue';

const form = useForm({
    date: new Date().toISOString().split('T')[0],
    cash: '',
    card: '',
});

function submit() {
    form.post(route('sales.store'));
}

// Para limpiar formato e inputs
function numericString(val) {
    if (!val) return 0;
    return parseFloat(val.toString().replace(/[^0-9.-]+/g,"")) || 0;
}

const totalCalculated = computed(() => {
    return numericString(form.cash) + numericString(form.card);
});

function formatCurrency(value) {
    return new Intl.NumberFormat('es-CL', {
        style: 'currency',
        currency: 'CLP',
        minimumFractionDigits: 0,
    }).format(value);
}
</script>

<template>
    <Head title="Registrar Venta" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-white">Registrar Ingresos del Día</h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-2xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-gray-900 shadow-xl rounded-2xl border border-gray-800">
                    
                    <form @submit.prevent="submit" class="p-8 space-y-8">

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <!-- Fecha -->
                            <div class="space-y-2 md:col-span-2">
                                <label class="block text-sm font-medium text-gray-300">Fecha del cierre</label>
                                <input 
                                    v-model="form.date" 
                                    type="date" 
                                    class="mt-1 block w-full rounded-xl border-gray-700 bg-gray-800 text-white shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-500 focus:ring-opacity-50 transition-colors"
                                    required
                                >
                                <p v-if="form.errors.date" class="text-sm text-red-500">{{ form.errors.date }}</p>
                            </div>

                            <!-- Efectivo -->
                            <div class="space-y-2">
                                <label class="block text-sm font-bold text-emerald-400">💵 Ingresos en Efectivo</label>
                                <div class="relative">
                                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 font-medium">$</span>
                                    <input 
                                        v-model="form.cash" 
                                        type="number" 
                                        step="0.01" 
                                        class="pl-8 block w-full rounded-xl border-gray-700 bg-gray-800 text-white shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-500 focus:ring-opacity-50 transition-colors"
                                        placeholder="0"
                                        required
                                    >
                                </div>
                                <p v-if="form.errors.cash" class="text-sm text-red-500">{{ form.errors.cash }}</p>
                            </div>

                            <!-- Tarjeta -->
                            <div class="space-y-2">
                                <label class="block text-sm font-bold text-cyan-400">💳 Ingresos con Tarjeta</label>
                                <div class="relative">
                                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 font-medium">$</span>
                                    <input 
                                        v-model="form.card" 
                                        type="number" 
                                        step="0.01" 
                                        class="pl-8 block w-full rounded-xl border-gray-700 bg-gray-800 text-white shadow-sm focus:border-cyan-500 focus:ring focus:ring-cyan-500 focus:ring-opacity-50 transition-colors"
                                        placeholder="0"
                                        required
                                    >
                                </div>
                                <p v-if="form.errors.card" class="text-sm text-red-500">{{ form.errors.card }}</p>
                            </div>
                        </div>

                        <!-- Calculadora Visual del Total -->
                        <div class="mt-8 bg-gray-950 p-6 rounded-xl border border-gray-800 flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-500 uppercase tracking-widest">Total sumado</p>
                                <p class="text-3xl font-black text-white mt-1">{{ formatCurrency(totalCalculated) }}</p>
                            </div>
                            <div class="w-12 h-12 bg-gray-800 rounded-full flex items-center justify-center border border-gray-700 shadow-inner">
                                <svg class="w-6 h-6 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                </svg>
                            </div>
                        </div>

                        <!-- Submit -->
                        <div class="flex items-center justify-end pt-4 border-t border-gray-800">
                            <button 
                                type="submit" 
                                :disabled="form.processing"
                                class="inline-flex items-center px-6 py-3 bg-emerald-600 border border-transparent rounded-xl font-bold text-sm text-white uppercase tracking-widest hover:bg-emerald-500 active:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 focus:ring-offset-gray-900 transition-all disabled:opacity-50 disabled:cursor-not-allowed shadow-lg shadow-emerald-900/50"
                            >
                                Guardar Cierre del Día
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
