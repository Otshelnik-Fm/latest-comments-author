## Описание:  

Дополнение для WordPress плагина [WP-Recall](https://wordpress.org/plugins/wp-recall/) - В профиле пользователя добавляет вкладку "Комментарии". Выводит комментарии пользователя - от новых, к старым, с постраничной навигацией.  

------------------------------

## Demo:  

Демонстрация работы дополнения: [здесь](https://otshelnik-fm.ru/author/otshelnik-fm/?tab=latestcomments&utm_source=free-addons&utm_medium=addon-description&utm_campaign=latest-comments-author&utm_content=github.com&utm_term=latest-message).  

Скриншоты допа доступны в каталоге дополнений codeseller, на странице [дополнения](https://codeseller.ru/products/latest-comments-author-poslednie-kommentarii-polzovatelya/)  

------------------------------

## Возможности:  

- Выводит вкладку "Комментарии" в личном кабинете  
- Возможность указать приватность вкладке комментариев  
- Считает общее количество комментариев пользователя  
- Нумерует каждый комментарий  
- Возможность установить количество комментариев на страницу  
- Показывает рейтинг комментария  
- Можно голосовать за комментарии, никуда не переходя  
- Контент отображается как есть (смайлы, картинки, форматирование)  
- Возможность включить подкрашивание контентного блока цветом WP-Recall  
- Поддерживается минимизация стилей  
- Ajax загрузка вкладки и постраничной навигации  
- Поддерживается кеширование от WP-Recall  
- В правой части меню реколлбара добавлен пункт меню "Ваши комментарии"  
- Вкладка "комментарии" выводится в зоне Counters, с показом количества комментариев пользователя  

------------------------------

## Установка/Обновление:  

**Установка:**  
Т.к. это дополнение для WordPress плагина WP-Recall, то оно устанавливается через [менеджер дополнений WP-Recall](https://codeseller.ru/obshhie-svedeniya-o-dopolneniyax-wp-recall/)  

1. В админке вашего сайта перейдите на страницу "WP-Recall" -> "Дополнения" и в самом верху нажмите на кнопку "Обзор", выберите .zip архив дополнения на вашем пк и нажмите кнопку "Установить".  
2. В списке загруженных дополнений, на этой странице, найдите это дополнение, наведите на него курсор мыши и нажмите кнопку "Активировать". Или выберите чекбокс и в выпадающем списке действия выберите "Активировать". Нажмите применить.  


**Обновление:**  
Дополнение поддерживает [автоматическое обновление](https://codeseller.ru/avtomaticheskie-obnovleniya-dopolnenij-plagina-wp-recall/) - два раза в день отправляются вашим сервером запросы на обновление.  
Если в течении суток вы не видите обновления (а на странице дополнения вы видите что версия вышла новая), советую ознакомиться с этой [статьёй](https://codeseller.ru/post-group/rabota-wordpress-krona-cron-prinuditelnoe-vypolnenie-kron-zadach-dlya-wp-recall/) 

------------------------------

## Настройки:  
### Блок настроек в админке:  

В админке переходим "WP-Recall" -> "Настройки" -> "Настройки Latest comments author"  

**1.** Возможность указать, сколько комментариев на страницу выводить  
**2.** Возможно глобально указать, кому будет доступно содержимое вкладки комментариев.  
По умолчанию: Всем (видят и гости и авторизованные).  
"Только авторизованным" - содержимое не будет доступно гостям, а авторизованным пользователям - доступно к просмотру.  
"Только хозяину ЛК" - комментарии будет видеть только сам их автор.  
**3.** Подкрашиваем контентный блок цветом WP-Recall - подробно скриншоты и описание реализации описано в этой статье: ["Используем цвет реколл, которым мы стилизуем кнопки, для своих дополнений"](https://codeseller.ru/post-group/ispolzuem-cvet-rekoll-kotorym-my-stilizuem-knopki-dlya-svoix-dopolnenij/)  

------------------------------


## Changelog:  
**2018-12-10**  
v3.5  
* поддержка WP-Recall 16.17.0  
* поддержка дополнения <a href="https://codeseller.ru/products/you-need-to-login/">You Need To Login</a>  
* минимизация инлайн стилей  
* небольшой рефакторинг - привел к стандарту 16.17  
* изменения в вёрстке и стилях  


**2018-05-12**  
v3.4.1  
* Исправлено подключения настроек - на мультисайтах приводило к ошибке. Расследование https://otshelnik-fm.ru/?p=3629  
* Добавлена иконка дополнения  


**2017-04-26**   
v3.4  
* Исправлен баг при ajax-загрузке сторонних вкладок - в логи генерировалась ошибка mysql синтаксиса  


**2017-04-21**  
v3.3  
* Работа с 16-й версией WP-Recall  


**2016-08-13**  
v3.2  
* Исправлена ошибка выборки из бд  


**2016-07-22**  
v3.1  
* Работа с 15-й версией WP-Recall.  
* В правой части меню реколлбара - добавлен пункт меню "Ваши комментарии".  
* Вкладка "комментарии" выводится в зоне Counters, с показом количества комментариев пользователя.  
* Стили загружаются там, где необходимы.  


**2016-06-18**  
v3.0  
* Дополнение полностью переписано.  
* Убрал зависимость от системы рейтинга.  
* Пагинация от WP-Recall  
* Дополнение поддерживает загрузку ajax вкладок и <a title="Читайте ниже по ссылке про кеширование" href="https://codeseller.ru/post-group/wp-recall-14-0-nastraivaem-svoj-cvet-keshiruem-i-obnovlyaemsya-s-drugogo-domena/" target="_blank">кеширование</a> WP-Recall  
* Возможность включить <a href="https://codeseller.ru/post-group/ispolzuem-cvet-rekoll-kotorym-my-stilizuem-knopki-dlya-svoix-dopolnenij/" target="_blank">подкрашивание контентного блока цветом WP-Recall</a>  
* Переработан дизайн  


**2015-09-06**  
v2.0  
* Совместимость с Wp-Recall 13.2.3 (обновление из админки в один клик и возможность оттуда следить за новыми версиями)  
* Добавил возможность голосовать  
* Стили постраничной навигации такие же как и у WP-Recall  
* Удалил стили wp-pagenavi  
* Новый дизайн в стиле Across Ocean  
* Работа под мобильными девайсами  


**2015-03-09**  
v1.0  
* Release  

------------------------------

## Поддержка и контакты:  

* Поддержка осуществляется в рамках текущего функционала дополнения  
* При возникновении проблемы, создайте соотвествующую тему на [форуме поддержки](https://codeseller.ru/forum/product-8977/) товара  
* Если вам нужна доработка под ваши нужды - вы можете обратиться ко мне в [ЛС](https://codeseller.ru/author/otshelnik-fm/?tab=chat) с техзаданием на платную доработку.  

Полный список моих работ опубликован на [моём сайте](https://otshelnik-fm.ru/all-my-addons-for-wp-recall/?utm_source=free-addons&utm_medium=addon-description&utm_campaign=latest-comments-author&utm_content=github.com&utm_term=all-my-addons) и в каталоге магазина [CodeSeller.ru](https://codeseller.ru/author/otshelnik-fm/?tab=publics&subtab=type-products)  

------------------------------

## Author:  

**Wladimir Druzhaev** (Otshelnik-Fm)  