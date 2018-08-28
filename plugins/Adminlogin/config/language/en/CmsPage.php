<?php
$config['CmsPage'] = [
    'Error' => [
        'page_name' => [
            'ER001' => 'Page name is required.',
            'ER002' => 'Page name can not be less than 2 characters.',
            'ER003' => 'Page name need to be between 2 to 100 characters long.',
            'ER004' => 'Page name already in use.',
            'ER005' => 'Only alphanumeric with -. allowed.',
        ],
        'title' => [
            'ER001' => 'Meta title is required.',
            'ER002' => 'Meta title can not be less than 2 characters.',
            'ER003' => 'Meta title need to be between 2 to 255 characters long.',
            'ER004' => 'Meta title already in use.',
            'ER005' => 'Only alphanumeric with -. allowed.',
        ],
        'page_slug' => [
            'ER001' => 'Page Slug is required.',
            'ER002' => 'Page Slug can not be less than 2 characters.',
            'ER003' => 'Page Slug need to be between 2 to 50 characters long.',
            'ER004' => 'Page Slug already in use.',
            'ER005' => 'Only alphanumeric with -. allowed.',
        ],
        'meta_keyword' => [
            'ER001' => 'Meta keyword is required.',
            'ER002' => 'Meta keyword can not be less than 2 characters.',
            'ER003' => 'Meta keyword must be less than 500 characters.',
            'ER004' => 'Meta keyword already in use.',
            'ER005' => 'Meta keyword must be between 2 to 500 characters long.',
        ],
        'meta_description' => [
            'ER001' => 'Meta description is required.',
            'ER002' => 'Meta description can not be less than 2 characters.',
            'ER003' => 'Meta description must be less than 500 characters.',
            'ER004' => 'Meta description already in use.',
            'ER005' => 'Meta description must be between 2 to 500 characters long.',
        ],
        'page_content' => [
            'ER001' => 'Page content is required.',
            'ER002' => 'Page content can not be less than 5 characters.',            
        ],
        'Other' => [
            'ER001' => 'The cms page could not be saved. Please, try again.',
            'ER002' => 'The cms page could not be updated. Please, try again.',
            'ER003' => 'No record exists with given parameters provided.',
            'ER004' => 'The cms page status could not be updated. Please, try again.',
            'ER005' => 'The cms page could not be deleted. Please, try again.',
        ],
    ],
    'Success' => [
        'SUC001' => 'The cms page has been saved.',
        'SUC002' => 'The cms page has been updated.',
        'SUC003' => 'The cms page activated.',
        'SUC004' => 'The cms page de-activated.',
        'SUC005' => 'The cms page has been deleted.',
    ],
];
?>