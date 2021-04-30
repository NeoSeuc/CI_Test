# Backend PHP | Full stack  TT v3

## Начало - Внимательно прочтите этот текст!


В папке web/system находится наше модифицированное ядро CodeIgniter v3.

Краткое достаточное руководство по нашему улучшенному CI находиться в **web/system/docs/Code_style_and_guide.md**

После его прочтения становится понятно как использовать Emerald_model'ы и остальные плюшки фреймворка. Мы работаем всегда с моделями Emerald_model - наши ORM-OOP модели
для работы с базой, связями. Они представляют базу данных и работу с ней, вся логика также хранится в них, "сервисный слой" также. Для работы с базой данных мы используем
Sparrow, вся документация также есть. НЕ ИСПОЛЬЗОВАТЬ СТАНДАРТНЫЙ CODEIGNITER DATABASE Класс.

В тестовом Phinx миграции не нужно делать. Достаточно залить дамп!

В папке web/application реализован полный пример приложения.

Точка входа: Main_page - контроллер, где все основные методы.

После установки вы увидите главную страницу с информацией которая поможет сделать задание.

## Инициализация через Docker

Файлы проекта находятся в папке web

### Init env on Windows

``init.bat`` - Initialize docker, pull images, init db, install composer dependencies.

### Base commands for Unix

``make init`` - Initialize docker, pull images, init db, install composer dependencies.  
``Доп команды``
``make docker-start`` - Start docker  
``make docker-stop`` - Stop docker containers  
``make logs`` - get containers logs

### PMA

Войти в PMA:  
``server`` - leave blank  
``username`` - root  
``password`` - root

### Конфигурация приложения (не требует изменений)

``'hostname' => 'frozeneon-mysql',
'username' => 'dev',
'password' => 'dev',
'database' => 'test_task',``

## Инициализация без Docker'a (no_docker_setup)

### PHP

Версии PHP 7.3+ Составлялось и проверялось на 7.3

### nginx

no_docker_setup/nginx/test_task.conf - в nginx conf.d или sites_available и укажите ваш домен `CI_DOMAIN`, к примеру `sometestworkspace.com`

### Приложение

Изменить на ваши пути public/index.php - **$application_folder**

config/database.php - **параметры базы**

### MySQL

db_dump/init_db.sql - залить дамп

Какие то мелочи могут не работать, например `php - short_open_tags -> on`. Фиксим вручную :)

#### После сделаного тестового.

**Заливайте пожалуйста новый дамп базы, config файл nginx, если надо собрать фронт - прям тут напишите инструкцию! Спасибо за понимание!**

--------------------------

## ЗАДАЧИ

`Задачи идут по возрастанию сложности кроме "задачи со звездочкой". Не обязательно выполнять все до единой, но каждая задача показывает Ваш уровень разработки по-своему.`

У нас в нашем мини-инстаграме уже есть список постов и просмотр информации о посте. Но вот не хватает чуть монетизации и юзабилити.

**Задача 1**. `/main_page/login` - Реализовать авторизацию. Login model уже содержит наброски для работы с сессией. Достаточно сделать логин-пароль авторизацию в базе
данных. Для удобства пароль можно не шифровать, чтобы при проверке мы могли легко авторизоваться.

**Задача 2**. `/main_page/comment` - Реализовать возможность комментирования постов. Реализовать вложенные комментарии. Количество уровней вложенности не ограничено.
Любыми способами на основе нашей текущей структуры переделать/улучшить/расширить возможность комментирования любого поста неограниченное число раз.

**Задача 3**. `/main_page/like` - Реализовать возможность лайкнуть пост или комментарий. Число лайков на один пост/комментарий не ограничено (пользователь может лайкать пока у него на балансе еще есть лайки).

**Задача 4**. `/main_page/add_money` - Реализовать монетизацию. Есть готовый API-Endpoint для зачисления средств принимающий количество валюты. У юзеров в базе есть
столбец `wallet_balance` отвечающий за баланс.
`wallet_total_refilled` - сумма, на которую юзер пополнил баланс за все время, `wallet_total_withdrawn` - сумма, которую юзер потратил (превратил в лайки). Эти два поля должны учитывать все действия по счету пользователя (пополнения и траты). Используемая валюта - доллар США. Любая работа с балансом пользователя должна быть максимально безопасна и отказоустойчива (все решения по этому поводу необходимо описать и обосновать).

**Задача 5**. `/main_page/buy_boosterpack` - Поскольку сейчас в мире самым популярным способом монетизации игр является покупка "бустерпаков" - сундуков/ящиков/кейсов с
предметами/карточками/деньгами, - предлагаем реализовать эту максимально простую функциональность для наших пользователей :)

