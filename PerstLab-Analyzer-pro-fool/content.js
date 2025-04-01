// Глобальные переменные
let highlightWords = [];
let wordColors = {};
let wordStats = {};
let wordLocations = {}; // Хранит информацию о местоположении слов
let highlightClassName = 'wh-highlight-word'; // Уникальный префикс класса

// Добавляем стили для подсветки (динамически)
function updateStyles() {
  // Удаляем предыдущие стили, если они существуют
  const existingStyle = document.getElementById('wh-highlight-styles');
  if (existingStyle) {
    existingStyle.remove();
  }
  
  const style = document.createElement('style');
  style.id = 'wh-highlight-styles';
  
  // Общий стиль для всех подсветок
  let styleRules = `
    [class^="highlight-"] {
      display: inline !important;
      color: black !important;
      padding: 1px 2px !important;
      margin: 0 !important;
      border-radius: 2px !important;
      box-shadow: 0 0 1px rgba(0,0,0,0.2) !important;
    }
  `;
  
  // Создаем стили для каждого слова с соответствующим цветом
  Object.entries(wordColors).forEach(([word, color]) => {
    if (word && word.trim() !== '') {
      // Создаем безопасное имя класса, заменяя проблемные символы
      const safeWord = word.replace(/[^a-zA-Z0-9]/g, function(match) {
        return '_' + match.charCodeAt(0).toString(16);
      });
      
      styleRules += `
        .highlight-${safeWord} {
          background-color: ${color} !important;
        }
      `;
    }
  });
  
  style.textContent = styleRules;
  document.head.appendChild(style);
}

// Функция для экранирования специальных символов в регулярных выражениях
function escapeRegExp(string) {
  return string.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
}

// Функция для проверки, является ли узел или его родители частью кода или предварительно отформатированного текста
function isCodeOrPreElement(node) {
  if (!node || node.nodeType !== 1) return false;
  
  const nodeName = node.nodeName.toLowerCase();
  if (nodeName === 'code' || nodeName === 'pre' || nodeName === 'script' || nodeName === 'style') {
    return true;
  }
  
  // Проверяем родительские элементы
  return node.parentNode ? isCodeOrPreElement(node.parentNode) : false;
}

// УЛУЧШЕННАЯ функция определения местоположения
function determineLocation(node) {
  // Если передан не узел или узел без родителя, считаем что это тело документа
  if (!node || !node.parentNode) return ['в теле документа'];
  
  // Получаем ближайший элемент (если передан текстовый узел)
  let element = node.nodeType === 3 ? node.parentNode : node;
  
  const locations = new Set(); // Используем Set для автоматического исключения дубликатов
  
  try {
    // Проверка принадлежности к таблице
    if (element.closest('table')) {
      locations.add('в таблице <table>');
    }
    
    // Проверка принадлежности к ссылке
    if (element.closest('a')) {
      locations.add('в теге <a>');
    }
    
    // Проверка принадлежности к списку UL
    if (element.closest('ul')) {
      locations.add('в списке UL');
    }
    
    // Получаем высоту и ширину документа
    const docHeight = Math.max(
      document.body.scrollHeight,
      document.body.offsetHeight,
      document.documentElement.clientHeight
    );
    
    // Находим все потенциальные заголовки и подвалы
    const headers = [];
    const footers = [];
    
    // Проверяем очевидные элементы шапки и подвала по тегам и классам
    const possibleHeaders = document.querySelectorAll('header, [class*="header"], [class*="head"], [id*="header"], [id*="head"]');
    const possibleFooters = document.querySelectorAll('footer, [class*="footer"], [class*="foot"], [id*="footer"], [id*="foot"]');
    
    possibleHeaders.forEach(header => headers.push(header));
    possibleFooters.forEach(footer => footers.push(footer));
    
    // Если шапка и подвал не найдены по тегам, ищем по позиции
    if (headers.length === 0) {
      // Предполагаем, что шапка находится в верхней части страницы
      const topElements = Array.from(document.querySelectorAll('div, nav, section'))
        .filter(el => {
          try {
            const rect = el.getBoundingClientRect();
            // Элемент должен быть широким и в верхней части страницы
            return rect.top < 150 && rect.width > window.innerWidth * 0.5;
          } catch (e) {
            return false;
          }
        });
      
      if (topElements.length > 0) {
        headers.push(...topElements);
      }
    }
    
    if (footers.length === 0) {
      // Предполагаем, что подвал находится в нижней части страницы
      const bottomElements = Array.from(document.querySelectorAll('div, section'))
        .filter(el => {
          try {
            const rect = el.getBoundingClientRect();
            const offsetTop = window.pageYOffset + rect.top;
            // Элемент должен быть широким и в нижней части страницы
            return (docHeight - offsetTop - rect.height < 150) && rect.width > window.innerWidth * 0.5;
          } catch (e) {
            return false;
          }
        });
      
      if (bottomElements.length > 0) {
        footers.push(...bottomElements);
      }
    }
    
    // Проверяем, находится ли элемент в шапке
    let isInHeader = false;
    for (const header of headers) {
      if (header.contains(element)) {
        locations.add('в шапке');
        isInHeader = true;
        break;
      }
    }
    
    // Проверяем, находится ли элемент в подвале
    let isInFooter = false;
    for (const footer of footers) {
      if (footer.contains(element)) {
        locations.add('в подвале');
        isInFooter = true;
        break;
      }
    }
    
    // Проверяем, находится ли элемент в основном содержимом
    let inContent = false;
    if (!isInHeader && !isInFooter) {
      const contentElements = document.querySelectorAll('main, article, [class*="content"], [class*="main"], [id*="content"], [id*="main"]');
      
      for (const contentEl of contentElements) {
        if (contentEl.contains(element)) {
          locations.add('в теле документа');
          inContent = true;
          break;
        }
      }
      
      // Если элемент не найден ни в шапке, ни в подвале, ни в основном содержимом,
      // то считаем, что он находится в теле документа
      if (!inContent) {
        locations.add('в теле документа');
      }
    }
  } catch (error) {
    console.error('Ошибка при определении местоположения:', error);
    locations.add('в теле документа'); // В случае ошибки считаем, что это тело документа
  }
  
  // Если ничего не определено, считаем что это тело документа
  if (locations.size === 0) {
    locations.add('в теле документа');
  }
  
  return Array.from(locations);
}

