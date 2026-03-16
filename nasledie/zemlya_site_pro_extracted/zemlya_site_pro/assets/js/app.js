
const SiteState = {
  filters: {
    region: 'all',
    scenario: 'all',
    sea: false,
    installment: false,
    status: 'free',
    sort: 'recommended'
  },
  compare: []
};

function safeQS(sel, root=document){ return root.querySelector(sel); }
function safeQSA(sel, root=document){ return Array.from(root.querySelectorAll(sel)); }

const COMPANY_CONTACTS = { phoneDigits: '79852198302', telegram: 'https://t.me/+dxP0tIDCMKhkNTYy', whatsapp: 'https://wa.me/79852198302' };
function formatNum(n){ return Number(n || 0).toLocaleString('ru-RU'); }
function waLink(message){ return `https://wa.me/${COMPANY_CONTACTS.phoneDigits}?text=${encodeURIComponent(message)}`; }

function getProjectById(id){ return PROJECTS.find(p => p.id === id); }
function getPlotById(id){ return PLOTS.find(p => p.id === id); }

function getPlotProject(plot){
  if (!plot.project) return null;
  return PROJECTS.find(p => p.id === plot.project) || null;
}
function plotUrl(plot){
  const map = {
    'pb-12': 'plot-pobednyj-bereg-12.html',
    'vl-09': 'plot-velikie-luga-09.html',
    'dz-08': 'plot-dzharylgach-08.html',
    'kd-24': 'plot-korolevskie-dachi-24.html'
  };
  if (map[plot.id]) return map[plot.id];
  const byProject = {
    'pobednyj-bereg': 'plot-pobednyj-bereg-12.html',
    'velikie-luga': 'plot-velikie-luga-09.html',
    'dzharylgach': 'plot-dzharylgach-08.html',
    'korolevskie-dachi': 'plot-korolevskie-dachi-24.html'
  };
  return byProject[plot.project] || 'plot-pobednyj-bereg-12.html';
}
function projectUrl(projectId){
  const map = {
    'pobednyj-bereg': 'project-pobednyj-bereg.html',
    'velikie-luga': 'project-velikie-luga.html',
    'dzharylgach': 'project-dzharylgach.html',
    'korolevskie-dachi': 'project-korolevskie-dachi.html'
  };
  return map[projectId] || 'project-pobednyj-bereg.html';
}
function formatDistance(plot){
  return plot.seaDist ? `${plot.seaDist} м до моря` : getPlotRegion(plot);
}
function compareValue(plot, key){
  switch(key){
    case 'price': return formatPrice(plot.price, plot.priceLabel);
    case 'area': return `${plot.area} соток`;
    case 'status': return STATUS_LABELS[plot.status]?.name || plot.status;
    case 'category': return plot.category;
    case 'vri': return plot.vri;
    case 'seaDist': return plot.seaDist ? `${plot.seaDist} м` : '—';
    case 'city': return getPlotCity(plot);
    case 'view': return plot.view || '—';
    case 'electric': return plot.comm?.electric || '—';
    case 'gas': return plot.comm?.gas || '—';
    case 'water': return plot.comm?.water || '—';
    case 'road': return plot.comm?.road || '—';
    case 'installment': return plot.installment?.available ? `Да, ${plot.installment.months} мес.` : 'Нет';
    case 'roi': return plot.roiEstimate ? `до ${plot.roiEstimate}%` : '—';
    default: return '—';
  }
}

/* ==============================
   TOAST NOTIFICATIONS (auto-dismiss 5s)
   ============================== */
function showToast(text){
  const el = safeQS('#siteToast');
  if(!el) return;
  el.textContent = text;
  el.classList.add('is-show');
  window.clearTimeout(showToast._t);
  showToast._t = window.setTimeout(() => el.classList.remove('is-show'), 5000);
}

/* ==============================
   COMPARE (localStorage)
   ============================== */
function loadCompare(){
  try{
    SiteState.compare = JSON.parse(localStorage.getItem('zemlyaCompare') || '[]');
  }catch(e){
    SiteState.compare = [];
  }
}
function saveCompare(){
  localStorage.setItem('zemlyaCompare', JSON.stringify(SiteState.compare));
  updateCompareCount();
}
function updateCompareCount(){
  safeQSA('[data-compare-count]').forEach(el => {
    el.textContent = SiteState.compare.length;
  });
}
function toggleCompare(plotId, btn){
  const current = SiteState.compare.slice();
  const exists = current.includes(plotId);
  if (exists){
    SiteState.compare = current.filter(id => id !== plotId);
    showToast('Участок удален из сравнения');
  } else {
    if (current.length >= 3){
      showToast('В сравнении может быть до 3 участков');
      return;
    }
    SiteState.compare = [...current, plotId];
    showToast('Участок добавлен в сравнение');
  }
  saveCompare();
  if (btn) btn.classList.toggle('is-active', SiteState.compare.includes(plotId));
  safeQSA(`[data-compare-id="${plotId}"]`).forEach(el => {
    el.classList.toggle('is-active', SiteState.compare.includes(plotId));
  });
  if (document.body.dataset.page === 'compare') renderComparePage();
}
window.toggleCompare = toggleCompare;

