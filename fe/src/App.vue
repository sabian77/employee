<script setup>
import { RouterLink, RouterView } from 'vue-router';
</script>

<template>
  <h1>Hello App!</h1>
  <p><strong>Current route path:</strong> {{ $route.fullPath }}</p>
  <nav>
    <RouterLink to="/">Home</RouterLink>
    <RouterLink to="/login"> Login</RouterLink>
    <RouterLink to="/division"> Division</RouterLink>
  </nav>
  <main>
    <RouterView />
  </main>
</template>