// Основная функция подсветки
function highlightText() {
  // Сброс статистики и местоположений
  wordStats = {};
  wordLocations = {};
  highlightWords.forEach(word => {
    wordStats[word] = 0;
    wordLocations[word] = {};
  });

  if (highlightWords.length === 0) {
    removeAllHighlights();
    return;
  }
  
  // Пытаемся заранее определить основные зоны страницы
  console.log("Анализ структуры страницы для определения зон...");

  // Сортируем слова по длине (сначала более длинные фразы)
  const sortedWords = [...highlightWords].sort((a, b) => b.length - a.length);
  
  // Функция обхода DOM с улучшенным алгоритмом подсветки
  function processNode(node) {
    // Пропускаем обработку элементов кода и предварительно отформатированного текста
    if (node.nodeType === 1 && isCodeOrPreElement(node)) {
      return;
    }
    
    if (node.nodeType === 3) { // Текстовый узел
      const text = node.textContent;
      if (!text || text.trim() === '') return;
      
      // Проверяем, есть ли совпадения с какими-либо словами
      let hasMatch = false;
      for (const word of sortedWords) {
        if (word && text.toLowerCase().includes(word.toLowerCase())) {
          hasMatch = true;
          break;
        }
      }
      
      if (!hasMatch) return;
      
      let processedHTML = text;
      
      // Обрабатываем каждое слово по отдельности, начиная с самых длинных
      for (const word of sortedWords) {
        if (!word || word.trim() === '') continue; // Пропускаем пустые слова
        
        // Создаем безопасное имя класса
        const safeWord = word.replace(/[^a-zA-Z0-9]/g, function(match) {
          return '_' + match.charCodeAt(0).toString(16);
        });
        
        const safeClassName = `highlight-${safeWord}`;
        const color = wordColors[word] || 'yellow';
        
        // Создаём регулярное выражение для текущего слова
        try {
          const regex = new RegExp(escapeRegExp(word), 'gi');
          
          // Подсчитываем количество совпадений для статистики
          const matches = text.match(regex);
          if (matches) {
            wordStats[word] = (wordStats[word] || 0) + matches.length;
            
            // Определяем местоположение слова
            const locations = determineLocation(node);
            
            // Сохраняем информацию о местоположении
            locations.forEach(location => {
              if (!wordLocations[word]) {
                wordLocations[word] = {};
              }
              if (!wordLocations[word][location]) {
                wordLocations[word][location] = 0;
              }
              wordLocations[word][location] += matches.length;
            });
            
            // Заменяем текст спаном с подсветкой
            processedHTML = processedHTML.replace(regex, (match) => {
              return `<span class="${safeClassName}" data-wh-word="${word}" style="background-color: ${color}; color: black;">${match}</span>`;
            });
          }
        } catch (error) {
          console.error('Ошибка при обработке слова:', word, error);
        }
      }
      
      // Если текст был изменен, заменяем узел
      if (processedHTML !== text) {
        try {
          const tempDiv = document.createElement('div');
          tempDiv.innerHTML = processedHTML;
          
          // Заменяем текстовый узел набором узлов из tempDiv
          const fragment = document.createDocumentFragment();
          while (tempDiv.firstChild) {
            fragment.appendChild(tempDiv.firstChild);
          }
          
          // Обработка ошибок при замене узла
          if (node.parentNode) {
            node.parentNode.replaceChild(fragment, node);
          }
        } catch (error) {
          console.error('Ошибка при замене узла:', error);
        }
      }
    } else if (node.nodeType === 1 && // Элемент
        !['SCRIPT', 'STYLE', 'TEXTAREA', 'INPUT', 'IFRAME', 'SVG', 'CANVAS', 'CODE', 'PRE'].includes(node.tagName) &&
        !node.classList.contains(highlightClassName) &&
        !node.querySelector('[class^="highlight-"]')) {
      
      // Обрабатываем дочерние узлы только если элемент ещё не содержит подсветку
      try {
        Array.from(node.childNodes).forEach(childNode => {
          processNode(childNode);
        });
      } catch (error) {
        console.error('Ошибка при обработке дочерних узлов:', error);
      }
    }
  }

  try {
    removeAllHighlights();
    processNode(document.body);
    console.log("Статистика по словам:", wordStats);
    console.log("Местоположения слов:", wordLocations);
    sendStats();
    updateStyles(); // Обновляем стили для новых цветов
  } catch (error) {
    console.error('Ошибка при подсветке:', error);
  }
}

