const fs = require('fs');
const path = require('path');

const filePath = path.join('c:', 'Razrabotka', 'Sait HR WindSurf', 'Front-new', 'pages', 'admin', 'view-application.vue');

// Читаем содержимое файла
fs.readFile(filePath, 'utf8', (err, data) => {
  if (err) {
    console.error('Ошибка при чтении файла:', err);
    return;
  }

  // Удаляем дублирующийся блок с сопроводительным письмом кандидата (строки 276-285)
  const lines = data.split('\n');
  const newLines = [
    ...lines.slice(0, 275),
    ...lines.slice(286)
  ];
  
  const newContent = newLines.join('\n');

  // Записываем обновленное содержимое обратно в файл
  fs.writeFile(filePath, newContent, 'utf8', (err) => {
    if (err) {
      console.error('Ошибка при записи файла:', err);
      return;
    }
    console.log('Дублирующийся блок успешно удален');
  });
});
