<script setup lang="ts">
import {
    Table,
    TableBody,
    TableCaption,
    TableCell,
    TableEmpty,
    TableHead,
    TableHeader,
    TableRow,
} from '@/components/ui/table';
import AppLayout from '@/layouts/AppLayout.vue';
import { dashboard } from '@/routes';
import { create, destroy, edit, index, show } from '@/routes/admin/users';
import { type BreadcrumbItem } from '@/types';
import { Head, Link } from '@inertiajs/vue3';
import { Edit, Eye, Trash } from 'lucide-vue-next';

// ✅ Receive users from Laravel (Inertia props)
const props = defineProps<{
    users: {
        id: number;
        name: string;
        email: string;
    }[];
}>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: dashboard().url },
    { title: 'Users', href: index().url },
];

// ✅ Access users via props
const users = props.users;
</script>

<template>
    <Head title="Users" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div
            class="flex flex-col gap-4 rounded-xl border border-sidebar-border/70 p-4 dark:border-sidebar-border"
        >
            <div class="flex items-center justify-between">
                <h2
                    class="text-2xl font-semibold text-gray-800 dark:text-gray-100"
                >
                    User Management
                </h2>

                <!-- ✅ Create User Button -->
                <Link
                    :href="create()"
                    class="rounded-md bg-amber-500 px-4 py-2 text-sm font-medium text-white hover:bg-amber-600 focus:ring-2 focus:ring-amber-400 focus:outline-none"
                >
                    + Add User
                </Link>
            </div>

            <div
                class="relative overflow-x-auto rounded-xl border border-sidebar-border/70 dark:border-sidebar-border"
            >
                <Table>
                    <TableCaption>List of all registered users</TableCaption>

                    <TableHeader>
                        <TableRow>
                            <TableHead>ID</TableHead>
                            <TableHead>Name</TableHead>
                            <TableHead>Email</TableHead>
                            <TableHead class="text-right">Actions</TableHead>
                        </TableRow>
                    </TableHeader>

                    <TableBody>
                        <template v-if="users.length">
                            <TableRow v-for="user in users" :key="user.id">
                                <TableCell>{{ user.id }}</TableCell>
                                <TableCell>{{ user.name }}</TableCell>
                                <TableCell>{{ user.email }}</TableCell>
                                <TableCell class="flex justify-end space-x-3">
                                    <Link
                                        :href="show(user.id)"
                                        class="text-green-500 hover:underline"
                                    >
                                        <Eye class="inline h-4 w-4" />
                                    </Link>
                                    <Link
                                        :href="edit(user.id)"
                                        class="text-blue-500 hover:underline"
                                    >
                                        <Edit class="inline h-4 w-4" />
                                    </Link>
                                    <Link
                                        :href="destroy(user.id)"
                                        class="text-red-500 hover:underline"
                                    >
                                        <Trash class="inline h-4 w-4" />
                                    </Link>
                                </TableCell>
                            </TableRow>
                        </template>

                        <template v-else>
                            <TableEmpty>No users found.</TableEmpty>
                        </template>
                    </TableBody>
                </Table>
            </div>
        </div>
    </AppLayout>
</template>