function clearAllCompare(){
  SiteState.compare = [];
  saveCompare();
  safeQSA('[data-compare-id]').forEach(el => el.classList.remove('is-active'));
  if (document.body.dataset.page === 'compare') renderComparePage();
  showToast('Сравнение очищено');
}
window.clearAllCompare = clearAllCompare;

/* ==============================
   PLOT CARD
   ============================== */
function makePlotCard(plot){
  const project = getPlotProject(plot);
  const title = getPlotName(plot);
  const region = getPlotRegion(plot);
  const city = getPlotCity(plot);
  const scenario = plot.scenarios?.[0] ? SCENARIO_LABELS[plot.scenarios[0]] : null;
  const compareActive = SiteState.compare.includes(plot.id) ? 'is-active' : '';
  const pageLink = plotUrl(plot);
  const media = plot.seaDist && plot.seaDist <= 900 ? 'assets/img/plot-sea-view.svg' : (region === 'Подмосковье' ? 'assets/img/region-mo.svg' : 'assets/img/region-yubk.svg');
  return `
  <article class="plot-card">
    <div class="plot-card__media">
      <img src="${media}" alt="${title}" loading="lazy" width="400" height="250">
      <div class="plot-card__badges">
        <span class="mini-badge">${STATUS_LABELS[plot.status]?.name || 'Свободен'}</span>
        ${scenario ? `<span class="mini-badge">${scenario.emoji} ${scenario.name}</span>` : ''}
      </div>
    </div>
    <div class="plot-card__body">
      <div>
        <div class="plot-card__sub"><span>${region}</span><span>•</span><span>${city}</span></div>
        <h3 class="plot-card__title">${title}</h3>
      </div>
      <div class="plot-card__stats">
        <div class="plot-card__stat"><strong>${plot.area} соток</strong><span>площадь</span></div>
        <div class="plot-card__stat"><strong>${plot.seaDist ? plot.seaDist + ' м' : 'ИЖС'}</strong><span>${plot.seaDist ? 'до воды' : 'формат'}</span></div>
        <div class="plot-card__stat"><strong>${plot.installment?.available ? plot.installment.months + ' мес.' : 'без'}</strong><span>рассрочка</span></div>
      </div>
      <div class="chips">
        <span class="chip">${plot.category}</span>
        <span class="chip">${plot.comm?.electric || 'электричество'}</span>
        <span class="chip">${plot.view ? 'видовой' : 'ровный'}</span>
      </div>
      <div class="plot-card__foot">
        <div class="price-big">${formatPrice(plot.price, plot.priceLabel)}<small>${plot.pricePerSotka ? formatPriceShort(plot.pricePerSotka) + ' / сотка' : 'цены и пакет по запросу'}</small></div>
        <button class="icon-btn ${compareActive}" data-compare-id="${plot.id}" aria-label="Сравнить" onclick="toggleCompare('${plot.id}', this)">⚖</button>
      </div>
      <div class="plot-card__actions">
        <a class="btn btn--cta" href="${pageLink}">Подробнее</a>
        <a class="btn btn--whatsapp" href="${waLink(`Здравствуйте! Интересует ${title}. Пришлите, пожалуйста, подробности.`)}" target="_blank" rel="noopener">WhatsApp</a>
      </div>
    </div>
  </article>`;
}

function makeProjectCard(project){
  return `
  <article class="media-card card">
    <div class="media-card__image"><img src="assets/img/project-masterplan.svg" alt="${project.name}" loading="lazy" width="600" height="375"></div>
    <div class="media-card__body">
      <div class="media-card__meta"><span>${project.regionName}</span><span>•</span><span>${project.city}</span></div>
      <h3>${project.name}</h3>
      <p>${project.description}</p>
      <div class="chips" style="margin-top:16px">
        <span class="chip">${project.plotsFree} свободных</span>
        <span class="chip">${project.areaMin}–${project.areaMax} соток</span>
        <span class="chip">${project.priceLabel || 'по запросу'}</span>
      </div>
      <div class="plot-card__actions" style="margin-top:18px">
        <a class="btn btn--outline" href="${projectUrl(project.id)}">Открыть проект</a>
        <a class="btn btn--whatsapp" href="${waLink(`Здравствуйте! Интересует проект ${project.name}. Пришлите, пожалуйста, цены и свободные лоты.`)}" target="_blank" rel="noopener">Запросить цены</a>
      </div>
    </div>
  </article>`;
}

