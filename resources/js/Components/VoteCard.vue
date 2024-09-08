<template>
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100 dark:bg-gray-900">
        <div>
            <slot name="logo" />
        </div>
        <h1 class="mt-8 text-2xl font-medium text-gray-900 dark:text-white">
            <slot name="name" />
        </h1>

        <div
            class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white dark:bg-gray-800 shadow-md overflow-hidden sm:rounded-lg">
            <slot />
        </div>
        <footer class="py-5 text-center text-sm text-black dark:text-white/70">
            © 2024 Vote system v0.1 All Rights Reserved. made with by <a href="https://osp101.dev" target="_blank">OSP101</a>. Powered by Laravel Jetstream
        </footer>
    </div>
</template>
