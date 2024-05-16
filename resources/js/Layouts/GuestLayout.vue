<script setup>
import ApplicationLogo from '@/Components/ApplicationLogo.vue';</script>

<template>
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0">
        <div>
            <Link href="/">
                <ApplicationLogo class="w-64 fill-current text-gray-500"/>
            </Link>
        </div>

        <slot/>

    </div>
</template>
