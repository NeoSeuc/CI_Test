<?php

use System\Emerald\Emerald_enum;
//I would be use constants in Analytics_model instead of this
//I think it's useless layer of data
class Transaction_type extends Emerald_enum
{
    const OBJECT_COMMENT = 'comment';
    const OBJECT_POST = 'post';
    const OBJECT_BOOSTERPACK = 'boosterpack';
    const OBJECT_MONEY = 'money';

    const ACTION_LIKE = 'like';
    const ACTION_ADD = 'add';
    const ACTION_OPEN = 'open';
}