/* ==============================
   CATALOG FILTERS (with debounce)
   ============================== */
function getFilteredPlots(){
  let items = PLOTS.filter(plot => SiteState.filters.status === 'all' ? true : plot.status === SiteState.filters.status);
  if (SiteState.filters.region !== 'all'){
    items = items.filter(plot => {
      const project = getPlotProject(plot);
      return (plot.region || project?.region) === SiteState.filters.region;
    });
  }
  if (SiteState.filters.scenario !== 'all'){
    items = items.filter(plot => plot.scenarios?.includes(SiteState.filters.scenario));
  }
  if (SiteState.filters.sea){
    items = items.filter(plot => plot.seaDist && plot.seaDist <= 1200);
  }
  if (SiteState.filters.installment){
    items = items.filter(plot => plot.installment?.available);
  }
  switch(SiteState.filters.sort){
    case 'price-asc': items.sort((a,b)=>a.price-b.price); break;
    case 'price-desc': items.sort((a,b)=>b.price-a.price); break;
    case 'area-desc': items.sort((a,b)=>b.area-a.area); break;
    case 'roi-desc': items.sort((a,b)=>(b.roiEstimate||0)-(a.roiEstimate||0)); break;
    default:
      items.sort((a,b)=>{
        const aScore = (a.status === 'free' ? 30 : 0) + (a.roiEstimate || 0) + (a.installment?.available ? 5 : 0);
        const bScore = (b.status === 'free' ? 30 : 0) + (b.roiEstimate || 0) + (b.installment?.available ? 5 : 0);
        return bScore - aScore;
      });
  }
  return items;
}

function renderIndexOffers(){
  const mount = safeQS('#bestOffers');
  if (!mount) return;
  const items = getFilteredPlots().slice(0, 6);
  mount.innerHTML = items.map(makePlotCard).join('');
}

let _catalogDebounce = null;
function renderCatalog(){
  const mount = safeQS('#catalogGrid');
  if (!mount) return;
  const items = getFilteredPlots();
  if (items.length === 0){
    mount.innerHTML = `<div class="compare-empty" style="grid-column:1 / -1">
        <h3 style="margin-top:0">По вашим критериям участков не найдено</h3>
        <p class="muted">Попробуйте изменить фильтры или оставьте заявку — подберем из закрытой базы.</p>
        <div style="margin-top:18px;display:flex;gap:10px;justify-content:center;flex-wrap:wrap">
          <button class="btn btn--outline" onclick="document.getElementById('catalogReset')?.click()">Сбросить фильтры</button>
          <a class="btn btn--whatsapp" href="${waLink('Здравствуйте! Не нашел подходящий участок на сайте. Подберите, пожалуйста, варианты.')}" target="_blank" rel="noopener">Оставить заявку</a>
        </div>
      </div>`;
  } else {
    mount.innerHTML = items.map(makePlotCard).join('');
  }
  const meta = safeQS('#catalogMeta');
  if (meta){
    const totalFree = items.filter(i => i.status === 'free').length;
    meta.textContent = `Показано ${items.length} предложений, из них свободно ${totalFree}`;
  }
  safeQSA('[data-region-filter]').forEach(btn => {
    btn.classList.toggle('is-active', btn.dataset.regionFilter === SiteState.filters.region);
  });
  safeQSA('[data-scenario-filter]').forEach(btn => {
    btn.classList.toggle('is-active', btn.dataset.scenarioFilter === SiteState.filters.scenario);
  });
  const seaBtn = safeQS('[data-toggle-sea]');
  if (seaBtn) seaBtn.classList.toggle('is-active', SiteState.filters.sea);
  const instBtn = safeQS('[data-toggle-installment]');
  if (instBtn) instBtn.classList.toggle('is-active', SiteState.filters.installment);
  const sort = safeQS('#catalogSort');
  if (sort) sort.value = SiteState.filters.sort;
}

function renderCatalogDebounced(){
  clearTimeout(_catalogDebounce);
  _catalogDebounce = setTimeout(renderCatalog, 200);
}

function renderGeoPlots(){
  const mount = safeQS('#geoPlots');
  if (!mount) return;
  const items = PLOTS.filter(plot => (plot.region || getPlotProject(plot)?.region) === 'yubk' && plot.status === 'free').slice(0, 6);
  mount.innerHTML = items.map(makePlotCard).join('');
}

function renderIntentOffers(){
  const mount = safeQS('#intentOffers');
  if (!mount) return;
  const items = PLOTS.filter(plot => plot.status === 'free' && plot.scenarios?.includes('invest'))
    .sort((a,b)=>(b.roiEstimate||0) - (a.roiEstimate||0)).slice(0, 4);
  mount.innerHTML = items.map(makePlotCard).join('');
}

