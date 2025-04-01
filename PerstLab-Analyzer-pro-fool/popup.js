let statsUpdateInterval;

document.addEventListener('DOMContentLoaded', function() {
  const textarea = document.getElementById('wordsList');
  const saveButton = document.getElementById('saveButton');
  const statsContent = document.getElementById('statsContent');
  const fileInput = document.getElementById('fileInput');
  const colorSelect = document.getElementById('colorSelect');
  const tabs = document.querySelectorAll('.wh-tab');
  const tabContents = document.querySelectorAll('.wh-tab-content');

  // Структура для хранения слов и их цветов
  let wordColors = {};

  // Настройка вкладок
  tabs.forEach(tab => {
    tab.addEventListener('click', () => {
      // Удаляем активный класс со всех вкладок
      tabs.forEach(t => t.classList.remove('active'));
      tabContents.forEach(t => t.classList.remove('active'));
      
      // Добавляем активный класс только нажатой вкладке
      tab.classList.add('active');
      
      // Показываем соответствующий контент
      const tabName = tab.getAttribute('data-tab');
      document.getElementById(`${tabName}-tab`).classList.add('active');
      
      // Обновляем статистику при переключении на эту вкладку
      if (tabName === 'stats') {
        updateStats();
      }
    });
  });

  // Функция обновления списка слов с цветами
  function updateWords() {
    const words = textarea.value
      .split('\n')
      .map(word => word.trim())
      .filter(word => word.length > 0);

    // Получаем текущий выбранный цвет
    const currentColor = colorSelect.value;
    
    // Ассоциируем каждый текст с выбранным цветом
    words.forEach(word => {
      // Всегда обновляем цвет при изменении выбора
      wordColors[word] = currentColor;
    });

    chrome.storage.sync.set({ 
      highlightWords: words, 
      wordColors: wordColors 
    }, function() {
      chrome.tabs.query({ active: true, currentWindow: true }, function(tabs) {
        if (tabs[0]) {
          chrome.tabs.sendMessage(tabs[0].id, {
            type: 'UPDATE_HIGHLIGHTS',
            words: words,
            colors: wordColors
          }, function(response) {
            if (chrome.runtime.lastError) {
              console.log('Ошибка отправки сообщения:', chrome.runtime.lastError);
            } else {
              updateStats();
            }
          });
        }
      });
    });
  }

  // Функция обработки загруженного файла
  fileInput.addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file && file.type === 'text/plain') {
      const reader = new FileReader();
      reader.onload = function(e) {
        const text = e.target.result;
        textarea.value = text.trim();
        updateWords();
      };
      reader.readAsText(file);
    } else {
      alert('Пожалуйста, выберите текстовый файл (.txt)');
    }
  });

  // Функция обновления статистики
  function updateStats() {
    chrome.tabs.query({ active: true, currentWindow: true }, function(tabs) {
      if (tabs[0]) {
        chrome.tabs.sendMessage(tabs[0].id, {
          type: 'REQUEST_STATS'
        }, function(response) {
          if (chrome.runtime.lastError) {
            console.log('Ошибка отправки сообщения:', chrome.runtime.lastError);
          } else if (response && response.stats) {
            updateStatsDisplay(response.stats, response.locations);
          }
        });
      }
    });
  }

  // Улучшенная функция отображения статистики с локациями
  function updateStatsDisplay(stats, locations = {}) {
    statsContent.innerHTML = '';
    
    const sortedStats = Object.entries(stats)
      .sort(([, a], [, b]) => b - a);
    
    if (sortedStats.length === 0) {
      statsContent.innerHTML = '<div class="stat-item">Нет найденных слов</div>';
      return;
    }

    // Функция для создания иконки
    function getIconForLocation(location) {
      let icon = '';
      
      if (location.includes('шапке')) {
        icon = '↑ ';
      } else if (location.includes('подвале')) {
        icon = '↓ ';
      } else if (location.includes('теле документа')) {
        icon = '◈ ';
      } else if (location.includes('теге <a>')) {
        icon = '🔗 ';
      } else if (location.includes('списке UL')) {
        icon = '• ';
      } else if (location.includes('таблице')) {
        icon = '▦ ';
      }
      
      return icon;
    }

    for (const [word, count] of sortedStats) {
      const containerDiv = document.createElement('div');
      containerDiv.className = 'stat-item-container';
      
      // Главная информация о слове
      const mainDiv = document.createElement('div');
      mainDiv.className = 'stat-item';
      mainDiv.innerHTML = `
        <span class="stat-word" title="${word}">${word}</span>
        <span class="stat-count">${count}</span>
      `;
      containerDiv.appendChild(mainDiv);
      
      // Блок с местоположениями
      const locationsDiv = document.createElement('div');
      locationsDiv.className = 'stat-locations';
      
      const wordLocations = locations[word] || {};
      const hasLocations = Object.keys(wordLocations).length > 0;
      
      if (hasLocations) {
        // Сортируем местоположения по количеству (по убыванию)
        const sortedLocations = Object.entries(wordLocations)
          .sort(([, a], [, b]) => b - a);
        
        for (const [location, locCount] of sortedLocations) {
          const locationItem = document.createElement('div');
          locationItem.className = 'location-badge';
          
          const icon = getIconForLocation(location);
          
          // Определяем соответствующий класс для бейджа
          let badgeClass = '';
          if (location.includes('шапке')) {
            badgeClass = 'header-loc';
          } else if (location.includes('подвале')) {
            badgeClass = 'footer-loc';
          } else if (location.includes('теге <a>')) {
            badgeClass = 'link-loc';
          } else if (location.includes('списке UL')) {
            badgeClass = 'list-loc';
          } else if (location.includes('таблице')) {
            badgeClass = 'table-loc';
          } else {
            badgeClass = 'content-loc';
          }
          
          locationItem.classList.add(badgeClass);
          locationItem.innerHTML = `${icon}${location}: <b>${locCount}</b>`;
          locationsDiv.appendChild(locationItem);
        }
      } else {
        locationsDiv.innerHTML = '<div class="location-badge unknown-loc">Местоположение не определено</div>';
      }
      
      containerDiv.appendChild(locationsDiv);
      statsContent.appendChild(containerDiv);
    }
  }

  // Функция запуска периодического обновления статистики
  function startStatsUpdate() {
    updateStats();
    statsUpdateInterval = setInterval(updateStats, 1000);
  }

  // Обработчик изменения текста
  textarea.addEventListener('input', function() {
    updateWords();
  });

  // Обработчик кнопки сохранения
  saveButton.addEventListener('click', updateWords);

  // Обработчик изменения цвета
  colorSelect.addEventListener('change', function() {
    updateWords(); // Обновляем слова с новым цветом
  });

  // Загрузка сохраненных слов и цветов при открытии popup
  chrome.storage.sync.get(['highlightWords', 'wordColors'], function(result) {
    if (result.highlightWords) {
      textarea.value = result.highlightWords.join('\n');
      wordColors = result.wordColors || {};
      updateWords();
    }
  });

  // Запуск обновления статистики
  startStatsUpdate();

  // Остановка обновления при закрытии popup
  window.addEventListener('unload', function() {
    if (statsUpdateInterval) {
      clearInterval(statsUpdateInterval);
    }
  });

  // Добавляем обработчик ошибок
  window.addEventListener('error', function(event) {
    console.error('Ошибка в popup:', event.error);
  });
});

