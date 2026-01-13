<script setup>
import { ref } from 'vue'
import axios from 'axios'
import { useRouter } from 'vue-router'

const router = useRouter()

// поля формы
const email = ref('')
const password = ref('')
const error = ref('')
const loading = ref(false)

const login = async () => {
  error.value = ''
  loading.value = true

  try {
    // 1️⃣ Получаем CSRF cookie (ОБЯЗАТЕЛЬНО для Sanctum)
    await axios.get('/sanctum/csrf-cookie')

    // 2️⃣ Логинимся
    await axios.post('/api/login', {
      email: email.value,
      password: password.value,
    })

    // 3️⃣ Проверяем, что пользователь авторизован
    await axios.get('/api/user')

    // 4️⃣ Переход на защищённую страницу
    router.push('/posts')

  } catch (e) {
    error.value = 'Неверный email или пароль'
  } finally {
    loading.value = false
  }
}
</script>

<template>
  <div class="login">
    <h1>Login</h1>

    <form @submit.prevent="login">
      <div>
        <input
          type="email"
          placeholder="Email"
          v-model="email"
          required
        />
      </div>

      <div>
        <input
          type="password"
          placeholder="Password"
          v-model="password"
          required
        />
      </div>

      <button type="submit" :disabled="loading">
        {{ loading ? 'Loading...' : 'Login' }}
      </button>

      <p v-if="error" class="error">
        {{ error }}
      </p>
    </form>
  </div>
</template>

<style scoped>
.login {
  max-width: 400px;
  margin: 80px auto;
  padding: 20px;
  border: 1px solid #ddd;
}

input {
  width: 100%;
  padding: 8px;
  margin-bottom: 10px;
}

button {
  width: 100%;
  padding: 10px;
  cursor: pointer;
}

.error {
  color: red;
  margin-top: 10px;
}
</style>