function renderProjectShowcase(){
  const mount = safeQS('#projectShowcase');
  if (!mount) return;
  mount.innerHTML = PROJECTS.slice(0, 3).map(makeProjectCard).join('');
}

function renderRelatedPlots(){
  const mount = safeQS('#relatedPlots');
  if (!mount) return;
  const plotId = document.body.dataset.plot || 'pb-12';
  const current = getPlotById(plotId) || getPlotById('pb-12');
  const region = current ? (current.region || getPlotProject(current)?.region) : 'yubk';
  const items = PLOTS.filter(plot => plot.id !== plotId && plot.status === 'free' && ((plot.region || getPlotProject(plot)?.region) === region)).slice(0, 3);
  mount.innerHTML = items.map(makePlotCard).join('');
}

/* ==============================
   COMPARE PAGE
   ============================== */
function renderComparePage(){
  const mount = safeQS('#compareTable');
  if (!mount) return;
  const items = SiteState.compare.map(getPlotById).filter(Boolean);
  if (!items.length){
    mount.innerHTML = `
      <div class="compare-empty">
        <h3 style="margin-top:0">Добавьте участки для сравнения из каталога</h3>
        <p class="muted">Добавьте до 3 участков из каталога через кнопку ⚖ на карточке. Мы покажем цену, площадь, коммуникации, рассрочку и инвестиционный потенциал.</p>
        <div style="margin-top:18px"><a class="btn btn--cta" href="catalog.html">Перейти в каталог</a></div>
      </div>`;
    return;
  }
  const rows = [
    ['Цена','price'],
    ['Площадь','area'],
    ['Статус','status'],
    ['Категория','category'],
    ['ВРИ','vri'],
    ['Регион / город','city'],
    ['Расстояние до моря','seaDist'],
    ['Вид','view'],
    ['Электричество','electric'],
    ['Газ','gas'],
    ['Вода','water'],
    ['Дорога','road'],
    ['Рассрочка','installment'],
    ['Потенциал ROI','roi']
  ];
  mount.innerHTML = `
    <div style="display:flex;justify-content:flex-end;margin-bottom:14px">
      <button class="btn btn--ghost btn--sm" onclick="clearAllCompare()">Очистить сравнение</button>
    </div>
    <div class="compare-table">
      <table>
        <thead>
          <tr>
            <th>Параметр</th>
            ${items.map(plot => `<th>
              <div style="display:grid;gap:8px">
                <strong style="font-family:var(--font-head);color:var(--primary);font-size:1.05rem">${getPlotName(plot)}</strong>
                <span class="muted">${getPlotRegion(plot)} · ${getPlotCity(plot)}</span>
                <div style="display:flex;gap:10px;align-items:center;justify-content:space-between">
                  <span style="font-weight:800;color:var(--primary)">${formatPrice(plot.price)}</span>
                  <button class="icon-btn" onclick="toggleCompare('${plot.id}')">✕</button>
                </div>
              </div>
            </th>`).join('')}
          </tr>
        </thead>
        <tbody>
          ${rows.map(([label,key]) => `<tr>
            <td><strong>${label}</strong></td>
            ${items.map(plot => `<td>${compareValue(plot, key)}</td>`).join('')}
          </tr>`).join('')}
        </tbody>
      </table>
    </div>
    <div style="text-align:center;margin-top:24px">
      <p class="muted">Понравился один из вариантов?</p>
      <a class="btn btn--cta" href="${waLink('Здравствуйте! Сравнил участки на сайте. Хочу узнать больше.')}" target="_blank" rel="noopener">Обсудить с менеджером</a>
    </div>`;
}

/* ==============================
   GENPLAN
   ============================== */
function renderGenplan(){
  const grid = safeQS('#genplanGrid');
  if (!grid) return;
  const projectId = document.body.dataset.project || 'pobednyj-bereg';
  const project = PROJECTS.find(p => p.id === projectId) || PROJECTS[0];
  const realPlots = PLOTS.filter(p => p.project === project.id);
  let html = '';
  for(let i = 1; i <= project.plotsTotal; i++){
    const plot = realPlots.find(p => p.num === i);
    const status = plot?.status || (i % 4 === 0 ? 'reserved' : i % 7 === 0 ? 'sold' : 'free');
    const area = plot?.area || (6 + (i % 10));
    const price = plot?.price || (project.priceMin + i * 85000);
    const content = plot
      ? `onclick="window.location.href='${plotUrl(plot)}'"`
      : '';
    html += `<div class="gp-cell ${status}" ${content}>
      ${i}
      <div class="gp-popup">
        <strong>Участок №${i}</strong>
        <div>${STATUS_LABELS[status]?.name || status}</div>
        <div style="margin-top:4px;color:var(--muted)">${area} соток · ${formatPrice(price)}</div>
      </div>
    </div>`;
  }
  grid.innerHTML = html;
}

