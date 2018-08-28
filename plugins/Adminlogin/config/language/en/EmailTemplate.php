<?php
$config['EmailTemplate'] = [
    'Error' => [
        'slug' => [
            'ER001' => 'Slug is required.',
            'ER002' => 'Slug can not be less than 2 characters.',
            'ER003' => 'Slug need to be between 2 to 100 characters long.',
            'ER004' => 'Slug already in use.',
            'ER005' => 'Only alphanumeric with -.@ allowed.',
        ],
        'title' => [
            'ER001' => 'Title is required.',
            'ER002' => 'Title can not be less than 2 characters.',
            'ER003' => 'Title need to be between 2 to 255 characters long.',
            'ER004' => 'Title already in use.',
            'ER005' => 'Only alphanumeric with -.!@ allowed.',
        ],
        'subject' => [
            'ER001' => 'Subject is required.',
            'ER002' => 'Subject can not be less than 2 characters.',
            'ER003' => 'Subject must be less than 255 characters.',
            'ER004' => 'Subject already in use.',
            'ER005' => 'Subject must be between 2 to 255 characters long.',
        ],
        
        'message' => [
            'ER001' => 'Message is required.',
            'ER002' => 'Message can not be less than 5 characters.',            
        ],
        'Other' => [
            'ER001' => 'The email template could not be saved. Please, try again.',
            'ER002' => 'The email template could not be updated. Please, try again.',
            'ER003' => 'No record exists with given parameters provided.',
            'ER004' => 'The email template status could not be updated. Please, try again.',
            'ER005' => 'The email template could not be deleted. Please, try again.',
        ],
    ],
    'Success' => [
        'SUC001' => 'The email template has been saved.',
        'SUC002' => 'The email template has been updated.',
        'SUC003' => 'The email template activated.',
        'SUC004' => 'The email template de-activated.',
        'SUC005' => 'The email template has been deleted.',
    ],
];
?>