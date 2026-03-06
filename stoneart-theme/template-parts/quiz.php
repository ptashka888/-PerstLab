<?php
/**
 * Template Part: Quiz (Lead Magnet)
 *
 * @package StoneArt
 */

$title    = sa_option('sa_quiz_title', 'Узнайте стоимость за 1 минуту');
$subtitle = sa_option('sa_quiz_subtitle', 'Ответьте на 4 вопроса и получите точный расчет + подарок по уходу');
?>

<section id="quiz-section" class="sa-section sa-section--dark sa-quiz sa-animate">
    <div class="sa-quiz__bg" style="background-image:url('https://images.unsplash.com/photo-1598387181032-a3103a2db5b3?auto=format&fit=crop&w=1920&q=80');"></div>

    <div class="sa-container">
        <div style="text-align:center;margin-bottom:2.5rem;">
            <h2 class="sa-section__title" style="color:var(--sa-white);"><?php echo esc_html($title); ?></h2>
            <p class="sa-section__subtitle" style="color:var(--sa-gray-300);"><?php echo esc_html($subtitle); ?></p>
        </div>

        <div class="sa-quiz__wrap">
            <div class="sa-quiz__header">
                <h2 class="sa-quiz__header-title">Калькулятор проекта</h2>
                <div class="sa-quiz__step-badge">Шаг <span id="current-step">1</span> из 4</div>
            </div>

            <div class="sa-quiz__progress">
                <div id="quiz-progress" class="sa-quiz__progress-bar" style="width:25%;"></div>
            </div>

            <div class="sa-quiz__body">
                <!-- Step 1: Product -->
                <div class="sa-quiz__step" id="step-1">
                    <h3 class="sa-quiz__step-title">Что именно планируете заказать?</h3>
                    <div class="sa-quiz__grid sa-quiz__grid--4">
                        <label class="sa-quiz__option">
                            <input type="radio" name="product" value="Столешница кухня" class="quiz-radio">
                            <img src="https://images.unsplash.com/photo-1556910103-1c02745aae4d?w=400" alt="Столешница" loading="lazy">
                            <span class="sa-quiz__option-label">Столешница (Кухня)</span>
                        </label>
                        <label class="sa-quiz__option">
                            <input type="radio" name="product" value="Подоконник" class="quiz-radio">
                            <img src="https://images.unsplash.com/photo-1584622650111-993a426fbf0a?w=400" alt="Подоконник" loading="lazy">
                            <span class="sa-quiz__option-label">Подоконники</span>
                        </label>
                        <label class="sa-quiz__option">
                            <input type="radio" name="product" value="Столешница ванная" class="quiz-radio">
                            <img src="https://images.unsplash.com/photo-1620626011761-996317b8d101?w=400" alt="Ванная" loading="lazy">
                            <span class="sa-quiz__option-label">Столешница (Ванная)</span>
                        </label>
                        <label class="sa-quiz__option">
                            <input type="radio" name="product" value="Лестница/Камин" class="quiz-radio">
                            <img src="https://images.unsplash.com/photo-1513694203232-719a280e022f?w=400" alt="Камин Ступени" loading="lazy">
                            <span class="sa-quiz__option-label">Лестница / Камин</span>
                        </label>
                    </div>
                </div>

                <!-- Step 2: Shape -->
                <div class="sa-quiz__step hidden" id="step-2">
                    <h3 class="sa-quiz__step-title">Выберите предполагаемую форму</h3>
                    <div class="sa-quiz__grid sa-quiz__grid--3">
                        <label class="sa-quiz__option sa-quiz__option--text">
                            <input type="radio" name="form_shape" value="Прямая" class="quiz-radio" checked>
                            <span class="sa-quiz__option-text">Прямая</span>
                            <span class="sa-quiz__option-hint">Классический вариант</span>
                        </label>
                        <label class="sa-quiz__option sa-quiz__option--text">
                            <input type="radio" name="form_shape" value="Угловая (Г-образная)" class="quiz-radio">
                            <span class="sa-quiz__option-text">Г-образная</span>
                            <span class="sa-quiz__option-hint">Угловая форма</span>
                        </label>
                        <label class="sa-quiz__option sa-quiz__option--text">
                            <input type="radio" name="form_shape" value="П-образная" class="quiz-radio">
                            <span class="sa-quiz__option-text">П-образная</span>
                            <span class="sa-quiz__option-hint">Максимум пространства</span>
                        </label>
                    </div>
                </div>

                <!-- Step 3: Sink -->
                <div class="sa-quiz__step hidden" id="step-3">
                    <h3 class="sa-quiz__step-title">Нужна ли интегрированная мойка (из того же камня)?</h3>
                    <div class="sa-quiz__grid sa-quiz__grid--2">
                        <label class="sa-quiz__option sa-quiz__option--text">
                            <input type="radio" name="sink" value="Да" class="quiz-radio">
                            <span class="sa-quiz__option-text">Да, нужна</span>
                            <span class="sa-quiz__option-hint">Бесшовная эстетика</span>
                        </label>
                        <label class="sa-quiz__option sa-quiz__option--text">
                            <input type="radio" name="sink" value="Нет" class="quiz-radio" checked>
                            <span class="sa-quiz__option-text">Нет, обычная</span>
                            <span class="sa-quiz__option-hint">Накладная/подстольная</span>
                        </label>
                    </div>
                </div>

                <!-- Step 4: Contact -->
                <div class="sa-quiz__step hidden" id="step-4">
                    <div class="sa-quiz__form">
                        <h3 class="sa-quiz__form-title">Готово! Куда отправить расчет?</h3>
                        <p class="sa-quiz__form-subtitle">За номером будет закреплена <strong>скидка до 30 000 ₽</strong> и подарок.</p>

                        <form id="quiz-form">
                            <div class="sa-quiz__contact-methods">
                                <label>
                                    <input type="radio" name="contact_method" value="WhatsApp" checked>
                                    <span><i class="fa-brands fa-whatsapp" style="color:#22c55e;"></i> WhatsApp</span>
                                </label>
                                <label>
                                    <input type="radio" name="contact_method" value="Telegram">
                                    <span><i class="fa-brands fa-telegram" style="color:#3b82f6;"></i> Telegram</span>
                                </label>
                                <label>
                                    <input type="radio" name="contact_method" value="Phone">
                                    <span><i class="fa-solid fa-phone" style="color:var(--sa-gray-500);"></i> Звонок</span>
                                </label>
                            </div>

                            <div class="sa-quiz__input-wrap">
                                <i class="fa-solid fa-mobile-screen sa-quiz__input-icon"></i>
                                <input type="tel" id="user-phone" name="phone" placeholder="+7 (999) 000-00-00" required class="sa-quiz__input">
                            </div>

                            <button type="submit" class="sa-btn sa-btn--primary sa-btn--xl">
                                Получить расчет
                            </button>

                            <p class="sa-quiz__privacy"><i class="fa-solid fa-lock"></i> Ваши данные надежно защищены.</p>
                        </form>
                    </div>
                </div>

                <!-- Navigation -->
                <div class="sa-quiz__nav">
                    <button type="button" id="btn-prev" class="sa-btn sa-btn--outline" style="display:none;">← Назад</button>
                    <button type="button" id="btn-next" class="sa-btn sa-btn--dark" style="margin-left:auto;">Далее →</button>
                </div>
            </div>
        </div>
    </div>
</section>
