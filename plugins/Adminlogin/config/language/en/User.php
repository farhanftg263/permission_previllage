<?php
$config['User'] = [
    'Error' => [
        'user_group_id' => [
            'ER001' => 'User group is required.',
        ],
        'nickname' => [
            'ER001' => 'Name is required.',
            'ER002' => 'Name should be between 2 to 25 characters long',
            'ER003' => 'Invalid name.',
            'ER005' => 'Invalid username.',
        ],
        'email' =>
            [
            'ER001' => 'Email is required.',
            'ER002' => 'Email must be valid.',
            'ER003' => 'Email must have at least 7 characters long.',
            'ER004' => 'Email must not exceed 50 characters.',
            'ER005' => 'Email already in use.',
            'ER006' => 'Email already in use!',
        ],
        'phone' => [
            'ER001' => 'Contact number is required!',
            'ER002' => 'Invalid Contact Number!',
            'ER004' => 'New password and confirm password did not match!',
            'ER005' => 'Confirm new password need to be at least 8 characters long!',
            'ER006' => 'Confirm new password cannot be longer than 30 characters!',
            'ER007' => 'New password and confirm password did not match!',
        ],
        'status' => [
            'ER001' => 'Status must have boolean value.',
        ],
        'Other' => [
            'ER001' => 'The user could not be added. Please, try again.',
            'ER002' => 'The user could not be updated. Please, try again.',
            'ER003' => 'No record exists with given parameters provided.',
            'ER004' => 'The user could not be deleted. Please, try again.',
            'ER005' => 'The user could not be changed. Please, try again.',
            'ER006' => 'Invalid username or password, try again.',
            'ER007' => 'Email address not registered with us.',
            'ER008' => 'Unable to sent verification link on your e-mail address.',
            'ER009' => 'You are not authorize to access this location.',
            'ER010' => 'Invalid token provided.',
            'ER011'  => 'Unable to reset password.',
            'ER012'  => 'Unable to change password.',
        ],
    ],
    'Success' => [
        'SUC001' => 'The user has been added.',
        'SUC002' => 'The user has been updated.',
        'SUC003' => 'The user has been deleted.',
        'SUC004' => 'The user activated.',
        'SUC005' => 'The user de-activated.',
        'SUC006' => 'You are successfully logged out.',
        'SUC007' => 'Please check your e-mail to reset your password.',
        'SUC008' => 'Your password has been successfully reset.',
        'SUC009' => 'Your password has been successfully changed.',
    ],
];
?>
