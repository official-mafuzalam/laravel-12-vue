<!-- resources/js/Components/Header.vue -->
<script setup lang="ts">
import { dashboard, login, register } from '@/routes';
import { Link, usePage } from '@inertiajs/vue3';

// ✅ Access Vite env variable in script
const appName = import.meta.env.VITE_APP_NAME || 'My App';

const page = usePage();
</script>

<template>
    <header
        class="fixed top-0 left-0 z-50 w-full border-b border-gray-200 bg-white/90 shadow-sm backdrop-blur-md dark:border-gray-800 dark:bg-gray-900/90"
    >
        <nav
            class="mx-auto flex max-w-7xl items-center justify-between px-6 py-3 lg:px-8"
        >
            <!-- Brand / Logo -->
            <div class="flex items-center space-x-2">
                <img
                    src="/logo.svg"
                    alt="Logo"
                    class="h-8 w-8"
                    onerror="this.style.display='none'"
                />
                <h1
                    class="text-lg font-semibold text-gray-800 dark:text-gray-100"
                >
                    {{ appName }}
                </h1>
            </div>

            <!-- Nav Links -->
            <div class="flex items-center space-x-4">
                <Link
                    v-if="page.props.auth && page.props.auth.user"
                    :href="dashboard()"
                    class="rounded-md border border-transparent bg-amber-500 px-5 py-2 text-sm font-medium text-white hover:bg-amber-600 focus:ring-2 focus:ring-amber-400 focus:outline-none dark:bg-amber-600 dark:hover:bg-amber-500"
                >
                    Dashboard
                </Link>

                <template v-else>
                    <Link
                        :href="login()"
                        class="rounded-md border border-transparent px-5 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-800"
                    >
                        Log in
                    </Link>
                    <Link
                        :href="register()"
                        class="rounded-md border border-amber-500 px-5 py-2 text-sm font-medium text-amber-600 hover:bg-amber-50 dark:border-amber-400 dark:text-amber-400 dark:hover:bg-gray-800"
                    >
                        Register
                    </Link>
                </template>
            </div>
        </nav>
    </header>
</template>
