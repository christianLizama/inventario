<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';

const form = useForm({
    name: '',
    phone: '',
    email: '',
});

function submit() {
    form.post(route('suppliers.store'));
}
</script>

<template>
    <Head title="Nuevo Proveedor" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-lg font-semibold text-white">Nuevo Proveedor</h2>
        </template>

        <div class="py-8 px-4 sm:px-6 lg:px-8 max-w-lg mx-auto">
            <div class="bg-gray-900 border border-gray-800 rounded-xl p-8">

                <form @submit.prevent="submit" class="space-y-6">

                    <!-- Nombre -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-300 mb-1.5">
                            Nombre <span class="text-cyan-500">*</span>
                        </label>
                        <input
                            id="name"
                            v-model="form.name"
                            type="text"
                            placeholder="Ej: Distribuidora Norte"
                            class="w-full bg-gray-800 border border-gray-700 text-white placeholder-gray-600 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:border-transparent transition"
                        />
                        <p v-if="form.errors.name" class="mt-1.5 text-xs text-red-400">{{ form.errors.name }}</p>
                    </div>

                    <!-- Teléfono -->
                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-300 mb-1.5">
                            Teléfono <span class="text-gray-600 font-normal">(opcional)</span>
                        </label>
                        <input
                            id="phone"
                            v-model="form.phone"
                            type="text"
                            placeholder="+56 9 1234 5678"
                            class="w-full bg-gray-800 border border-gray-700 text-white placeholder-gray-600 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:border-transparent transition"
                        />
                        <p v-if="form.errors.phone" class="mt-1.5 text-xs text-red-400">{{ form.errors.phone }}</p>
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-300 mb-1.5">
                            Email <span class="text-gray-600 font-normal">(opcional)</span>
                        </label>
                        <input
                            id="email"
                            v-model="form.email"
                            type="email"
                            placeholder="proveedor@ejemplo.com"
                            class="w-full bg-gray-800 border border-gray-700 text-white placeholder-gray-600 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:border-transparent transition"
                        />
                        <p v-if="form.errors.email" class="mt-1.5 text-xs text-red-400">{{ form.errors.email }}</p>
                    </div>

                    <!-- Botones -->
                    <div class="flex justify-end gap-3 pt-1">
                        <a
                            :href="route('suppliers.index')"
                            class="px-4 py-2 text-sm text-gray-400 border border-gray-700 rounded-lg hover:bg-gray-800 transition-colors"
                        >
                            Cancelar
                        </a>
                        <button
                            type="submit"
                            :disabled="form.processing"
                            class="px-6 py-2 text-sm font-medium text-white bg-cyan-600 hover:bg-cyan-500 disabled:opacity-50 rounded-lg transition-colors"
                        >
                            {{ form.processing ? 'Guardando...' : 'Guardar Proveedor' }}
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
