<template>
    <AdminLayout>
        <div class="p-6">
            <div class="mb-6 flex items-center justify-between">
                <h1 class="text-2xl font-bold">Roles Management</h1>
                <Link
                    v-if="hasPermission('create roles')"
                    :href="route('admin.roles.create')"
                    class="rounded bg-blue-500 px-4 py-2 text-white hover:bg-blue-600"
                >
                    Create Role
                </Link>
            </div>

            <div class="overflow-hidden rounded-lg bg-white shadow">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
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
                        <tr v-for="role in roles" :key="role.id">
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
                                </div>
                            </td>
                            <td
                                class="px-6 py-4 text-sm whitespace-nowrap text-gray-500"
                            >
                                {{ role.users_count }} users
                            </td>
                            <td
                                class="px-6 py-4 text-sm font-medium whitespace-nowrap"
                            >
                                <Link
                                    :href="route('admin.roles.show', role.id)"
                                    class="mr-3 text-blue-600 hover:text-blue-900"
                                >
                                    View
                                </Link>
                                <Link
                                    v-if="
                                        hasPermission('edit roles') &&
                                        role.name !== 'super-admin'
                                    "
                                    :href="route('admin.roles.edit', role.id)"
                                    class="mr-3 text-indigo-600 hover:text-indigo-900"
                                >
                                    Edit
                                </Link>
                                <button
                                    v-if="
                                        hasPermission('delete roles') &&
                                        role.name !== 'super-admin' &&
                                        role.users_count === 0
                                    "
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

<script setup lang="ts">
import { useAuth } from '@/composables/useAuth';
import AdminLayout from '@/layouts/AdminLayout.vue';
import { Link, router } from '@inertiajs/vue3';

const { hasPermission } = useAuth();

defineProps({
    roles: Array,
});

const deleteRole = (role) => {
    if (confirm(`Are you sure you want to delete ${role.name}?`)) {
        router.delete(route('admin.roles.destroy', role.id));
    }
};
</script>
