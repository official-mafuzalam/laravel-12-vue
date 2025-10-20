<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { dashboard } from '@/routes';
import { type BreadcrumbItem } from '@/types';
import { Head, Link, router } from '@inertiajs/vue3';

// ✅ Import your route helpers
import { destroy, edit, index, status } from '@/routes/admin/users';
import {
    Activity,
    ArrowLeft,
    Ban,
    Calendar,
    CheckCircle,
    Clock,
    Edit,
    Mail,
    Shield,
    Trash2,
    User,
} from 'lucide-vue-next';

// ✅ Receive user data from Laravel
const props = defineProps<{
    user: {
        id: number;
        name: string;
        email: string;
        status: 'active' | 'blocked';
        last_seen_at?: string;
        created_at?: string;
        updated_at?: string;
    };
}>();

// Breadcrumbs
const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: dashboard().url },
    { title: 'Users', href: index().url },
    { title: props.user.name, href: '' },
];

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

// ✅ Format relative time
function formatRelativeTime(dateString: string) {
    if (!dateString) return 'Never';

    const date = new Date(dateString);
    const now = new Date();
    const diffInMs = now.getTime() - date.getTime();
    const diffInMinutes = Math.floor(diffInMs / (1000 * 60));
    const diffInHours = Math.floor(diffInMs / (1000 * 60 * 60));
    const diffInDays = Math.floor(diffInMs / (1000 * 60 * 60 * 24));

    if (diffInMinutes < 1) return 'Just now';
    if (diffInMinutes < 60) return `${diffInMinutes} min ago`;
    if (diffInHours < 24) return `${diffInHours} hours ago`;
    if (diffInDays < 7) return `${diffInDays} days ago`;

    return formatDate(dateString);
}

// ✅ Block/Unblock user function
function toggleUserStatus() {
    const action = props.user.status === 'active' ? 'block' : 'unblock';
    const actionText = props.user.status === 'active' ? 'block' : 'unblock';

    if (confirm(`Are you sure you want to ${actionText} this user?`)) {
        router.put(
            status(props.user.id).url,
            {
                status: props.user.status === 'active' ? 'blocked' : 'active',
            },
            {
                preserveScroll: true,
                onSuccess: () => {
                    // Success message will be shown automatically via flash
                },
                onError: (errors) => {
                    if (window.showToast) {
                        window.showToast.error(
                            'Failed to update user status. Please try again.',
                        );
                    }
                },
            },
        );
    }
}

// ✅ Delete user function
function deleteUser() {
    if (
        confirm(
            'Are you sure you want to delete this user? This action cannot be undone.',
        )
    ) {
        router.delete(destroy(props.user.id).url, {
            preserveScroll: true,
            onSuccess: () => {
                // Redirect will happen automatically
            },
            onError: (errors) => {
                if (window.showToast) {
                    window.showToast.error(
                        'Failed to delete user. Please try again.',
                    );
                }
            },
        });
    }
}

// ✅ Check if user is recently active (within last 5 minutes)
function isRecentlyActive() {
    if (!props.user.last_seen_at) return false;
    const lastSeen = new Date(props.user.last_seen_at);
    const now = new Date();
    const diffInMinutes = (now.getTime() - lastSeen.getTime()) / (1000 * 60);
    return diffInMinutes < 5;
}
</script>

