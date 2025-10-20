<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { dashboard } from '@/routes';
import { index, show } from '@/routes/admin/users';
import { type BreadcrumbItem } from '@/types';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { Activity, ArrowLeft, Mail, Save, Shield, User } from 'lucide-vue-next';

// ✅ Receive user data from Laravel
const props = defineProps<{
    user: {
        id: number;
        name: string;
        email: string;
        role?: string;
        status?: string;
        created_at?: string;
        updated_at?: string;
    };
}>();

// ✅ Form setup
const form = useForm({
    name: props.user.name,
    email: props.user.email,
    role: props.user.role || 'user',
    status: props.user.status || 'active',
});

// ✅ Breadcrumbs
const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: dashboard().url },
    { title: 'Users', href: index().url },
    { title: `Edit ${props.user.name}`, href: '#' },
];

// ✅ Submit form
function submit() {
    form.put(route('admin.users.update', props.user.id), {
        preserveScroll: true,
        onSuccess: () => {
            // Optional: Show success message or redirect
        },
        onError: (errors) => {
            console.error('Update failed:', errors);
        },
    });
}

// ✅ Format date for display
function formatDate(dateString: string) {
    if (!dateString) return 'N/A';
    return new Date(dateString).toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
    });
}
</script>

<template>
    <Head :title="`Edit User - ${user.name}`" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex flex-col gap-6">
            <!-- Header -->
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <Link
                        :href="index()"
                        class="flex items-center gap-2 text-gray-600 transition-colors hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-100"
                    >
                        <ArrowLeft class="h-4 w-4" />
                        Back to Users
                    </Link>
                    <div class="h-6 w-px bg-gray-300 dark:bg-gray-600"></div>
                    <h1
                        class="text-2xl font-bold text-gray-900 dark:text-gray-100"
                    >
                        Edit User
                    </h1>
                </div>

                <Link
                    :href="show(user.id)"
                    class="flex items-center gap-2 px-4 py-2 text-sm text-gray-600 transition-colors hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-100"
                >
                    <User class="h-4 w-4" />
                    View Profile
                </Link>
            </div>

            <!-- Main Content -->
            <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
                <!-- Form Section -->
                <div class="lg:col-span-2">
                    <div
                        class="rounded-xl border border-sidebar-border/70 p-6 dark:border-sidebar-border"
                    >
                        <form @submit.prevent="submit" class="space-y-6">
                            <!-- Name Field -->
                            <div>
                                <label
                                    for="name"
                                    class="mb-2 flex items-center gap-2 text-sm font-medium text-gray-700 dark:text-gray-300"
                                >
                                    <User class="h-4 w-4" />
                                    Full Name
                                </label>
                                <input
                                    id="name"
                                    v-model="form.name"
                                    type="text"
                                    class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-gray-900 transition-colors focus:border-transparent focus:ring-2 focus:ring-amber-500 focus:outline-none dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100"
                                    :class="{
                                        'border-red-500 dark:border-red-400':
                                            form.errors.name,
                                    }"
                                    placeholder="Enter full name"
                                />
                                <p
                                    v-if="form.errors.name"
                                    class="mt-1 text-sm text-red-600 dark:text-red-400"
                                >
                                    {{ form.errors.name }}
                                </p>
                            </div>

                            <!-- Email Field -->
                            <div>
                                <label
                                    for="email"
                                    class="mb-2 flex items-center gap-2 text-sm font-medium text-gray-700 dark:text-gray-300"
                                >
                                    <Mail class="h-4 w-4" />
                                    Email Address
                                </label>
                                <input
                                    id="email"
                                    v-model="form.email"
                                    type="email"
                                    class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-gray-900 transition-colors focus:border-transparent focus:ring-2 focus:ring-amber-500 focus:outline-none dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100"
                                    :class="{
                                        'border-red-500 dark:border-red-400':
                                            form.errors.email,
                                    }"
                                    placeholder="Enter email address"
                                />
                                <p
                                    v-if="form.errors.email"
                                    class="mt-1 text-sm text-red-600 dark:text-red-400"
                                >
                                    {{ form.errors.email }}
                                </p>
                            </div>

                            <!-- Role Field -->
                            <div>
                                <label
                                    for="role"
                                    class="mb-2 flex items-center gap-2 text-sm font-medium text-gray-700 dark:text-gray-300"
                                >
                                    <Shield class="h-4 w-4" />
                                    Role
                                </label>
                                <select
                                    id="role"
                                    v-model="form.role"
                                    class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-gray-900 transition-colors focus:border-transparent focus:ring-2 focus:ring-amber-500 focus:outline-none dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100"
                                    :class="{
                                        'border-red-500 dark:border-red-400':
                                            form.errors.role,
                                    }"
                                >
                                    <option value="user">User</option>
                                    <option value="moderator">Moderator</option>
                                    <option value="admin">Administrator</option>
                                </select>
                                <p
                                    v-if="form.errors.role"
                                    class="mt-1 text-sm text-red-600 dark:text-red-400"
                                >
                                    {{ form.errors.role }}
                                </p>
                            </div>

                            <!-- Status Field -->
                            <div>
                                <label
                                    for="status"
                                    class="mb-2 flex items-center gap-2 text-sm font-medium text-gray-700 dark:text-gray-300"
                                >
                                    <Activity class="h-4 w-4" />
                                    Status
                                </label>
                                <select
                                    id="status"
                                    v-model="form.status"
                                    class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-gray-900 transition-colors focus:border-transparent focus:ring-2 focus:ring-amber-500 focus:outline-none dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100"
                                    :class="{
                                        'border-red-500 dark:border-red-400':
                                            form.errors.status,
                                    }"
                                >
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                    <option value="suspended">Suspended</option>
                                </select>
                                <p
                                    v-if="form.errors.status"
                                    class="mt-1 text-sm text-red-600 dark:text-red-400"
                                >
                                    {{ form.errors.status }}
                                </p>
                            </div>

                            <!-- Form Actions -->
                            <div
                                class="flex items-center justify-between border-t border-gray-200 pt-6 dark:border-gray-600"
                            >
                                <Link
                                    :href="index()"
                                    class="px-4 py-2 text-sm text-gray-600 transition-colors hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-100"
                                >
                                    Cancel
                                </Link>
                                <button
                                    type="submit"
                                    :disabled="form.processing"
                                    class="flex items-center gap-2 rounded-lg bg-amber-500 px-6 py-2 text-white transition-colors hover:bg-amber-600 focus:ring-2 focus:ring-amber-500 focus:ring-offset-2 focus:outline-none disabled:cursor-not-allowed disabled:opacity-50"
                                >
                                    <Save class="h-4 w-4" />
                                    {{
                                        form.processing
                                            ? 'Saving...'
                                            : 'Save Changes'
                                    }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Sidebar Information -->
                <div class="space-y-6">
                    <!-- User Summary -->
                    <div
                        class="rounded-xl border border-sidebar-border/70 p-6 dark:border-sidebar-border"
                    >
                        <h3
                            class="mb-4 text-lg font-semibold text-gray-900 dark:text-gray-100"
                        >
                            User Information
                        </h3>
                        <div class="space-y-3 text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-600 dark:text-gray-400"
                                    >User ID:</span
                                >
                                <span
                                    class="font-medium text-gray-900 dark:text-gray-100"
                                    >#{{ user.id }}</span
                                >
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600 dark:text-gray-400"
                                    >Created:</span
                                >
                                <span
                                    class="font-medium text-gray-900 dark:text-gray-100"
                                    >{{ formatDate(user.created_at) }}</span
                                >
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600 dark:text-gray-400"
                                    >Last Updated:</span
                                >
                                <span
                                    class="font-medium text-gray-900 dark:text-gray-100"
                                    >{{ formatDate(user.updated_at) }}</span
                                >
                            </div>
                        </div>
                    </div>

                    <!-- Current Status -->
                    <div
                        class="rounded-xl border border-sidebar-border/70 p-6 dark:border-sidebar-border"
                    >
                        <h3
                            class="mb-4 text-lg font-semibold text-gray-900 dark:text-gray-100"
                        >
                            Current Status
                        </h3>
                        <div class="space-y-3">
                            <div class="flex items-center justify-between">
                                <span
                                    class="text-sm text-gray-600 dark:text-gray-400"
                                    >Role:</span
                                >
                                <span
                                    class="rounded-full px-2 py-1 text-xs font-medium capitalize"
                                    :class="{
                                        'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200':
                                            user.role === 'admin',
                                        'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200':
                                            user.role === 'moderator',
                                        'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200':
                                            user.role === 'user',
                                    }"
                                >
                                    {{ user.role }}
                                </span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span
                                    class="text-sm text-gray-600 dark:text-gray-400"
                                    >Status:</span
                                >
                                <span
                                    class="rounded-full px-2 py-1 text-xs font-medium capitalize"
                                    :class="{
                                        'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200':
                                            user.status === 'active',
                                        'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200':
                                            user.status === 'inactive',
                                        'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200':
                                            user.status === 'suspended',
                                    }"
                                >
                                    {{ user.status }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div
                        class="rounded-xl border border-sidebar-border/70 p-6 dark:border-sidebar-border"
                    >
                        <h3
                            class="mb-4 text-lg font-semibold text-gray-900 dark:text-gray-100"
                        >
                            Quick Actions
                        </h3>
                        <div class="space-y-2">
                            <Link
                                :href="show(user.id)"
                                class="flex w-full items-center justify-center gap-2 rounded-lg border border-gray-300 px-4 py-2 text-sm text-gray-700 transition-colors hover:bg-gray-50 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-700"
                            >
                                <User class="h-4 w-4" />
                                View Profile
                            </Link>
                            <button
                                v-if="user.status !== 'suspended'"
                                @click="
                                    form.status = 'suspended';
                                    submit();
                                "
                                :disabled="form.processing"
                                class="flex w-full items-center justify-center gap-2 rounded-lg border border-red-300 px-4 py-2 text-sm text-red-700 transition-colors hover:bg-red-50 disabled:opacity-50 dark:border-red-600 dark:text-red-300 dark:hover:bg-red-900"
                            >
                                <Activity class="h-4 w-4" />
                                Suspend User
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
``