Нужно создать 3 бустерпака которые будут стоить 5$, 20$ и 50$. В базе для этого присутствует структура, класс также частично реализован.
Бустерпак должен содержать в себе опеределенное количество айтемов, в базе для этого присутствует структура, класс также частично реализован.
Айтем — это количество лайков 1 лайк = 1 usd.

Покупая такой пак пользователь
получает случайный айтем в виде количества лайков которые может потратить на "лайкинг" постов и комментариев. Лайки попадают на "лайк-счет" пользователя с которого он их будет
тратить, то есть параллельно храним как счет в $, так и отдельный счет числа лайков у юзера.

Логика бустерпака:

```
С учетом накопленного профитбанка нужно выбрать доступную для выдачи часть бустерпака с учетом его цены.
Из того какие айтемы в себе содержит бустерпак, нужно выбрать все айтемы до размера профитбанка и из них рандомных айтем  — получаемое юзером число лайков.  
По результату выполнения нужно уменьшить значение профитбанка по формуле 
[профитбанк = профитбанк + цена бустерпака — наша комиссия(us) - стоимость выданных в текущем открытии лайков]
     
Рассмотрим пример бустерпака за 5$:

Банк равен 0$. Пользователь покупает пак за 5$.

Формула: 
максимальная цена = профитбанк + (цена бустерпака - наша комиссия(us))
выборка = все айтемы в бустерпаке до максимальной цены
результат = рандом(выборка).

Допустим, юзер получил 1 лайк, в банк уходит 5$ - 1$(us) - 1 лайк(1$) = 3$

	Банк равен 3$. Пользователь покупает еще один пак за 5$.

Формула: 
максимальная цена = профитбанк + (цена бустерпака - наша комиссия(us))
выборка = все айтемы в бустерпаке до максимальной цены.
результат = рандом(выборка).

Допустим, юзер получил 6 лайков, в банк уходит 5$ - 1$(us) - 6 лайков(6$) = -2$, итого банк равен 0$(т.к. не может быть меньше нуля)

	Банк равен 0$. Пользователь покупает еще один пак за 5$.

Формула: 
максимальная цена = профитбанк + (цена бустерпака - наша комиссия(us))
выборка = все айтемы в бустерпаке до максимальной цены.
результат = рандом(выборка).

Допустим, юзер получил 1 лайк, в таком случае в банк уходит 5$ - 1$ - 1$ лайков(1$) = 3$, итого банк равен 3$

```

Таким образом разность между результатом юзера и ценой бустерпака которую мы не выдали ему используется в будущих открытиях и выдается кому-то из следующих юзеров.

**Задача 6** `Class Transaction` Обеспечение максимальной безопасности и консистентности данных. Сделать лог любых изменений баланса.

Посказка:
Сделать `Class Transaction_type` который будет расширять наш класс - enum ( `\System\Emerald\Emerald_enum` ) в котором будут константы для таблицы транзакций, который
будет отвечать в логе за тип - списание или зачисление средств. `Class Transaction_info` - отвечать за источник, на что были потрачены/зачислены средства.

Основные требования к задаче - возможность создания mysql запросов, которые надо сделать и положить в mysql/6task.sql

1. Выборка по часам сколько денег потрачено на бустерпаки, по каждому бустерпаку. При этом, показать также сколько получили юзеры из них в эквиваленте $. Выборка должна
   быть за месяц.
2. Выборка по юзеру, сколько он пополнил средств и получил лайков, насколько он будет везучий так сказать :) . Остаток на счету $ и лайков.
3. Задачу 1 и 2 сделать в один Mysql запрос.

Для успешного прохождения проверки, необходимо выполинть сделующие условия:

- Склонировать проект, или создать его копию в своем публичном репозитории.
- Выполнить все задания в отдельной ветке и сделать Pull Request в Master.
- Все методы должны работать через Http запросы (а лучше сделать дополнительную задачу и дописать по примеру фронт на vue.js).
- Исходный код тестового задания не должен быть отформатирован ( чтоб в Pull Request были только изменения касающиеся задач )

#### Дополнительная задача Vue.js | Frontend - Fullstack

Реализовать описанную в задачах выше функциональность на фронте.

**Доп. задача 1**. В отдельной модалке реализовать через запрос на бек отображение истории пополнений и расходов, в
отдельной табе модалки показать общую пополненную сумму юзером, общую потраченную сумму, текущий баланс.

**Доп. задача 2**. История открытия бустерпаков (стоимость каждого, полученные из каждого открытия лайки).

**Доп. задача 3**. По связи безопасности бэка и фронта конкретных требований нет но Вы можете проявить креативность.

#### Удачи Вам! ;)
