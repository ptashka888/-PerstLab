
const PROJECTS = [
  {
    id: 'pobednyj-bereg',
    name: 'Победный берег',
    region: 'yubk', regionName: 'Южные регионы',
    city: 'видовой формат', line: 'проект с инвестиционным мотивом',
    description: 'Проект для тех, кто ищет участок под дом, отдых или инвестицию с сильным эмоциональным фактором и потенциалом роста интереса к локации.',
    infrastructure: ['Электричество','Подъездные дороги','Возможность подключения коммуникаций','Поэтапная выдача свободных лотов'],
    stages: [{name:'Текущий пул',status:'sale'},{name:'Резерв',status:'plan'}],
    plotsTotal: 24, plotsFree: 12,
    priceMin: null, priceMax: null,
    areaMin: 7, areaMax: 15,
    seaDistance: 900,
    priceLabel: 'по запросу',
    features: ['Видовой формат','ИЖС','Рассрочка','Под инвестицию'],
    gradient: 'linear-gradient(135deg, #2D6A4F22, #40916C33)'
  },
  {
    id: 'velikie-luga',
    name: 'Великие Луга',
    region: 'west', regionName: 'Западное направление',
    city: 'спокойный дачный формат', line: 'под дом и отдых',
    description: 'Проект для тех, кто ищет более спокойный загородный сценарий: дом, дача, семейное владение и понятный горизонт использования.',
    infrastructure: ['Электричество','Подъезд','Базовая инженерия','Возможность подбора по площади'],
    stages: [{name:'Текущий пул',status:'sale'}],
    plotsTotal: 31, plotsFree: 17,
    priceMin: null, priceMax: null,
    areaMin: 8, areaMax: 18,
    seaDistance: null,
    priceLabel: 'по запросу',
    features: ['Для жизни','Для дачи','ИЖС','Семейный сценарий'],
    gradient: 'linear-gradient(135deg, #C4D7B222, #A0C49D33)'
  },
  {
    id: 'dzharylgach',
    name: 'Проект Джарылгач',
    region: 'east', regionName: 'Видовые локации',
    city: 'лоты под отдых и аренду', line: 'эмоциональный актив',
    description: 'Проект с сильной природной и рекреационной составляющей. Подходит тем, кто смотрит на участок как на эмоциональный актив и потенциальный арендный сценарий.',
    infrastructure: ['Электричество','Доступ к лотам по подборке','Подъездные пути','Подбор по сценарию использования'],
    stages: [{name:'Текущий пул',status:'sale'}],
    plotsTotal: 18, plotsFree: 9,
    priceMin: null, priceMax: null,
    areaMin: 5, areaMax: 12,
    seaDistance: 400,
    priceLabel: 'по запросу',
    features: ['Отдых','Видовой фактор','Арендный сценарий'],
    gradient: 'linear-gradient(135deg, #E8DFD022, #D4C5A933)'
  },
  {
    id: 'korolevskie-dachi',
    name: 'Королёвские дачи',
    region: 'mo', regionName: 'Подмосковье',
    city: 'формат постоянной жизни', line: 'семейный спрос',
    description: 'Проект под стабильный жизненный сценарий: строительство дома, постоянное проживание и понятная логика загородной жизни.',
    infrastructure: ['Электричество','Дороги','Подключение инженерии','Семейный формат проекта'],
    stages: [{name:'Текущий пул',status:'sale'}],
    plotsTotal: 42, plotsFree: 21,
    priceMin: null, priceMax: null,
    areaMin: 7, areaMax: 20,
    seaDistance: null,
    priceLabel: 'по запросу',
    features: ['Подмосковье','Для жизни','ИЖС','Рассрочка'],
    gradient: 'linear-gradient(135deg, #D5C4A122, #C9B88C33)'
  }
];

