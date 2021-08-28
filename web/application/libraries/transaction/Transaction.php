<?php

use Model\Analytics_model;

//I don't know why do we need this class if we can use directly Analytics_model for our purposes
//But this class is in the Task 6 so I created it
class Transaction
{
    /**
     * @param int $user_id
     * @param string $object
     * @param string $action
     * @param int $object_id
     * @param int $amount
     * @return void
     */
    public function log(int $user_id, string $object, string $action, int $object_id, int $amount): void
    {
         Analytics_model::create(
            [
                'user_id' => $user_id,
                'object' => $object,
                'action' => $action,
                'object_id' => $object_id,
                'amount' => $amount,
            ]
        );
    }
}