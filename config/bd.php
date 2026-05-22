<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Bangladesh Administrative Divisions & Districts
    |--------------------------------------------------------------------------
    */

    'divisions' => [
        'Dhaka', 'Chittagong', 'Rajshahi', 'Khulna',
        'Barisal', 'Sylhet', 'Rangpur', 'Mymensingh',
    ],

    'districts' => [
        'Dhaka'      => ['Dhaka','Gazipur','Narayanganj','Narsingdi','Manikganj','Munshiganj','Rajbari','Faridpur','Gopalganj','Madaripur','Shariatpur','Kishoreganj','Tangail'],
        'Chittagong' => ['Chittagong','Cox\'s Bazar','Rangamati','Bandarban','Khagrachhari','Feni','Noakhali','Lakshmipur','Comilla','Chandpur','Brahmanbaria'],
        'Rajshahi'   => ['Rajshahi','Chapai Nawabganj','Natore','Pabna','Sirajganj','Bogura','Joypurhat','Naogaon'],
        'Khulna'     => ['Khulna','Jessore','Satkhira','Bagerhat','Narail','Magura','Jhenaidah','Chuadanga','Kushtia','Meherpur'],
        'Barisal'    => ['Barisal','Bhola','Patuakhali','Pirojpur','Jhalokati','Barguna'],
        'Sylhet'     => ['Sylhet','Moulvibazar','Habiganj','Sunamganj'],
        'Rangpur'    => ['Rangpur','Dinajpur','Thakurgaon','Panchagarh','Nilphamari','Lalmonirhat','Kurigram','Gaibandha'],
        'Mymensingh' => ['Mymensingh','Netrokona','Jamalpur','Sherpur'],
    ],

    /*
    |--------------------------------------------------------------------------
    | Payment Methods available in Bangladesh
    |--------------------------------------------------------------------------
    */

    'payment_methods' => [
        'cod'         => ['label' => 'Cash on Delivery', 'icon' => 'money-bill', 'color' => '#16a34a'],
        'bkash'       => ['label' => 'bKash',            'icon' => 'mobile-alt', 'color' => '#E2136E'],
        'nagad'       => ['label' => 'Nagad',            'icon' => 'mobile-alt', 'color' => '#F7941D'],
        'rocket'      => ['label' => 'Rocket (DBBL)',    'icon' => 'mobile-alt', 'color' => '#8B008B'],
        'ssl_commerz' => ['label' => 'Card / Net Banking','icon' => 'credit-card','color' => '#1a56db'],
        'bank'        => ['label' => 'Bank Transfer',    'icon' => 'university', 'color' => '#374151'],
    ],

    /*
    |--------------------------------------------------------------------------
    | Popular Drug Categories in BD
    |--------------------------------------------------------------------------
    */

    'default_categories' => [
        ['name' => 'Medicine',           'slug' => 'medicine',           'icon' => '💊'],
        ['name' => 'Vitamins & Supplements','slug' => 'vitamins',        'icon' => '🧪'],
        ['name' => 'Baby & Mother',      'slug' => 'baby-mother',        'icon' => '👶'],
        ['name' => 'Diabetes Care',      'slug' => 'diabetes',           'icon' => '🩸'],
        ['name' => 'Heart & Blood',      'slug' => 'heart-blood',        'icon' => '❤️'],
        ['name' => 'Skin Care',          'slug' => 'skin-care',          'icon' => '🧴'],
        ['name' => 'Eye & Ear',          'slug' => 'eye-ear',            'icon' => '👁️'],
        ['name' => 'Dental Care',        'slug' => 'dental',             'icon' => '🦷'],
        ['name' => 'Fitness & Nutrition','slug' => 'fitness',            'icon' => '💪'],
        ['name' => 'Herbal & Natural',   'slug' => 'herbal',             'icon' => '🌿'],
        ['name' => 'Medical Devices',    'slug' => 'devices',            'icon' => '🩺'],
        ['name' => 'Personal Care',      'slug' => 'personal-care',      'icon' => '🛁'],
    ],

    /*
    |--------------------------------------------------------------------------
    | SMS Templates (Bangla)
    |--------------------------------------------------------------------------
    */

    'sms_templates' => [
        'order_confirm'    => 'প্রিয় {name}, আপনার অর্ডার #{order} নিশ্চিত হয়েছে। মোট: ৳{total}। ট্র্যাক: ousodhaloy.com/track - Ousodhaloy',
        'order_shipped'    => 'আপনার অর্ডার #{order} পাঠানো হয়েছে। ট্র্যাক করুন: ousodhaloy.com/track - Ousodhaloy',
        'order_delivered'  => 'আপনার অর্ডার #{order} ডেলিভারি হয়েছে। ধন্যবাদ! - Ousodhaloy',
        'order_cancelled'  => 'আপনার অর্ডার #{order} বাতিল হয়েছে। - Ousodhaloy',
        'otp'              => 'আপনার Ousodhaloy OTP: {code}। {minutes} মিনিটে মেয়াদ শেষ।',
    ],
];
