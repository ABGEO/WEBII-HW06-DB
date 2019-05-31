<?php

require_once __DIR__ . '/Services/autoload.php';

use Database\DataManager;

$data_manager = new DataManager('webdb', 'root', 'toor');

$where = [
    [
        'logic_operator' => '',
        'column' => 'name',
        'operator' => '=',
        'value' => 'temuri',
    ],
    [
        'logic_operator' => 'OR',
        'column' => 'username',
        'operator' => '=',
        'value' => 'username',
    ],
];

$order = [
    'surname' => 'desc',
    'name' => 'asc',
];

$data = $data_manager->select(['name', 'username'], 'users', $where, $order, 5);

var_dump($data);