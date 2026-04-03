<script setup>
import { ref } from 'vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import AppDock from '@/Components/AppDock.vue';
import { Link } from '@inertiajs/vue3';

const showingNavigationDropdown = ref(false);
</script>

<template>
    <div class="min-h-screen bg-gray-950 text-gray-100">

        <!-- Top navbar -->
        <nav class="bg-gray-900 border-b border-gray-800">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="flex h-14 items-center justify-between">

                    <div class="flex items-center gap-6">
                        <Link :href="route('dashboard')" class="text-white font-bold text-lg tracking-tight hover:text-indigo-400 transition-colors">
                            📦 Inventario
                        </Link>

                        <div class="hidden sm:flex items-center gap-1">
                            <Link :href="route('dashboard')" class="px-3 py-1.5 rounded-md text-sm transition-colors"
                                :class="route().current('dashboard') ? 'bg-gray-800 text-white' : 'text-gray-400 hover:text-white hover:bg-gray-800'">
                                Inicio
                            </Link>
                            <Link :href="route('suppliers.index')" class="px-3 py-1.5 rounded-md text-sm transition-colors"
                                :class="route().current('suppliers.*') ? 'bg-gray-800 text-white' : 'text-gray-400 hover:text-white hover:bg-gray-800'">
                                Proveedores
                            </Link>
                            <Link :href="route('products.index')" class="px-3 py-1.5 rounded-md text-sm transition-colors"
                                :class="route().current('products.*') ? 'bg-gray-800 text-white' : 'text-gray-400 hover:text-white hover:bg-gray-800'">
                                Productos
                            </Link>
                            <Link :href="route('sales.index')" class="px-3 py-1.5 rounded-md text-sm transition-colors"
                                :class="route().current('sales.*') ? 'bg-gray-800 text-white' : 'text-gray-400 hover:text-white hover:bg-gray-800'">
                                Ventas
                            </Link>
                        </div>
                    </div>

                    <div class="hidden sm:flex items-center">
                        <Dropdown align="right" width="48">
                            <template #trigger>
                                <button type="button" class="flex items-center gap-2 px-3 py-1.5 rounded-md text-sm text-gray-300 hover:text-white hover:bg-gray-800 transition-colors">
                                    <span class="w-6 h-6 rounded-full bg-indigo-600 flex items-center justify-center text-xs font-bold text-white uppercase">
                                        {{ $page.props.auth.user.name.charAt(0) }}
                                    </span>
                                    {{ $page.props.auth.user.name }}
                                    <svg class="w-4 h-4 text-gray-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </template>
                            <template #content>
                                <DropdownLink :href="route('profile.edit')">Perfil</DropdownLink>
                                <DropdownLink :href="route('logout')" method="post" as="button">Cerrar sesión</DropdownLink>
                            </template>
                        </Dropdown>
                    </div>

                    <!-- Hamburger mobile -->
                    <div class="-me-2 flex items-center sm:hidden">
                        <button @click="showingNavigationDropdown = !showingNavigationDropdown"
                            class="p-2 rounded-md text-gray-400 hover:text-white hover:bg-gray-800 transition-colors">
                            <svg class="h-5 w-5" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                <path :class="{ hidden: showingNavigationDropdown, 'inline-flex': !showingNavigationDropdown }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                                <path :class="{ hidden: !showingNavigationDropdown, 'inline-flex': showingNavigationDropdown }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Menú mobile -->
            <div :class="{ block: showingNavigationDropdown, hidden: !showingNavigationDropdown }" class="sm:hidden border-t border-gray-800">
                <div class="space-y-1 px-4 py-3">
                    <Link :href="route('dashboard')" class="block px-3 py-2 rounded-md text-sm text-gray-300 hover:text-white hover:bg-gray-800">Inicio</Link>
                    <Link :href="route('suppliers.index')" class="block px-3 py-2 rounded-md text-sm text-gray-300 hover:text-white hover:bg-gray-800">Proveedores</Link>
                    <Link :href="route('products.index')" class="block px-3 py-2 rounded-md text-sm text-gray-300 hover:text-white hover:bg-gray-800">Productos</Link>
                    <Link :href="route('sales.index')" class="block px-3 py-2 rounded-md text-sm text-gray-300 hover:text-white hover:bg-gray-800">Ventas</Link>
                </div>
                <div class="border-t border-gray-800 px-4 py-3">
                    <p class="text-sm text-gray-400">{{ $page.props.auth.user.name }}</p>
                    <div class="mt-2 space-y-1">
                        <Link :href="route('profile.edit')" class="block px-3 py-2 rounded-md text-sm text-gray-300 hover:text-white hover:bg-gray-800">Perfil</Link>
                        <Link :href="route('logout')" method="post" as="button" class="block w-full text-left px-3 py-2 rounded-md text-sm text-gray-300 hover:text-white hover:bg-gray-800">Cerrar sesión</Link>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Page header -->
        <header class="bg-gray-900 border-b border-gray-800" v-if="$slots.header">
            <div class="mx-auto max-w-7xl px-4 py-4 sm:px-6 lg:px-8">
                <slot name="header" />
            </div>
        </header>

        <!-- Contenido — padding-bottom para que el dock no tape nada -->
        <main class="pb-28">
            <slot />
        </main>

        <!-- Dock global -->
        <AppDock />
    </div>
</template>