const PLOTS = [
  {id:'pb-05', project:'pobednyj-bereg', num:5, status:'free', area:8, price:null, priceLabel:'по запросу', pricePerSotka:null,
   category:'ИЖС', vri:'Жилая застройка', cadastre:'по запросу',
   comm:{electric:'есть',gas:'уточняется',water:'уточняется',road:'подъезд есть'},
   seaDist:950, cityDist:'внутри проекта', airportDist:'уточняется', shopDist:'по проекту', schoolDist:'по направлению',
   scenarios:['rest','invest'], roiEstimate:24, relief:'видовой', view:'открытый горизонт',
   installment:{available:true, months:18, monthly:null}, legal:{ownership:'официальное оформление',encumbrances:'проверяется до сделки',checkDate:'март 2026'},
   color:'#A8D5BA', gradient:'linear-gradient(135deg, #A8D5BA, #74B49B)'},
  {id:'pb-12', project:'pobednyj-bereg', num:12, status:'free', area:10, price:null, priceLabel:'по запросу', pricePerSotka:null,
   category:'ИЖС', vri:'Жилая застройка', cadastre:'по запросу',
   comm:{electric:'есть',gas:'уточняется',water:'уточняется',road:'подъезд есть'},
   seaDist:800, cityDist:'внутри проекта', airportDist:'уточняется', shopDist:'по проекту', schoolDist:'по направлению',
   scenarios:['invest','life'], roiEstimate:28, relief:'ровный, угловой', view:'видовой лот',
   installment:{available:true, months:24, monthly:null}, legal:{ownership:'официальное оформление',encumbrances:'проверяется до сделки',checkDate:'март 2026'},
   color:'#2D8F5E', gradient:'linear-gradient(135deg, #2D8F5E, #1B7A4A)'},
  {id:'pb-18', project:'pobednyj-bereg', num:18, status:'reserved', area:14, price:null, priceLabel:'по запросу', pricePerSotka:null,
   category:'ИЖС', vri:'Жилая застройка', cadastre:'по запросу',
   comm:{electric:'есть',gas:'уточняется',water:'уточняется',road:'подъезд есть'},
   seaDist:900, cityDist:'внутри проекта', airportDist:'уточняется', shopDist:'по проекту', schoolDist:'по направлению',
   scenarios:['life','invest'], roiEstimate:null, relief:'видовой', view:'вид на горизонт',
   installment:{available:false}, legal:{ownership:'официальное оформление',encumbrances:'проверяется до сделки',checkDate:'февраль 2026'},
   color:'#E8A020', gradient:'linear-gradient(135deg, #E8D5A0, #D4B870)'},
  {id:'vl-03', project:'velikie-luga', num:3, status:'free', area:9, price:null, priceLabel:'по запросу', pricePerSotka:null,
   category:'ИЖС', vri:'Жилая застройка', cadastre:'по запросу',
   comm:{electric:'есть',gas:'уточняется',water:'по проекту',road:'подъезд есть'},
   seaDist:null, cityDist:'внутри проекта', airportDist:'уточняется', shopDist:'по направлению', schoolDist:'по направлению',
   scenarios:['life'], roiEstimate:16, relief:'ровный', view:'открытое пространство',
   installment:{available:true, months:24, monthly:null}, legal:{ownership:'официальное оформление',encumbrances:'проверяется до сделки',checkDate:'март 2026'},
   color:'#C4D7B2', gradient:'linear-gradient(135deg, #C4D7B2, #A0C49D)'},
  {id:'vl-09', project:'velikie-luga', num:9, status:'free', area:12, price:null, priceLabel:'по запросу', pricePerSotka:null,
   category:'ИЖС', vri:'Жилая застройка', cadastre:'по запросу',
   comm:{electric:'есть',gas:'уточняется',water:'по проекту',road:'подъезд есть'},
   seaDist:null, cityDist:'внутри проекта', airportDist:'уточняется', shopDist:'по направлению', schoolDist:'по направлению',
   scenarios:['life','rest'], roiEstimate:18, relief:'ровный', view:'просторный участок',
   installment:{available:true, months:18, monthly:null}, legal:{ownership:'официальное оформление',encumbrances:'проверяется до сделки',checkDate:'март 2026'},
   color:'#8AB87A', gradient:'linear-gradient(135deg, #8AB87A, #6EA05A)'},
  {id:'dz-04', project:'dzharylgach', num:4, status:'free', area:6, price:null, priceLabel:'по запросу', pricePerSotka:null,
   category:'СНТ', vri:'Садоводство', cadastre:'по запросу',
   comm:{electric:'есть',gas:'нет',water:'уточняется',road:'подъезд есть'},
   seaDist:350, cityDist:'внутри проекта', airportDist:'уточняется', shopDist:'по направлению', schoolDist:'по направлению',
   scenarios:['rest','invest'], roiEstimate:26, relief:'легкий уклон', view:'природный пейзаж',
   installment:{available:true, months:12, monthly:null}, legal:{ownership:'официальное оформление',encumbrances:'проверяется до сделки',checkDate:'март 2026'},
   color:'#87CEEB', gradient:'linear-gradient(135deg, #87CEEB, #5FA8D3)'},
  {id:'dz-08', project:'dzharylgach', num:8, status:'free', area:7, price:null, priceLabel:'по запросу', pricePerSotka:null,
   category:'СНТ', vri:'Садоводство', cadastre:'по запросу',
   comm:{electric:'есть',gas:'нет',water:'уточняется',road:'подъезд есть'},
   seaDist:420, cityDist:'внутри проекта', airportDist:'уточняется', shopDist:'по направлению', schoolDist:'по направлению',
   scenarios:['rest','invest'], roiEstimate:29, relief:'ровный', view:'видовой фактор',
   installment:{available:true, months:12, monthly:null}, legal:{ownership:'официальное оформление',encumbrances:'проверяется до сделки',checkDate:'февраль 2026'},
   color:'#B0C4DE', gradient:'linear-gradient(135deg, #B0C4DE, #8CAABE)'},
  {id:'kd-11', project:'korolevskie-dachi', num:11, status:'free', area:10, price:null, priceLabel:'по запросу', pricePerSotka:null,
   category:'ИЖС', vri:'Жилая застройка', cadastre:'по запросу',
   comm:{electric:'есть',gas:'возможен',water:'по проекту',road:'подъезд есть'},
   seaDist:null, cityDist:'внутри проекта', airportDist:'уточняется', shopDist:'по направлению', schoolDist:'по направлению',
   scenarios:['life'], roiEstimate:14, relief:'ровный', view:'загородный формат',
   installment:{available:true, months:24, monthly:null}, legal:{ownership:'официальное оформление',encumbrances:'проверяется до сделки',checkDate:'март 2026'},
   color:'#B0A880', gradient:'linear-gradient(135deg, #B0A880, #9A9060)'},
  {id:'kd-24', project:'korolevskie-dachi', num:24, status:'free', area:16, price:null, priceLabel:'по запросу', pricePerSotka:null,
   category:'ИЖС', vri:'Жилая застройка', cadastre:'по запросу',
   comm:{electric:'есть',gas:'возможен',water:'по проекту',road:'подъезд есть'},
   seaDist:null, cityDist:'внутри проекта', airportDist:'уточняется', shopDist:'по направлению', schoolDist:'по направлению',
   scenarios:['life','business'], roiEstimate:13, relief:'ровный', view:'просторный семейный формат',
   installment:{available:true, months:36, monthly:null}, legal:{ownership:'официальное оформление',encumbrances:'проверяется до сделки',checkDate:'февраль 2026'},
   color:'#C8B888', gradient:'linear-gradient(135deg, #C8B888, #B0A068)'},
  {id:'mix-02', project:null, num:2, status:'free', projectName:'Индивидуальная подборка', region:'west', regionName:'Западное направление', city:'под запрос клиента',
   area:11, price:null, priceLabel:'по запросу', pricePerSotka:null, category:'ИЖС', vri:'Уточняется', cadastre:'по запросу',
   comm:{electric:'уточняется',gas:'уточняется',water:'уточняется',road:'уточняется'}, seaDist:null, cityDist:'индивидуально', airportDist:'уточняется', shopDist:'уточняется', schoolDist:'уточняется',
   scenarios:['life','invest'], roiEstimate:19, relief:'уточняется', view:'подбирается под задачу', installment:{available:true, months:18, monthly:null}, legal:{ownership:'официальное оформление',encumbrances:'проверяется до сделки',checkDate:'март 2026'},
   color:'#D5C4A1', gradient:'linear-gradient(135deg, #D5C4A1, #C9B88C)'}
];

