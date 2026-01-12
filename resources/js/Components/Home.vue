<template>
    <div>
        {{ message }}
    </div>
</template>

<script setup>
import { ref } from 'vue'
const message = ref('Привет, Vue 3 + Laravel!')
</script>

<style scoped>
div {
    color: green;
    font-weight: bold;
}
</style>
