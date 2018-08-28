<?php
$config['AdminUser'] = [
    'Error' => [
        'user_group_id' => [
            'ER001' => 'User group is required.',
        ],
        'username' => [
            'ER001' => 'Username is required.',
            'ER002' => 'Username can not be less than 5 charecters.',
            'ER003' => 'Username need to be between 5 to 30 characters long',
            'ER004' => 'Username already in use.',
            'ER005' => 'Invalid username.',
        ],
        'first_name' => 
            [
                'ER001' => 'First name required.',
                'ER002' => 'First Name need to be between 2 to 30 characters long !',
                'ER003' => 'Only alphanumeric with -. allowed !',
                'ER004' => 'First Name need to be at least 2 characters long !',
                'ER005' => 'First Name cannot be longer than 30 characters !',
                'ER006' => '',
            ],
        'last_name' => 
            [
                'ER001' => 'Last name required.',
                'ER002' => 'Last Name need to be between 2 to 30 characters long !',
                'ER003' => 'Only alphanumeric with -. allowed !',
                'ER004' => 'Last Name need to be at least 2 characters long !',
                'ER005' => 'Last Name cannot be longer than 30 characters !',
                'ER006' => '',
            ],
        'password' =>
            [
            'ER001' => 'Password is required.',
            'ER002' => 'Password need to be at least 5 characters long.',
            'ER003' => 'Password need to be between 5 to 30 characters long.',
            'ER004' => 'Password and confirm password did not match!',
        ],     
        'about_me' => 
            [
                'ER001' => 'About Me need to be between 2 to 255 characters long !',
                'ER002' => 'About Me need to be at least 2 characters long !',
                'ER003' => 'About Me cannot be longer than 255 characters !',
            ],
        'confirm_password' =>[
            'ER001' => 'Confirm new password is required!',
            'ER002' => 'Confirm password need to be at least 5 characters long!',
            'ER003' => 'Confirm password cannot be longer than 30 characters!',
            'ER004' => 'Password and confirm password does not match!',
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
        'role_id' => 
            [
                'ER001' => 'Role is required !',
                'ER002' => '',
            ],
        'current_password' => [
            'ER001' => 'Current password is required!',
            'ER002' => 'Spaces not allowed in password !',
            'ER003' => 'Current password need to be between 5 to 30 characters long!',
            'ER004' => 'Wrong current password!',
            'ER005' => 'Current password need to be at least 5 characters long!',
            'ER006' => 'Current password cannot be longer than 30 characters!',
        ],
        'new_password' =>[
            'ER001' => 'New password is required!',
            'ER002' => 'The password should have a minimum of 8 characters with a mix of alphanumeric and special characters!',
            'ER003' => 'New password need to be between 5 to 30 characters long!',
            'ER004' => 'New password need to be at least 8 characters long!',
            'ER005' => 'New password cannot be longer than 30 characters!',
            'ER006' => 'The passwords does not match!',
        ],
        'confirm_new_password' => [
            'ER001' => 'Confirm new password is required!',
            'ER002' => 'The password should have a minimum of 8 characters with a mix of alphanumeric and special characters!',
            'ER003' => 'Confirm new password need to be between 5 to 30 characters long!',
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
