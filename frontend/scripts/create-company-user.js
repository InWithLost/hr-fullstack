// Скрипт для создания пользователя с компанией (не админа)

// Данные пользователя
const user = {
  id: 123,
  name: 'Иван Петров',
  email: 'company@example.com',
  role: 'company',
  token: 'demo-company-token'
};

// Данные компании
const company = {
  id: 456,
  name: 'ООО "Тестовая компания"',
  description: 'Тестовая компания для демонстрации функционала',
  location: 'Москва',
  industry: 'IT',
  website: 'https://example.com',
  phone: '+7 (123) 456-78-90',
  balance: 15000,
  free_job_postings: 2
};

// Сохраняем данные в localStorage
localStorage.setItem('user', JSON.stringify(user));
localStorage.setItem('company', JSON.stringify(company));
localStorage.setItem('token', user.token);

// Удаляем данные администратора, если они есть
localStorage.removeItem('admin_managing_company');

console.log('Пользователь с компанией успешно создан!');
console.log('Email: company@example.com');
console.log('Роль: company');