<template>
    <Head :title="`User Profile - ${user.name}`" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex flex-col gap-6">
            <!-- Header -->
            <div class="flex items-center justify-between gap-4 rounded-xl border border-sidebar-border/70 p-4 dark:border-sidebar-border">
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
                        User Profile
                    </h1>
                </div>

                <div class="flex space-x-2">
                    <Link
                        :href="edit(user.id)"
                        class="flex items-center gap-2 rounded-md bg-amber-500 px-4 py-2 text-sm font-medium text-white transition-colors hover:bg-amber-600"
                    >
                        <Edit class="h-4 w-4" />
                        Edit User
                    </Link>
                </div>
            </div>

            <!-- Main Content -->
            <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
                <!-- Left Column - User Info -->
                <div class="space-y-6 lg:col-span-2">
                    <!-- Profile Card -->
                    <div
                        class="rounded-xl border border-sidebar-border/70 p-6 dark:border-sidebar-border"
                    >
                        <div class="flex items-start justify-between">
                            <div class="flex items-center gap-4">
                                <div
                                    class="flex h-16 w-16 items-center justify-center rounded-full bg-gradient-to-br from-amber-500 to-orange-500"
                                >
                                    <User class="h-8 w-8 text-white" />
                                </div>
                                <div>
                                    <h2
                                        class="text-2xl font-bold text-gray-900 dark:text-gray-100"
                                    >
                                        {{ user.name }}
                                    </h2>
                                    <p class="text-gray-600 dark:text-gray-400">
                                        User ID: #{{ user.id }}
                                    </p>
                                </div>
                            </div>
                            <div class="text-right">
                                <span
                                    class="inline-flex items-center gap-1 rounded-full px-3 py-1 text-sm font-medium"
                                    :class="{
                                        'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200':
                                            user.status === 'active',
                                        'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200':
                                            user.status === 'blocked',
                                    }"
                                >
                                    <Activity class="h-3 w-3" />
                                    {{
                                        user.status === 'active'
                                            ? 'Active'
                                            : 'Blocked'
                                    }}
                                </span>
                                <p
                                    class="mt-1 text-sm text-gray-500 dark:text-gray-400"
                                >
                                    {{
                                        isRecentlyActive()
                                            ? 'Recently active'
                                            : 'Not recently active'
                                    }}
                                </p>
                            </div>
                        </div>

                        <div class="mt-6 grid grid-cols-1 gap-4 sm:grid-cols-2">
                            <div class="flex items-center gap-3">
                                <div
                                    class="rounded-lg bg-blue-50 p-2 dark:bg-blue-900/20"
                                >
                                    <Mail
                                        class="h-5 w-5 text-blue-600 dark:text-blue-400"
                                    />
                                </div>
                                <div>
                                    <p
                                        class="text-sm font-medium text-gray-900 dark:text-gray-100"
                                    >
                                        Email
                                    </p>
                                    <p
                                        class="text-sm text-gray-600 dark:text-gray-400"
                                    >
                                        {{ user.email }}
                                    </p>
                                </div>
                            </div>

                            <div class="flex items-center gap-3">
                                <div
                                    class="rounded-lg bg-green-50 p-2 dark:bg-green-900/20"
                                >
                                    <Calendar
                                        class="h-5 w-5 text-green-600 dark:text-green-400"
                                    />
                                </div>
                                <div>
                                    <p
                                        class="text-sm font-medium text-gray-900 dark:text-gray-100"
                                    >
                                        Member Since
                                    </p>
                                    <p
                                        class="text-sm text-gray-600 dark:text-gray-400"
                                    >
                                        {{ formatDate(user.created_at) }}
                                    </p>
                                </div>
                            </div>

                            <div class="flex items-center gap-3">
                                <div
                                    class="rounded-lg bg-purple-50 p-2 dark:bg-purple-900/20"
                                >
                                    <Clock
                                        class="h-5 w-5 text-purple-600 dark:text-purple-400"
                                    />
                                </div>
                                <div>
                                    <p
                                        class="text-sm font-medium text-gray-900 dark:text-gray-100"
                                    >
                                        Last Seen
                                    </p>
                                    <p
                                        class="text-sm text-gray-600 dark:text-gray-400"
                                    >
                                        {{
                                            user.last_seen_at
                                                ? formatRelativeTime(
                                                      user.last_seen_at,
                                                  )
                                                : 'Never'
                                        }}
                                    </p>
                                </div>
                            </div>

                            <div class="flex items-center gap-3">
                                <div
                                    class="rounded-lg bg-amber-50 p-2 dark:bg-amber-900/20"
                                >
                                    <Shield
                                        class="h-5 w-5 text-amber-600 dark:text-amber-400"
                                    />
                                </div>
                                <div>
                                    <p
                                        class="text-sm font-medium text-gray-900 dark:text-gray-100"
                                    >
                                        Account Status
                                    </p>
                                    <p
                                        class="text-sm text-gray-600 capitalize dark:text-gray-400"
                                    >
                                        {{ user.status }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Activity Stats -->
                    <div
                        class="rounded-xl border border-sidebar-border/70 p-6 dark:border-sidebar-border"
                    >
                        <h3
                            class="mb-4 text-lg font-semibold text-gray-900 dark:text-gray-100"
                        >
                            Account Information
                        </h3>
                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                            <div
                                class="rounded-lg bg-gray-50 p-4 dark:bg-gray-800"
                            >
                                <p
                                    class="text-sm font-medium text-gray-600 dark:text-gray-400"
                                >
                                    Profile Created
                                </p>
                                <p
                                    class="text-lg font-semibold text-gray-900 dark:text-gray-100"
                                >
                                    {{ formatDate(user.created_at) }}
                                </p>
                            </div>
                            <div
                                class="rounded-lg bg-gray-50 p-4 dark:bg-gray-800"
                            >
                                <p
                                    class="text-sm font-medium text-gray-600 dark:text-gray-400"
                                >
                                    Last Updated
                                </p>
                                <p
                                    class="text-lg font-semibold text-gray-900 dark:text-gray-100"
                                >
                                    {{ formatDate(user.updated_at) }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column - Actions & Details -->
                <div class="space-y-6">
                    <!-- Quick Actions -->
                    <div
                        class="rounded-xl border border-sidebar-border/70 p-6 dark:border-sidebar-border"
                    >
                        <h3
                            class="mb-4 text-lg font-semibold text-gray-900 dark:text-gray-100"
                        >
                            Quick Actions
                        </h3>
                        <div class="space-y-3">
                            <Link
                                :href="edit(user.id)"
                                class="flex w-full items-center justify-center gap-2 rounded-lg bg-amber-500 px-4 py-3 text-sm font-medium text-white transition-colors hover:bg-amber-600"
                            >
                                <Edit class="h-4 w-4" />
                                Edit Profile
                            </Link>

                            <button
                                @click="toggleUserStatus"
                                class="flex w-full items-center justify-center gap-2 rounded-lg px-4 py-3 text-sm font-medium transition-colors"
                                :class="{
                                    'bg-red-100 text-red-800 hover:bg-red-200 dark:bg-red-900 dark:text-red-200 dark:hover:bg-red-800':
                                        user.status === 'active',
                                    'bg-green-100 text-green-800 hover:bg-green-200 dark:bg-green-900 dark:text-green-200 dark:hover:bg-green-800':
                                        user.status === 'blocked',
                                }"
                            >
                                <Ban
                                    v-if="user.status === 'active'"
                                    class="h-4 w-4"
                                />
                                <CheckCircle v-else class="h-4 w-4" />
                                {{
                                    user.status === 'active'
                                        ? 'Block User'
                                        : 'Unblock User'
                                }}
                            </button>

                            <button
                                @click="deleteUser"
                                class="flex w-full items-center justify-center gap-2 rounded-lg bg-red-600 px-4 py-3 text-sm font-medium text-white transition-colors hover:bg-red-700"
                            >
                                <Trash2 class="h-4 w-4" />
                                Delete User
                            </button>
                        </div>
                    </div>

                    <!-- Status Information -->
                    <div
                        class="rounded-xl border border-sidebar-border/70 p-6 dark:border-sidebar-border"
                    >
                        <h3
                            class="mb-4 text-lg font-semibold text-gray-900 dark:text-gray-100"
                        >
                            Status Information
                        </h3>
                        <div class="space-y-3">
                            <div class="flex items-center justify-between">
                                <span
                                    class="text-sm text-gray-600 dark:text-gray-400"
                                    >Current Status</span
                                >
                                <span
                                    class="rounded-full px-2 py-1 text-xs font-medium"
                                    :class="{
                                        'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200':
                                            user.status === 'active',
                                        'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200':
                                            user.status === 'blocked',
                                    }"
                                >
                                    {{
                                        user.status === 'active'
                                            ? 'Active'
                                            : 'Blocked'
                                    }}
                                </span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span
                                    class="text-sm text-gray-600 dark:text-gray-400"
                                    >Last Activity</span
                                >
                                <span
                                    class="text-sm text-gray-900 dark:text-gray-100"
                                >
                                    {{
                                        user.last_seen_at
                                            ? formatRelativeTime(
                                                  user.last_seen_at,
                                              )
                                            : 'Never'
                                    }}
                                </span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span
                                    class="text-sm text-gray-600 dark:text-gray-400"
                                    >Account Age</span
                                >
                                <span
                                    class="text-sm text-gray-900 dark:text-gray-100"
                                >
                                    {{
                                        user.created_at
                                            ? Math.floor(
                                                  (new Date().getTime() -
                                                      new Date(
                                                          user.created_at,
                                                      ).getTime()) /
                                                      (1000 * 60 * 60 * 24),
                                              ) + ' days'
                                            : 'N/A'
                                    }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Danger Zone -->
                    <div
                        class="rounded-xl border border-red-200 bg-red-50 p-6 dark:border-red-800 dark:bg-red-900/20"
                    >
                        <h3
                            class="mb-3 text-lg font-semibold text-red-800 dark:text-red-200"
                        >
                            Danger Zone
                        </h3>
                        <p class="mb-4 text-sm text-red-700 dark:text-red-300">
                            Once you delete a user, there is no going back.
                            Please be certain.
                        </p>
                        <button
                            @click="deleteUser"
                            class="flex w-full items-center justify-center gap-2 rounded-lg border border-red-300 bg-white px-4 py-2 text-sm font-medium text-red-700 transition-colors hover:bg-red-50 dark:border-red-600 dark:bg-red-900 dark:text-red-200 dark:hover:bg-red-800"
                        >
                            <Trash2 class="h-4 w-4" />
                            Delete This User
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
