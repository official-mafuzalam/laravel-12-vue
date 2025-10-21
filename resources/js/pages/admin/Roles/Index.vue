<script setup lang="ts">
import AdminLayout from '@/layouts/AppLayout.vue';
import { create, destroy, edit } from '@/routes/admin/roles';
import { Link, router } from '@inertiajs/vue3';

defineProps({
    roles: Object,
});

const deleteRole = (role) => {
    if (confirm(`Are you sure you want to delete "${role.name}"?`)) {
        router.delete(destroy(role.id));
    }
};
</script>

<template>
    <AdminLayout>
        <div class="p-6">
            <!-- Header -->
            <div class="mb-6 flex items-center justify-between">
                <h1 class="text-2xl font-bold text-gray-900">
                    Roles Management
                </h1>
                <Link
                    :href="create()"
                    class="rounded-md bg-amber-500 px-4 py-2 text-sm font-medium text-white hover:bg-amber-600 focus:ring-2 focus:ring-amber-400 focus:outline-none"
                >
                    + Add New Role
                </Link>
            </div>

            <!-- Roles Table -->
            <div class="overflow-hidden rounded-lg bg-white shadow-md">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium tracking-wider text-gray-500 uppercase"
                            >
                                ID
                            </th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium tracking-wider text-gray-500 uppercase"
                            >
                                Name
                            </th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium tracking-wider text-gray-500 uppercase"
                            >
                                Permissions
                            </th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium tracking-wider text-gray-500 uppercase"
                            >
                                Users
                            </th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium tracking-wider text-gray-500 uppercase"
                            >
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white">
                        <tr v-for="role in roles.data" :key="role.id">
                            <td
                                class="px-6 py-4 text-sm whitespace-nowrap text-gray-500"
                            >
                                {{ role.id }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">
                                    {{ role.name }}
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex flex-wrap gap-1">
                                    <span
                                        v-for="permission in role.permissions"
                                        :key="permission.id"
                                        class="inline-flex items-center rounded bg-green-100 px-2 py-1 text-xs font-medium text-green-800"
                                    >
                                        {{ permission.name }}
                                    </span>
                                    <span
                                        v-if="role.permissions.length === 0"
                                        class="text-xs text-gray-500"
                                    >
                                        No permissions
                                    </span>
                                </div>
                            </td>
                            <td
                                class="px-6 py-4 text-sm whitespace-nowrap text-gray-500"
                            >
                                {{ role.users_count }} users
                            </td>
                            <td
                                class="space-x-2 px-6 py-4 text-sm font-medium whitespace-nowrap"
                            >
                                <Link
                                    :href="edit(role.id)"
                                    class="text-indigo-600 hover:text-indigo-900"
                                >
                                    Edit
                                </Link>
                                <button
                                    v-if="role.users_count === 0"
                                    @click="deleteRole(role)"
                                    class="text-red-600 hover:text-red-900"
                                >
                                    Delete
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </AdminLayout>
</template>
