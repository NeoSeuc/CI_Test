/** Сколько денег потрачено на бустерпаки по каждому паку отдельно, почасовая выборка.
  Также нужно показать, сколько получили юзеры из каждого пока в эквиваленте $.
  Выборка должна быть за последние 30 дней. */
select
    HOUR(a.time_created) as time,
    b.id as boosterpack_id,
    concat('pack for a ',b.price, ' $') as boosterpack,
    count(a.object_id) * b.price as money_earned,
    SUM(a.amount) as users_get_likes
from analytics a
    join boosterpack b ON a.object_id = b.id
where a.object = 'boosterpack' AND a.action = 'open'
group by HOUR(a.time_created), a.object_id
order by time asc

/** Выборка по юзеру, на сколько он пополнил баланс и сколько получил лайков за все время.
  Текущий остаток баланса в $ и лайков на счету */
select
    u.id,
    u.wallet_total_refilled,
    sum(a.amount) as likes_for_all_time,
    u.wallet_balance,
    u.likes_balance
from user u
         join analytics a ON u.id = a.user_id
where a.object = 'boosterpack' and a.action = 'open'
group by u.id