import ruLocale from '../locales/ru'

export default {
  // Основные настройки
  language: 'ru',
  language_url: '/tinymce/langs/ru.js',
  height: 500,
  menubar: true,
  
  // Панели инструментов
  toolbar: [
    'undo redo | formatselect | bold italic underline strikethrough | alignleft aligncenter alignright alignjustify',
    'bullist numlist | outdent indent | link image media table | removeformat code'
  ],
  
  // Плагины
  plugins: [
    'advlist', 'autolink', 'lists', 'link', 'image', 'charmap', 'preview',
    'anchor', 'searchreplace', 'visualblocks', 'code', 'fullscreen',
    'insertdatetime', 'media', 'table', 'help', 'wordcount'
  ],
  
  // Настройки контента
  content_style: 'body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif; font-size: 16px; }',
  
  // Локализация
  translations: ruLocale,
  
  // Настройки загрузки изображений
  images_upload_url: '/api/upload-image',
  images_upload_credentials: true,
  
  // Дополнительные настройки
  branding: false,
  promotion: false,
  browser_spellcheck: true,
  contextmenu: false,
  api_key: 'no-api-key'
} 