<?php
$config['GlobalSetting'] = [
    'Error' => [
        'reference' => [
            'ER001' => 'Reference is required.',
            'ER002' => 'Reference can not be less than 2 characters.',
            'ER003' => 'Reference need to be between 2 to 100 characters long.',
            'ER004' => 'Reference already in use.',
            'ER005' => 'Only alphanumeric with -.@ allowed.',
        ],
        'datatype' => [
            'ER001' => 'Type is required.',
            'ER002' => 'Type can not be less than 2 characters.',
            'ER003' => 'Type need to be between 2 to 50 characters long.',
            'ER004' => 'Type already in use.',
            'ER005' => 'Only alphanumeric with -.!@ allowed.',
        ],
        'value' => [
            'ER001' => 'Value is required.',
            'ER002' => 'Value can not be less than 1 characters.',
            'ER003' => 'Value must be less than 100 characters.',            
            'ER004' => 'Value must be between 2 to 100 charasters long.',
            'ER005' => 'Please enter valid value.',
        ],
               
        'Other' => [
            'ER001' => 'The global setting could not be saved. Please, try again.',
            'ER002' => 'The global setting could not be updated. Please, try again.',
            'ER003' => 'No record exists with given parameters provided.',
            'ER004' => 'The global setting status could not be updated. Please, try again.',
            'ER005' => 'The global setting could not be deleted. Please, try again.',
        ],
    ],
    'Success' => [
        'SUC001' => 'The global setting has been saved.',
        'SUC002' => 'The global setting has been updated.',
        'SUC003' => 'The global setting activated.',
        'SUC004' => 'The global setting deactivated.',
        'SUC005' => 'The global setting has been deleted.',
    ],
];
?>