import { createPinia, defineStore } from 'pinia';

export const pinia = createPinia();

export const usePortalStore = defineStore('portal', {
  state: () => ({
    guest: {
      name: 'Алексей Смирнов',
      room: '1205',
      stay: '2-9 мая',
      balance: 18450,
      bonus: 3200
    },
    services: [
      {
        title: 'Уборка номера',
        category: 'Комфорт',
        description: 'Ежедневная уборка с заменой белья и мини-бара.',
        duration: 45,
        price: 1800
      },
      {
        title: 'Трансфер в аэропорт',
        category: 'Транспорт',
        description: 'Премиальный седан с Wi‑Fi и напитками.',
        duration: 60,
        price: 4200
      },
      {
        title: 'Экскурсия по городу',
        category: 'Гид',
        description: '3 часа с персональным гидом и фотосессией.',
        duration: 180,
        price: 7500
      }
    ],
    orders: [
      { title: 'Доставка завтрака', status: 'done', date: '02.05', room: '1205' },
      { title: 'Замена полотенец', status: 'in-progress', date: '03.05', room: '1205' },
      { title: 'Просьба о позднем выезде', status: 'new', date: '04.05', room: '1205' }
    ],
    menu: [
      { name: 'Стейк с пюре', price: 2100, category: 'Горячее' },
      { name: 'Цезарь с курицей', price: 950, category: 'Салаты' },
      { name: 'Томатный суп с базиликом', price: 640, category: 'Супы' },
      { name: 'Матча латте', price: 420, category: 'Напитки' }
    ],
    spa: ['Массаж Relax 60 мин', 'Хаммам 40 мин', 'Спа для двоих 90 мин'],
    news: [
      {
        title: 'Открылась летняя терраса',
        date: '01.05',
        excerpt: 'Панорамные виды и дегустации сезонного меню на крыше отеля.'
      },
      {
        title: 'Новый шеф-кондитер',
        date: '27.04',
        excerpt: 'Попробуйте фирменные десерты в ресторане и сервисе в номер.'
      },
      {
        title: 'SPA-неделя',
        date: '20.04',
        excerpt: 'Скидка 20% на все процедуры по промокоду RELAX.'
      }
    ],
    mapPoints: [
      { name: 'Рецепция', description: '1 этаж, круглосуточно' },
      { name: 'SPA-комплекс', description: 'Этаж 2, ежедневно 10:00–22:00' },
      { name: 'Ресторан', description: 'Этаж 1, завтрак 7:00–11:00' }
    ],
    guide: [
      {
        title: 'Лучшие места рядом',
        description: 'Парки, кофейни и музеи в шаговой доступности.'
      },
      {
        title: 'Маршрут для бега',
        description: 'Променад вдоль набережной, 4.2 км с разметкой.'
      }
    ]
  }),
  getters: {
    openRequestCount: (state) => state.orders.filter((o) => o.status !== 'done').length
  }
});
