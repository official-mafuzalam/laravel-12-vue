<template>
    <AdminLayout>
        <div class="p-6">
            <!-- Header -->
            <div class="mb-6 flex items-center justify-between">
                <h1 class="text-2xl font-bold text-gray-900">
                    Create New Role
                </h1>
                <Link
                    :href="route('admin.roles.index')"
                    class="text-gray-600 hover:text-gray-900"
                >
                    Back to Roles
                </Link>
            </div>

            <!-- Form -->
            <div class="rounded-lg bg-white p-6 shadow-md">
                <form @submit.prevent="submit">
                    <!-- Role Name -->
                    <div class="mb-6">
                        <label
                            for="name"
                            class="mb-2 block text-sm font-medium text-gray-700"
                        >
                            Role Name *
                        </label>
                        <input
                            type="text"
                            id="name"
                            v-model="form.name"
                            class="w-full rounded-md border border-gray-300 px-3 py-2 shadow-sm focus:border-blue-500 focus:ring-blue-500 focus:outline-none"
                            required
                        />
                        <div
                            v-if="form.errors.name"
                            class="mt-1 text-sm text-red-600"
                        >
                            {{ form.errors.name }}
                        </div>
                    </div>

                    <!-- Permissions -->
                    <div class="mb-6">
                        <label
                            class="mb-3 block text-sm font-medium text-gray-700"
                        >
                            Permissions
                        </label>

                        <div
                            v-for="(groupPermissions, groupName) in permissions"
                            :key="groupName"
                            class="mb-6"
                        >
                            <h3
                                class="mb-3 text-lg font-medium text-gray-900 capitalize"
                            >
                                {{ groupName }}
                            </h3>
                            <div
                                class="grid grid-cols-1 gap-3 md:grid-cols-2 lg:grid-cols-3"
                            >
                                <label
                                    v-for="permission in groupPermissions"
                                    :key="permission.id"
                                    class="flex items-center space-x-2 rounded border border-gray-200 p-2 hover:bg-gray-50"
                                >
                                    <input
                                        type="checkbox"
                                        :value="permission.name"
                                        v-model="form.permissions"
                                        class="rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                                    />
                                    <span class="text-sm text-gray-700">{{
                                        permission.name
                                    }}</span>
                                </label>
                            </div>
                        </div>

                        <div
                            v-if="form.errors.permissions"
                            class="mt-1 text-sm text-red-600"
                        >
                            {{ form.errors.permissions }}
                        </div>
                    </div>

                    <!-- Buttons -->
                    <div class="flex justify-end space-x-3">
                        <Link
                            :href="route('admin.roles.index')"
                            class="rounded-md bg-gray-300 px-4 py-2 text-gray-700 transition duration-200 hover:bg-gray-400"
                        >
                            Cancel
                        </Link>
                        <button
                            type="submit"
                            :disabled="form.processing"
                            class="rounded-md bg-blue-500 px-4 py-2 text-white transition duration-200 hover:bg-blue-600 disabled:opacity-50"
                        >
                            {{
                                form.processing ? 'Creating...' : 'Create Role'
                            }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AdminLayout>
</template>

<script setup>
import AdminLayout from '@/layouts/AppLayout.vue';
import { Link, useForm } from '@inertiajs/vue3';

const props = defineProps({
    permissions: Object,
});

const form = useForm({
    name: '',
    permissions: [],
});

const submit = () => {
    form.post(route('admin.roles.store'));
};
</script>
