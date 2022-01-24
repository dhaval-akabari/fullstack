<template>
    <div>
        <!-- 404 Error Text -->
        <div class="text-center">
            <div class="error mx-auto" data-text="404">404</div>
            <p class="lead text-gray-800 mb-5">Page Not Found</p>
            <p class="text-gray-500 mb-0">
                The page you are looking for does not exist or is unavailable..
            </p>
            <router-link :to="{ name: 'dashboard' }">
                <span>&larr; Back to Dashboard</span>
            </router-link>
        </div>
    </div>
</template>