/* ==============================
   FAQ ACCORDION
   ============================== */
function initFAQ(){
  safeQSA('.faq-item').forEach(item => {
    const btn = item.querySelector('.faq-q');
    if(!btn) return;
    btn.addEventListener('click', () => item.classList.toggle('is-open'));
  });
}

/* ==============================
   BURGER MENU
   ============================== */
function initBurger(){
  const burger = safeQS('#burger');
  const nav = safeQS('#topNav');
  if (!burger || !nav) return;
  burger.addEventListener('click', () => {
    nav.classList.toggle('is-open');
    burger.setAttribute('aria-expanded', nav.classList.contains('is-open'));
  });
  // Close on click outside
  document.addEventListener('click', (e) => {
    if (nav.classList.contains('is-open') && !nav.contains(e.target) && !burger.contains(e.target)){
      nav.classList.remove('is-open');
      burger.setAttribute('aria-expanded', 'false');
    }
  });
  // Close on nav link click
  safeQSA('a', nav).forEach(link => {
    link.addEventListener('click', () => {
      nav.classList.remove('is-open');
      burger.setAttribute('aria-expanded', 'false');
    });
  });
}

/* ==============================
   PHONE FORMATTING & VALIDATION
   ============================== */
function formatPhoneInput(value){
  let digits = value.replace(/\D/g, '');
  if (digits.startsWith('8') && digits.length > 1) digits = '7' + digits.slice(1);
  if (!digits.startsWith('7') && digits.length > 0) digits = '7' + digits;
  if (digits.length === 0) return '';
  let result = '+7';
  if (digits.length > 1) result += ' (' + digits.slice(1, 4);
  if (digits.length >= 4) result += ')';
  if (digits.length > 4) result += ' ' + digits.slice(4, 7);
  if (digits.length > 7) result += '-' + digits.slice(7, 9);
  if (digits.length > 9) result += '-' + digits.slice(9, 11);
  return result;
}

function isValidPhone(value){
  const digits = value.replace(/\D/g, '');
  return digits.length === 11 && digits.startsWith('7');
}

function showFieldError(field, message){
  clearFieldError(field);
  field.classList.add('field-error');
  const errEl = document.createElement('div');
  errEl.className = 'field-error-msg';
  errEl.textContent = message;
  field.parentNode.insertBefore(errEl, field.nextSibling);
}

function clearFieldError(field){
  field.classList.remove('field-error');
  const next = field.nextElementSibling;
  if (next && next.classList.contains('field-error-msg')){
    next.remove();
  }
}

function initPhoneInputs(){
  safeQSA('input[type="tel"]').forEach(input => {
    input.addEventListener('input', () => {
      const cursorPos = input.selectionStart;
      const oldLen = input.value.length;
      input.value = formatPhoneInput(input.value);
      const newLen = input.value.length;
      const newPos = cursorPos + (newLen - oldLen);
      input.setSelectionRange(newPos, newPos);
      if (input.value.length > 0) clearFieldError(input);
    });
    input.addEventListener('focus', () => {
      if (!input.value) input.value = '+7';
    });
    input.addEventListener('blur', () => {
      if (input.value === '+7' || input.value === '+7 (') input.value = '';
    });
  });
}

/* ==============================
   FORM VALIDATION & SUBMISSION
   ============================== */
function initForms(){
  safeQSA('[data-form]').forEach(form => {
    form.addEventListener('submit', e => {
      e.preventDefault();
      let valid = true;

      // Validate name if present
      const nameInput = form.querySelector('input[name="name"], input[type="text"]');
      if (nameInput){
        const nameVal = nameInput.value.trim();
        if (nameVal.length < 2){
          showFieldError(nameInput, 'Укажите имя (минимум 2 символа)');
          valid = false;
        } else {
          clearFieldError(nameInput);
        }
      }

      // Validate phone
      const phone = form.querySelector('input[type="tel"]');
      const phoneValue = phone ? phone.value.trim() : '';
      if (!phoneValue || !isValidPhone(phoneValue)){
        if (phone){
          showFieldError(phone, 'Введите телефон в формате +7 (XXX) XXX-XX-XX');
          phone.focus();
        }
        valid = false;
      } else if (phone){
        clearFieldError(phone);
      }

      if (!valid) return;

      const title = form.dataset.formTitle || 'Запрос с сайта';
      const msg = `Здравствуйте! ${title}. Мой телефон: ${phoneValue}`;
      window.open(waLink(msg), '_blank', 'noopener');
      showToast('Спасибо! Менеджер свяжется в течение 15 минут');
      form.reset();
    });
  });
}

