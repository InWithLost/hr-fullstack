// Валидация email
export const validateEmail = (email) => {
  const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/
  return re.test(email)
}

// Валидация пароля
export const validatePassword = (password) => {
  // Минимум 8 символов, хотя бы одна буква и одна цифра
  const re = /^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$/
  return re.test(password)
}

// Валидация формы создания пользователя
export const validateUserForm = (form) => {
  const errors = {}

  if (!form.name) {
    errors.name = 'Имя обязательно'
  } else if (form.name.length < 2) {
    errors.name = 'Имя должно содержать минимум 2 символа'
  }

  if (!form.email) {
    errors.email = 'Email обязателен'
  } else if (!validateEmail(form.email)) {
    errors.email = 'Некорректный email'
  }

  if (!form.password) {
    errors.password = 'Пароль обязателен'
  } else if (!validatePassword(form.password)) {
    errors.password = 'Пароль должен содержать минимум 8 символов, букву и цифру'
  }

  if (form.password !== form.password_confirmation) {
    errors.password_confirmation = 'Пароли не совпадают'
  }

  return errors
} 