<?php
$config['Species'] = [
    'Error' => [
        'title' => [
            'ER001' => 'Name is required.',
            'ER002' => 'Name can not be less than 2 characters.',
            'ER003' => 'Name need to be between 2 to 255 characters long.',
            'ER004' => 'Name already in use.',
            'ER005' => 'Only alphanumeric with -.!@# allowed.',
        ],
        'image' => [
            'ER001' => 'Image is required.',
            'ER002' => 'Only jpeg, png and gif images are allowed',
        ],       
        
        'Other' => [
            'ER001' => 'The species could not be saved. Please, try again.',
            'ER002' => 'The species could not be updated. Please, try again.',
            'ER003' => 'No record exists with given parameters provided.',
            'ER004' => 'The species status could not be updated. Please, try again.',
            'ER005' => 'The species could not be deleted. Please, try again.',
        ],
    ],
    'Success' => [
        'SUC001' => 'The species has been saved.',
        'SUC002' => 'The species has been updated.',
        'SUC003' => 'The species activated.',
        'SUC004' => 'The species de-activated.',
        'SUC005' => 'The species has been deleted.',
    ],
];
?>