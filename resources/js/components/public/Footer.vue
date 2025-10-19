<script setup lang="ts">
// Footer is static in this example
</script>

<template>
    <footer
        class="mt-8 w-full max-w-[335px] text-center text-sm text-gray-600 lg:max-w-4xl"
    >
        <div class="py-4">
            <p>
                © {{ new Date().getFullYear() }} Your Company — All rights
                reserved.
            </p>
            <p class="text-xs">Made with ❤️ using Laravel + Inertia + Vue 3</p>
        </div>
    </footer>
</template>