/* ==============================
   QUIZ
   ============================== */
const quizSteps = [
  {
    label:'Шаг 1 из 5',
    question:'Для какой задачи вы выбираете участок?',
    options:[
      {icon:'📈', text:'Инвестиция — заработать на росте стоимости', value:'invest'},
      {icon:'🏡', text:'Для жизни — построить дом', value:'life'},
      {icon:'🌊', text:'Для отдыха — дача, сезонный дом', value:'rest'},
      {icon:'🏕', text:'Под аренду / глэмпинг / бизнес', value:'business'}
    ]
  },
  {
    label:'Шаг 2 из 5',
    question:'Какой регион вам ближе?',
    options:[
      {icon:'🌊', text:'Южные регионы — видовой и курортный формат', value:'yubk'},
      {icon:'🌿', text:'Западное направление — более спокойный сценарий', value:'west'},
      {icon:'🌄', text:'Видовые локации — отдых и эмоциональный актив', value:'east'},
      {icon:'🌲', text:'Подмосковье — удобно для постоянной жизни', value:'mo'}
    ]
  },
  {
    label:'Шаг 3 из 5',
    question:'Какой бюджет комфортен?',
    options:[
      {icon:'💰', text:'До 1,5 млн ₽', value:'1'},
      {icon:'💰', text:'1,5–3 млн ₽', value:'2'},
      {icon:'💰', text:'3–5 млн ₽', value:'3'},
      {icon:'💰', text:'Более 5 млн ₽', value:'4'}
    ]
  },
  {
    label:'Шаг 4 из 5',
    question:'Нужны ли коммуникации и рассрочка?',
    options:[
      {icon:'⚡', text:'Нужны готовые коммуникации', value:'comm'},
      {icon:'📋', text:'Нужна рассрочка', value:'installment'},
      {icon:'🌱', text:'Главное — потенциал участка, остальное потом', value:'potential'},
      {icon:'🤝', text:'Нужен баланс по цене и готовности', value:'balance'}
    ]
  },
  {
    label:'Шаг 5 из 5',
    question:'Какой результат для вас лучший?',
    options:[
      {icon:'📄', text:'Юр. чистый участок с понятными документами', value:'legal'},
      {icon:'📍', text:'Сильная локация и транспортная доступность', value:'location'},
      {icon:'📈', text:'Максимальный рост цены за 2–3 года', value:'roi'},
      {icon:'🏗', text:'Возможность быстро начать стройку', value:'build'}
    ]
  }
];
let quizIndex = 0;
const quizAnswers = {};

function renderQuiz(){
  const mount = safeQS('#quizBody');
  const bar = safeQS('#quizProgress');
  if (!mount || !bar) return;
  const step = quizSteps[quizIndex];
  bar.style.width = `${((quizIndex + 1) / quizSteps.length) * 100}%`;
  mount.innerHTML = `
    <div class="quiz-label">${step.label}</div>
    <div class="quiz-question">${step.question}</div>
    <div class="quiz-options">
      ${step.options.map(opt => `
        <label class="quiz-option ${quizAnswers[quizIndex] === opt.value ? 'is-selected' : ''}">
          <input type="radio" name="quizStep" value="${opt.value}" style="display:none" ${quizAnswers[quizIndex] === opt.value ? 'checked' : ''}>
          <div style="font-size:1.3rem">${opt.icon}</div>
          <div>${opt.text}</div>
        </label>
      `).join('')}
    </div>
    <div class="quiz-nav">
      <button class="btn btn--ghost" type="button" id="quizPrev">${quizIndex === 0 ? 'На главную' : 'Назад'}</button>
      <button class="btn btn--cta" type="button" id="quizNext">${quizIndex === quizSteps.length - 1 ? 'Показать результат' : 'Далее'}</button>
    </div>`;
  safeQSA('.quiz-option', mount).forEach(opt => {
    opt.addEventListener('click', () => {
      safeQSA('.quiz-option', mount).forEach(x => x.classList.remove('is-selected'));
      opt.classList.add('is-selected');
      const input = opt.querySelector('input');
      if (input) {
        input.checked = true;
        quizAnswers[quizIndex] = input.value;
      }
    });
  });
  safeQS('#quizPrev')?.addEventListener('click', () => {
    if (quizIndex === 0){
      window.location.href = 'index.html';
      return;
    }
    quizIndex -= 1;
    renderQuiz();
  });
  safeQS('#quizNext')?.addEventListener('click', () => {
    if (!quizAnswers[quizIndex]){
      showToast('Выберите вариант ответа');
      return;
    }
    if (quizIndex === quizSteps.length - 1){
      renderQuizResult();
      return;
    }
    quizIndex += 1;
    renderQuiz();
  });
}

