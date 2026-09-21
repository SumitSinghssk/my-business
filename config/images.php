<?php

/*
|--------------------------------------------------------------------------
| Image presets
|--------------------------------------------------------------------------
|
| One definition per kind of uploaded image. The admin upload component
| crops to `ratio`, the server resizes every upload to exactly
| `width` x `height`, and the website displays the image with the same
| `aspect` class, so what the admin crops is exactly what visitors see.
|
| `variants` are extra, smaller widths saved next to the image (name-640.webp)
| and offered to browsers through srcset, so cards don't download the full size.
|
| Changing a size here? Run `php artisan images:normalize` afterwards to
| bring already-uploaded images in line.
|
*/

return [

    'format' => 'webp',   // webp | jpg
    'quality' => 82,

    'presets' => [

        'blog' => [
            'label' => 'Blog featured image',
            'width' => 1600,
            'height' => 1000,
            'ratio' => '16:10',
            'aspect' => 'aspect-16/10',
            'directory' => 'blogs',
            'variants' => [640, 960], // smaller copies for cards and phones (srcset)
        ],

        'service' => [
            'label' => 'Service image',
            'width' => 1600,
            'height' => 1000,
            'ratio' => '16:10',
            'aspect' => 'aspect-16/10',
            'directory' => 'services',
            'variants' => [640, 960], // smaller copies for cards and phones (srcset)
        ],

        'project' => [
            'label' => 'Project image',
            'width' => 1600,
            'height' => 1000,
            'ratio' => '16:10',
            'aspect' => 'aspect-16/10',
            'directory' => 'projects',
            'variants' => [640, 960], // smaller copies for cards and phones (srcset)
        ],

        'page' => [
            'label' => 'Page banner',
            'width' => 1600,
            'height' => 600,
            'ratio' => '8:3',
            'aspect' => 'aspect-8/3',
            'directory' => 'pages',
        ],

        'category' => [
            'label' => 'Category image',
            'width' => 1200,
            'height' => 750,
            'ratio' => '16:10',
            'aspect' => 'aspect-16/10',
            'directory' => 'blog-categories',
        ],

        'og' => [
            'label' => 'Social share image',
            'width' => 1200,
            'height' => 630,
            'ratio' => '1.91:1',
            'aspect' => 'aspect-[40/21]',
            'directory' => 'seo',
        ],

        'avatar' => [
            'label' => 'Profile photo',
            'width' => 400,
            'height' => 400,
            'ratio' => '1:1',
            'aspect' => 'aspect-square',
            'directory' => 'avatars',
        ],

    ],

];
