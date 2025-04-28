<?php
use TYPO3\CMS\Core\Imaging\IconProvider\BitmapIconProvider;

return [
    'ext-tinyaccordion-wizard-icon' => [
        'provider' => BitmapIconProvider::class,
        'source' => 'EXT:tinyaccordion/Resources/Public/Icons/ce_wiz.png'
    ],
    'ext-tinyaccordion-ext-icon' => [
        'provider' => BitmapIconProvider::class,
        'source' => 'EXT:tinyaccordion/Resources/Public/Icons/Extension.gif'
    ]
];
