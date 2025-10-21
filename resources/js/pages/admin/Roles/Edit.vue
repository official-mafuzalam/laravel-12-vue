<template>
    <AdminLayout>
        <div class="p-6">
            <!-- Header -->
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold text-gray-900">Edit Role: {{ role.name }}</h1>
                <Link 
                    :href="route('admin.roles.index')"
                    class="text-gray-600 hover:text-gray-900"
                >
                    Back to Roles
                </Link>
            </div>

            <!-- Form -->
            <div class="bg-white shadow-md rounded-lg p-6">
                <form @submit.prevent="submit">
                    <!-- Role Name -->
                    <div class="mb-6">
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                            Role Name *
                        </label>
                        <input
                            type="text"
                            id="name"
                            v-model="form.name"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                            required
                        />
                        <div v-if="form.errors.name" class="text-red-600 text-sm mt-1">
                            {{ form.errors.name }}
                        </div>
                    </div>

                    <!-- Permissions -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-3">
                            Permissions
                        </label>
                        
                        <div v-for="(groupPermissions, groupName) in permissions" :key="groupName" class="mb-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-3 capitalize">{{ groupName }}</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                                <label 
                                    v-for="permission in groupPermissions" 
                                    :key="permission.id"
                                    class="flex items-center space-x-2 p-2 border border-gray-200 rounded hover:bg-gray-50"
                                >
                                    <input
                                        type="checkbox"
                                        :value="permission.name"
                                        v-model="form.permissions"
                                        class="rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                                    />
                                    <span class="text-sm text-gray-700">{{ permission.name }}</span>
                                </label>
                            </div>
                        </div>
                        
                        <div v-if="form.errors.permissions" class="text-red-600 text-sm mt-1">
                            {{ form.errors.permissions }}
                        </div>
                    </div>

                    <!-- Buttons -->
                    <div class="flex justify-end space-x-3">
                        <Link 
                            :href="route('admin.roles.index')"
                            class="bg-gray-300 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-400 transition duration-200"
                        >
                            Cancel
                        </Link>
                        <button
                            type="submit"
                            :disabled="form.processing"
                            class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 disabled:opacity-50 transition duration-200"
                        >
                            {{ form.processing ? 'Updating...' : 'Update Role' }}
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
    role: Object,
    permissions: Object
});

const form = useForm({
    name: props.role.name,
    permissions: props.role.permissions
});

const submit = () => {
    form.put(route('admin.roles.update', props.role.id));
};
</script>