function renderQuizResult(){
  const mount = safeQS('#quizBody');
  const bar = safeQS('#quizProgress');
  if (!mount || !bar) return;
  bar.style.width = '100%';
  const region = quizAnswers[1] || 'yubk';
  const scenario = quizAnswers[0] || 'invest';
  const matches = PLOTS.filter(plot => plot.status === 'free')
    .filter(plot => (plot.region || getPlotProject(plot)?.region) === region)
    .filter(plot => plot.scenarios?.includes(scenario))
    .slice(0, 3);
  const regionLabel = REGIONS.find(r => r.id === region)?.name || region.toUpperCase();
  mount.innerHTML = `
    <div class="quiz-result">
      <div style="font-size:3rem">🎯</div>
      <h2 class="section-title" style="font-size:2.1rem">Нашли несколько подходящих сценариев</h2>
      <p class="section-text" style="margin-left:auto;margin-right:auto">Показываем подходящие сценарии покупки и быстро переводим в живой контакт. Если нужен полный список, отправьте запрос и менеджер соберет персональную подборку.</p>
      <div class="quiz-result__grid">
        <div class="quiz-pill"><strong>${matches.length}</strong><span>готовых лотов</span></div>
        <div class="quiz-pill"><strong>${regionLabel}</strong><span>основное направление</span></div>
        <div class="quiz-pill"><strong>${(matches[0]?.roiEstimate || 22)}%</strong><span>потенциал ROI</span></div>
      </div>
      <div class="catalog-grid" style="margin:18px 0 24px">${matches.map(makePlotCard).join('')}</div>
      <div style="text-align:center;margin-top:24px">
        <h3>Получить персональную подборку</h3>
        <p class="muted">Оставьте телефон — менеджер свяжется в течение 15 минут и подберет лоты под ваш сценарий.</p>
        <div style="display:flex;gap:12px;justify-content:center;flex-wrap:wrap;margin-top:18px">
          <a class="btn btn--cta" href="${waLink(`Здравствуйте! Прошел квиз на сайте. Сценарий: ${SCENARIO_LABELS[scenario]?.name || scenario}, регион: ${regionLabel}. Подберите, пожалуйста, участки.`)}" target="_blank" rel="noopener">Написать в WhatsApp</a>
          <a class="btn btn--outline" href="catalog.html">Смотреть каталог</a>
        </div>
      </div>
    </div>`;
}

/* ==============================
   CALCULATOR (with validation)
   ============================== */
function clampCalcInput(input, min, max){
  let val = parseFloat(input.value);
  if (isNaN(val) || val < min) val = min;
  if (val > max) val = max;
  input.value = val;
  return val;
}

function updateCalc(){
  const priceEl = safeQS('#calcPrice');
  const areaEl = safeQS('#calcArea');
  const yearsEl = safeQS('#calcYears');
  const growthEl = safeQS('#calcGrowth');
  const expensesEl = safeQS('#calcExpenses');
  if (!priceEl) return;

  const price = clampCalcInput(priceEl, 0, 999999999);
  const area = clampCalcInput(areaEl, 0.1, 10000);
  const years = clampCalcInput(yearsEl, 1, 30);
  const growth = clampCalcInput(growthEl, -50, 200);
  const expenses = clampCalcInput(expensesEl, 0, 999999999);

  if (price <= 0){
    const map = { calcTitle: 'Укажите стоимость покупки', calcFuturePrice: '—', calcInvestment: '—', calcProfit: '—', calcROI: '—', calcPerSotka: '—' };
    Object.entries(map).forEach(([id, value]) => {
      const el = safeQS('#' + id);
      if (el) el.textContent = value;
    });
    return;
  }

  const futurePrice = price * Math.pow(1 + growth / 100, years);
  const investment = price + expenses;
  const profit = futurePrice - investment;
  const roi = investment ? (profit / investment) * 100 : 0;
  const perSotka = area ? futurePrice / area : 0;
  const yWord = years == 1 ? 'год' : (years < 5 ? 'года' : 'лет');

  const map = {
    calcTitle: `Прогноз через ${years} ${yWord}`,
    calcFuturePrice: formatRuNumber(Math.round(futurePrice)) + ' ₽',
    calcInvestment: formatRuNumber(Math.round(investment)) + ' ₽',
    calcProfit: (profit >= 0 ? '+' : '') + formatRuNumber(Math.round(profit)) + ' ₽',
    calcROI: (roi >= 0 ? '+' : '') + roi.toFixed(1) + '%',
    calcPerSotka: formatRuNumber(Math.round(perSotka)) + ' ₽'
  };
  Object.entries(map).forEach(([id, value]) => {
    const el = safeQS('#' + id);
    if (el) el.textContent = value;
  });

  // Show CTA after calculation
  const ctaMount = safeQS('#calcCTA');
  if (ctaMount && !ctaMount.innerHTML.trim()){
    ctaMount.innerHTML = `<div style="text-align:center;margin-top:20px;padding:16px;background:#f7faf7;border:1px solid var(--line);border-radius:18px">
      <p style="margin:0 0 12px;color:var(--muted)">Хотите обсудить расчет с экспертом?</p>
      <a class="btn btn--cta btn--sm" href="${waLink('Здравствуйте! Посчитал доходность на калькуляторе. Хочу обсудить реальные варианты.')}" target="_blank" rel="noopener">Обсудить с менеджером</a>
    </div>`;
  }
}
window.updateCalc = updateCalc;