const SCENARIO_LABELS = {
  invest: {name:'Инвестиция', emoji:'📈', color:'#2D6A4F', bg:'rgba(45,106,79,.1)'},
  life:   {name:'Для жизни', emoji:'🏡', color:'#E8A020', bg:'rgba(232,160,32,.1)'},
  rest:   {name:'Отдых', emoji:'🌿', color:'#3B82F6', bg:'rgba(59,130,246,.1)'},
  business:{name:'Коммерция', emoji:'🏗', color:'#9333EA', bg:'rgba(147,51,234,.1)'}
};

const STATUS_LABELS = {
  free:     {name:'Свободен', color:'#40916C', bg:'#E8F5E9'},
  reserved: {name:'Бронь', color:'#E8A020', bg:'#FFF3E0'},
  sold:     {name:'Продан', color:'#9CA3AF', bg:'#F3F4F6'}
};

const REGIONS = [
  {id:'yubk', name:'Южные регионы', short:'Юг', cities:'видовые и курортные локации', count:12, avgPrice:null},
  {id:'west', name:'Западное направление', short:'Запад', cities:'спокойный дачный и семейный формат', count:18, avgPrice:null},
  {id:'east', name:'Видовые локации', short:'Видовые', cities:'эмоциональный актив и отдых', count:9, avgPrice:null},
  {id:'mo', name:'Подмосковье', short:'МО', cities:'постоянная жизнь и семейный спрос', count:21, avgPrice:null}
];

function formatPrice(n, label) {
  if (label) return label;
  if (n === null || n === undefined || Number.isNaN(Number(n))) return 'по запросу';
  return Number(n).toLocaleString('ru-RU') + ' ₽';
}
function formatPriceShort(n, label) {
  if (label) return label;
  if (n === null || n === undefined || Number.isNaN(Number(n))) return 'индивидуально';
  if (n >= 1000000) return (n/1000000).toFixed(1).replace('.0','') + ' млн ₽';
  return (n/1000).toFixed(0) + ' тыс ₽';
}
function getPlotName(plot) {
  if (plot.project) {
    const proj = PROJECTS.find(p => p.id === plot.project);
    return proj ? proj.name + ', уч. №' + plot.num : 'Участок №' + plot.num;
  }
  return (plot.projectName || 'Участок') + ', уч. №' + plot.num;
}
function getPlotCity(plot) {
  if (plot.city) return plot.city;
  const proj = PROJECTS.find(p => p.id === plot.project);
  return proj ? proj.city : '';
}
function getPlotRegion(plot) {
  if (plot.regionName) return plot.regionName;
  const proj = PROJECTS.find(p => p.id === plot.project);
  return proj ? proj.regionName : '';
}
