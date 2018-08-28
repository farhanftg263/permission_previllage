<?php
$config['StateServiceTax'] = [
    'Error' => [
        'state_id' => [
            'ER001' => 'State is required.',            
            'ER004' => 'This state already exist.',
            
        ],
        
        'service_tax' => [
            'ER001' => 'Service tax is required.',
            'ER002' => 'Service tax can not be less than 1 characters.',            
            'ER003' => 'Please enter valid service tax.',
            'ER004' => 'The service tax must be between 0 and 100.', 
        ],        
        'Other' => [
            'ER001' => 'The state service tax could not be saved. Please, try again.',
            'ER002' => 'The state service tax could not be updated. Please, try again.',
            'ER003' => 'No record exists with given parameters provided.',
            'ER004' => 'The state service tax status could not be updated. Please, try again.',
            'ER005' => 'The state service tax could not be deleted. Please, try again.',
        ],
    ],
    'Success' => [
        'SUC001' => 'The state service tax has been saved.',
        'SUC002' => 'The state service tax has been updated.',
        'SUC003' => 'The state service tax activated.',
        'SUC004' => 'The state service tax deactivated.',
        'SUC005' => 'The state service tax has been deleted.',
    ],
];
?>