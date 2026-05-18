<?php

return [

    'disk' => env('UPLOAD_DISK', env('FILESYSTEM_DISK', 'public')),

    'paths' => [
        'restaurant_logos' => 'uploads/restaurants/logos',
        'restaurant_covers' => 'uploads/restaurants/covers',
        'product_images' => 'uploads/products/images',
        'gallery_images' => 'uploads/galleries/images',
        'banner_images' => 'uploads/banners/images',
        'setting_images' => 'uploads/settings/images',
        'admin_profiles' => 'uploads/profiles/admins',
        'client_profiles' => 'uploads/profiles/clients',
        'user_profiles' => 'uploads/profiles/users',
    ],

    'max_size' => [
        'default' => 2048,
        'restaurant_logos' => 2048,
        'restaurant_covers' => 4096,
        'product_images' => 3072,
        'gallery_images' => 4096,
        'banner_images' => 4096,
        'setting_images' => 2048,
        'admin_profiles' => 2048,
        'client_profiles' => 2048,
        'user_profiles' => 2048,
    ],

    'allowed_image_mimes' => [
        'jpg',
        'jpeg',
        'png',
        'webp',
    ],

    'validation' => [
        'image_rule' => 'image',
    ],

];
