<?php
declare(strict_types=1);

use Quizpalme\Tinyaccordion\Controller\SelectionController;
use TYPO3\CMS\Extbase\Utility\ExtensionUtility;
use TYPO3\CMS\Core\Information\Typo3Version;

defined('TYPO3') || die('Access denied.');

call_user_func(
    function () {
        ExtensionUtility::configurePlugin(
            'Tinyaccordion',
            'Content',
            [
                SelectionController::class => 'content'
            ],
            [
                SelectionController::class => ''
            ]
        );
        ExtensionUtility::configurePlugin(
            'Tinyaccordion',
            'Contentui',
            [
                SelectionController::class => 'content_ui_accordion'
            ],
            [
                SelectionController::class => ''
            ]
        );
        ExtensionUtility::configurePlugin(
            'Tinyaccordion',
            'News',
            [
                SelectionController::class => 'news'
            ],
            [
                SelectionController::class => ''
            ]
        );
        ExtensionUtility::configurePlugin(
            'Tinyaccordion',
            'Newsui',
            [
                SelectionController::class => 'news_ui_accordion'
            ],
            [
                SelectionController::class => ''
            ]
        );
        ExtensionUtility::configurePlugin(
            'Tinyaccordion',
            'Page',
            [
                SelectionController::class => 'pages'
            ],
            [
                SelectionController::class => ''
            ]
        );
        ExtensionUtility::configurePlugin(
            'Tinyaccordion',
            'Pageui',
            [
                SelectionController::class => 'pages_ui_accordion'
            ],
            [
                SelectionController::class => ''
            ]
        );

        // wizards
        if ((new Typo3Version())->getMajorVersion() < 13) {
            // @extensionScannerIgnoreLine
            \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig('<INCLUDE_TYPOSCRIPT: source="FILE:EXT:tinyaccordion/Configuration/TSconfig/ContentElementWizard.tsconfig">');
        }
    }
);