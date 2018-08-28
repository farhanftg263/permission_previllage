<?php
$config['StateLicence'] = [
    'Error' => [
        'state' => [
            'ER001' => 'State is required.',
            'ER002' => 'State name can not be less than 2 characters.',
            'ER003' => 'State name need to be between 2 to 100 characters long.',
            'ER004' => 'This state already in use.',
            'ER005' => 'Only alphanumeric with -.@ allowed.',
        ],
        'name' => [
            'ER001' => 'Licence name is required.',
            'ER002' => 'Licence name can not be less than 2 characters.',
            'ER003' => 'Licence name need to be between 2 to 50 characters long.',
            'ER004' => 'Licence name already in use.',
            'ER005' => 'Only alphanumeric with -.!@ allowed.',
        ],
        'link' => [
            'ER001' => 'Licence link is required.',
            'ER002' => 'Licence link can not be less than 10 characters.',
            'ER003' => 'Licence link must be less than 255 characters.',            
            'ER004' => 'Licence link must be between 2 to 255 charasters long.',
            'ER005' => 'Please enter valid licence link.',
        ],
         'service_tax' => [
            'ER001' => 'Service tax is required.',
            'ER002' => 'Service tax can not be less than 1 characters.',            
            'ER003' => 'Please enter valid service tax.',
            'ER004' => 'The service tax must be between 0 and 100.', 
        ],        
        'Other' => [
            'ER001' => 'The state licence could not be saved. Please, try again.',
            'ER002' => 'The state licence could not be updated. Please, try again.',
            'ER003' => 'No record exists with given parameters provided.',
            'ER004' => 'The state licence status could not be updated. Please, try again.',
            'ER005' => 'The state licence could not be deleted. Please, try again.',
        ],
    ],
    'Success' => [
        'SUC001' => 'The state licence has been saved.',
        'SUC002' => 'The state licence has been updated.',
        'SUC003' => 'The state licence activated.',
        'SUC004' => 'The state licence deactivated.',
        'SUC005' => 'The state licence has been deleted.',
    ],
];
?>