function formatRuNumber(n){
  return n.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ' ');
}

/* ==============================
   CATALOG CONTROLS
   ============================== */
function initCatalogControls(){
  safeQSA('[data-region-filter]').forEach(btn => {
    btn.addEventListener('click', () => {
      SiteState.filters.region = btn.dataset.regionFilter;
      renderCatalogDebounced();
    });
  });
  safeQSA('[data-scenario-filter]').forEach(btn => {
    btn.addEventListener('click', () => {
      const next = btn.dataset.scenarioFilter;
      SiteState.filters.scenario = SiteState.filters.scenario === next ? 'all' : next;
      renderCatalogDebounced();
    });
  });
  const sea = safeQS('[data-toggle-sea]');
  if (sea) sea.addEventListener('click', () => {
    SiteState.filters.sea = !SiteState.filters.sea;
    renderCatalogDebounced();
  });
  const inst = safeQS('[data-toggle-installment]');
  if (inst) inst.addEventListener('click', () => {
    SiteState.filters.installment = !SiteState.filters.installment;
    renderCatalogDebounced();
  });
  const sort = safeQS('#catalogSort');
  if (sort) sort.addEventListener('change', () => {
    SiteState.filters.sort = sort.value;
    renderCatalogDebounced();
  });
  const reset = safeQS('#catalogReset');
  if (reset) reset.addEventListener('click', () => {
    SiteState.filters = { region:'all', scenario:'all', sea:false, installment:false, status:'free', sort:'recommended' };
    renderCatalog();
  });
}

/* ==============================
   SMOOTH SCROLL FOR ANCHOR LINKS
   ============================== */
function initSmoothScroll(){
  document.addEventListener('click', (e) => {
    const link = e.target.closest('a[href^="#"]');
    if (!link) return;
    const hash = link.getAttribute('href');
    if (hash.length <= 1) return;
    const target = document.querySelector(hash);
    if (target){
      e.preventDefault();
      target.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }
  });
}

/* ==============================
   SCROLL-TO-TOP BUTTON
   ============================== */
function initScrollToTop(){
  const btn = safeQS('#scrollToTop');
  if (!btn) return;
  let ticking = false;
  window.addEventListener('scroll', () => {
    if (!ticking){
      window.requestAnimationFrame(() => {
        btn.classList.toggle('is-show', window.scrollY > 600);
        ticking = false;
      });
      ticking = true;
    }
  }, { passive: true });
  btn.addEventListener('click', () => {
    window.scrollTo({ top: 0, behavior: 'smooth' });
  });
}

/* ==============================
   LAZY LOADING IMAGES
   ============================== */
function initLazyImages(){
  if ('loading' in HTMLImageElement.prototype) return; // native lazy loading supported
  const images = safeQSA('img[loading="lazy"]');
  if (!images.length) return;
  if ('IntersectionObserver' in window){
    const observer = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting){
          const img = entry.target;
          if (img.dataset.src){
            img.src = img.dataset.src;
          }
          observer.unobserve(img);
        }
      });
    }, { rootMargin: '200px' });
    images.forEach(img => observer.observe(img));
  }
}

/* ==============================
   ACTIVE NAV
   ============================== */
function setActiveNav(){
  const page = document.body.dataset.page;
  safeQSA('[data-nav]').forEach(link => {
    link.classList.toggle('is-active', link.dataset.nav === page);
  });
}

/* ==============================
   INIT ON DOMContentLoaded
   ============================== */
document.addEventListener('DOMContentLoaded', () => {
  loadCompare();
  updateCompareCount();
  setActiveNav();
  initBurger();
  initFAQ();
  initPhoneInputs();
  initForms();
  initCatalogControls();
  initSmoothScroll();
  initScrollToTop();
  initLazyImages();
  renderIndexOffers();
  renderCatalog();
  renderGeoPlots();
  renderIntentOffers();
  renderProjectShowcase();
  renderRelatedPlots();
  renderComparePage();
  renderGenplan();
  if (document.body.dataset.page === 'quiz') renderQuiz();
  updateCalc();
});
