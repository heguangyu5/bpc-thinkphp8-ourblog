<?php

return array(
    array(
        'id'          => 1,
        'email'       => 'bob@ourats.com',
        'reg_token'   => NULL,
        'username'    => 'Bob',
        'password'    => password_hash('123456', PASSWORD_DEFAULT),
        'create_date' => '2020-07-14 18:00:00',
        'update_date' => '2020-07-14 18:06:00'
    ),
    array(
        'id'          => 2,
        'email'       => 'joe@ourats.com',
        'reg_token'   => NULL,
        'username'    => 'Joe',
        'password'    => password_hash('654321', PASSWORD_DEFAULT),
        'create_date' => '2020-07-15 18:00:00',
        'update_date' => '2020-07-15 18:07:00'
    ),
    array(
        'id'          => 3,
        'email'       => 'jim@ourats.com',
        'reg_token'   => 'abcd1234abcd1234abcd1234abcd1234',
        'username'    => 'Jim',
        'password'    => password_hash('6543210', PASSWORD_DEFAULT),
        'create_date' => '2020-07-15 18:00:00',
        'update_date' => '2020-07-15 18:08:00'
    )
);
