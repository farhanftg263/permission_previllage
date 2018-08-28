<?php
$config['Role'] = [
    'RoleError' => [        
        'name'=>
            [
                'ER001' => 'Role name required.',
                'ER002' => 'Role Name need to be between 2 to 30 characters long !',
                'ER003' => 'Only alphanumeric with -. allowed !',
                'ER004' => 'Role name already in use !',
                'ER005' => 'Role Name need to be at least 2 characters long !',
                'ER006' => 'Role Name cannot be longer than 30 characters !',
            ],
        'description'=>
            [
                'ER001' => 'Description need to be between 2 to 100 characters long !',
                'ER002' => '',
            ],
    ],
    'Others' => [
        'Error' => [
            'ER001' => 'The role could not be saved. Please, try again !',
            'ER002' => 'Invalid parameter passed for role id !',
            'ER003' => 'Invalid parameter value passed for role id',
            'ER004' => 'The role could not be updated. Please, try again !',
            'ER005'  => 'The Role status could not be changed. Please, try again !',
            'ER006' => 'The Role could not be deleted. Please, try again !',
        ],
        'Success' => [
            'SUC001' => 'Role has been successfully added.',
            'SUC002' => 'Role has been successfully updated.',
            'SUC003' => 'Role has been in-active !',
            'SUC004' => 'Role has been active !',
            'SUC005' => 'The Role has been deleted !',
        ]
    ],
];
?>