// Глобальная функция обновления статистики (для использования в слушателе сообщений)
function updateStatsDisplay(stats, locations = {}) {
  const statsContent = document.getElementById('statsContent');
  if (!statsContent) return;
  
  statsContent.innerHTML = '';
  
  const sortedStats = Object.entries(stats)
    .sort(([, a], [, b]) => b - a);
  
  if (sortedStats.length === 0) {
    statsContent.innerHTML = '<div class="stat-item">Нет найденных слов</div>';
    return;
  }

  // Функция для создания иконки
  function getIconForLocation(location) {
    let icon = '';
    
    if (location.includes('шапке')) {
      icon = '↑ ';
    } else if (location.includes('подвале')) {
      icon = '↓ ';
    } else if (location.includes('теле документа')) {
      icon = '◈ ';
    } else if (location.includes('теге <a>')) {
      icon = '🔗 ';
    } else if (location.includes('списке UL')) {
      icon = '• ';
    } else if (location.includes('таблице')) {
      icon = '▦ ';
    }
    
    return icon;
  }

  for (const [word, count] of sortedStats) {
    const containerDiv = document.createElement('div');
    containerDiv.className = 'stat-item-container';
    
    // Главная информация о слове
    const mainDiv = document.createElement('div');
    mainDiv.className = 'stat-item';
    mainDiv.innerHTML = `
      <span class="stat-word" title="${word}">${word}</span>
      <span class="stat-count">${count}</span>
    `;
    containerDiv.appendChild(mainDiv);
    
    // Блок с местоположениями
    const locationsDiv = document.createElement('div');
    locationsDiv.className = 'stat-locations';
    
    const wordLocations = locations[word] || {};
    const hasLocations = Object.keys(wordLocations).length > 0;
    
    if (hasLocations) {
      // Сортируем местоположения по количеству (по убыванию)
      const sortedLocations = Object.entries(wordLocations)
        .sort(([, a], [, b]) => b - a);
      
      for (const [location, locCount] of sortedLocations) {
        const locationItem = document.createElement('div');
        locationItem.className = 'location-badge';
        
        const icon = getIconForLocation(location);
        
        // Определяем соответствующий класс для бейджа
        let badgeClass = '';
        if (location.includes('шапке')) {
          badgeClass = 'header-loc';
        } else if (location.includes('подвале')) {
          badgeClass = 'footer-loc';
        } else if (location.includes('теге <a>')) {
          badgeClass = 'link-loc';
        } else if (location.includes('списке UL')) {
          badgeClass = 'list-loc';
        } else if (location.includes('таблице')) {
          badgeClass = 'table-loc';
        } else {
          badgeClass = 'content-loc';
        }
        
        locationItem.classList.add(badgeClass);
        locationItem.innerHTML = `${icon}${location}: <b>${locCount}</b>`;
        locationsDiv.appendChild(locationItem);
      }
    } else {
      locationsDiv.innerHTML = '<div class="location-badge unknown-loc">Местоположение не определено</div>';
    }
    
    containerDiv.appendChild(locationsDiv);
    statsContent.appendChild(containerDiv);
  }
}

chrome.runtime.onMessage.addListener(function(request, sender, sendResponse) {
  if (request.type === 'STATS_UPDATE') {
    const statsContent = document.getElementById('statsContent');
    if (statsContent) {
      const stats = request.stats;
      const locations = request.locations || {};
      const tabs = document.querySelectorAll('.wh-tab');
      const activeTab = Array.from(tabs).find(tab => tab.classList.contains('active'));
      
      // Обновляем отображение статистики только если открыта вкладка статистики
      if (activeTab && activeTab.getAttribute('data-tab') === 'stats') {
        updateStatsDisplay(stats, locations);
      }
    }
  }
  return true;
});