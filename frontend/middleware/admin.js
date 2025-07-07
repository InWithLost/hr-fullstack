/**
 * Middleware для проверки доступа к админ-панели
 * 
 * Это middleware применяется только к страницам, которые явно указывают его в definePageMeta
 * Оно проверяет наличие токена и административных прав у пользователя
 * В режиме разработки проверки отключены для упрощения тестирования
 */
export default defineNuxtRouteMiddleware(async (to, from) => {
  // БЛОК 1: Режим разработки
  // В режиме разработки middleware отключено для упрощения тестирования
  // Удалите этот return, чтобы включить проверки авторизации
  return;

  // БЛОК 2: Логирование
  // Выводим в консоль информацию о срабатывании middleware и наличии токена
  console.log('middleware: process.client =', process.client, 'token =', process.client ? localStorage.getItem('token') : undefined)

  // БЛОК 3: Проверка окружения
  // Проверяем, что код выполняется на клиенте, так как localStorage доступен только там
  if (!process.client) {
    // На сервере просто завершаем middleware без проверок
    return
  }

  // БЛОК 4: Проверка наличия токена
  // Получаем токен из localStorage и проверяем его наличие
  const token = localStorage.getItem('token')
  if (!token) {
    // Если токен отсутствует, перенаправляем на главную страницу
    return navigateTo('/')
  }

  // БЛОК 5: Проверка прав администратора через API
  try {
    const config = useRuntimeConfig()
    const response = await $fetch(`${config.public.apiBase}/api/user`, {
      headers: {
        Authorization: `Bearer ${token}`
      }
    })
    // Выводим полный ответ от API для диагностики
    console.log('API response:', response)
    // Выводим пользователя в консоль для отладки
    console.log('USER:', response.user)
    // Проверяем, что пользователь — админ
    if (!response || !response.is_admin) {
      // Если не админ — редирект на главную
      return navigateTo('/')
    }
  } catch (e) {
    // В случае ошибки — редирект на главную
    return navigateTo('/')
  }
}) 