// Удаление подсветок
function removeAllHighlights() {
  try {
    document.querySelectorAll('[class^="highlight-"]').forEach(el => {
      if (el.parentNode) {
        const text = document.createTextNode(el.textContent);
        el.parentNode.replaceChild(text, el);
      }
    });
    
    // Очистка объединенных текстовых узлов
    document.body.normalize();
  } catch (error) {
    console.error('Ошибка при удалении подсветок:', error);
  }
}

// Отправка статистики и информации о местоположении
function sendStats() {
  chrome.runtime.sendMessage({
    type: 'STATS_UPDATE',
    stats: wordStats,
    locations: wordLocations
  });
}

// Счетчик обработки для ограничения частоты обновлений
let processingCounter = 0;
const MAX_PROCESSING_PER_SECOND = 3;
let lastProcessingTime = 0;
let observerTimeout = null;

// Функция для защиты от слишком частых обновлений
function throttledHighlight() {
  const now = Date.now();
  
  // Если прошло менее секунды с последнего обновления
  if (now - lastProcessingTime < 1000) {
    processingCounter++;
    
    // Если превышено максимальное количество обновлений в секунду
    if (processingCounter > MAX_PROCESSING_PER_SECOND) {
      console.log('Слишком много обновлений, отложено');
      clearTimeout(observerTimeout);
      
      // Откладываем обновление на секунду
      observerTimeout = setTimeout(() => {
        processingCounter = 0;
        lastProcessingTime = Date.now();
        highlightText();
      }, 1000);
      return;
    }
  } else {
    // Сбрасываем счетчик, если прошла секунда или больше
    processingCounter = 0;
  }
  
  // Обновляем время последнего обновления и выполняем подсветку
  lastProcessingTime = now;
  highlightText();
}

// Наблюдатель за изменениями DOM с дросселингом для производительности
const observer = new MutationObserver((mutations) => {
  let shouldUpdate = false;
  mutations.forEach(mutation => {
    if (!mutation.target.closest('[class^="highlight-"]')) {
      shouldUpdate = true;
    }
  });
  
  if (shouldUpdate) {
    clearTimeout(observerTimeout);
    observerTimeout = setTimeout(() => {
      throttledHighlight();
    }, 300); // Задержка для предотвращения слишком частых обновлений
  }
});

// Запускаем наблюдатель с оптимизированными параметрами
observer.observe(document.body, {
  childList: true,
  subtree: true,
  characterData: true,
  attributes: false // Не отслеживаем изменения атрибутов для оптимизации
});

// Обработчик сообщений с немедленным запуском поиска
chrome.runtime.onMessage.addListener((request, sender, sendResponse) => {
  if (request.type === 'UPDATE_HIGHLIGHTS') {
    console.log('Получены данные для подсветки:', request.words, request.colors);
    highlightWords = request.words;
    wordColors = request.colors || {};
    
    // Важно! Чистим подсветку перед обновлением
    removeAllHighlights();
    
    setTimeout(() => {
      try {
        highlightText();
        sendResponse({ success: true, stats: wordStats, locations: wordLocations });
      } catch (error) {
        console.error('Ошибка при подсветке:', error);
        sendResponse({ success: false, error: error.message });
      }
    }, 50);
  } else if (request.type === 'REQUEST_STATS') {
    sendResponse({ stats: wordStats, locations: wordLocations });
  }
  return true;
});

// Начальная загрузка сохраненных слов и цветов
chrome.storage.sync.get(['highlightWords', 'wordColors'], function(result) {
  if (result.highlightWords) {
    highlightWords = result.highlightWords;
    wordColors = result.wordColors || {};
    setTimeout(() => {
      highlightText();
    }, 100);
  }
});

// Запускаем поиск при загрузке страницы
document.addEventListener('DOMContentLoaded', () => {
  highlightText();
});