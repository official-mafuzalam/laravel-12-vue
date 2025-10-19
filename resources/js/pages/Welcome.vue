<!-- resources/js/Pages/Welcome.vue -->
<script setup lang="ts">
import MainLayout from '@/Layouts/PublicLayout.vue';
import { Head } from '@inertiajs/vue3';
</script>

<template>
  <Head title="Welcome">
    <link rel="preconnect" href="https://rsms.me/" />
    <link rel="stylesheet" href="https://rsms.me/inter/inter.css" />
  </Head>

  <MainLayout>
    <!-- Page content goes into the default slot of MainLayout -->
    <template #default>
      <!-- Replace the following with your page body -->
      <section class="p-6">
        <h1 class="text-3xl font-bold text-blue-800 dark:text-blue-100">Welcome to our site</h1>
        <p class="mt-3">This is the home page body. Edit this section.</p>
      </section>
    </template>
  </MainLayout>
</template>
