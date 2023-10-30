<?php
declare(strict_types=1);

defined('TYPO3') || die('Access denied.');

use Quizpalme\Tinyaccordion\Controller\SelectionController;
use Quizpalme\Tinyaccordion\Updates\SwitchableControllerActionsPluginUpdater;
use TYPO3\CMS\Extbase\Utility\ExtensionUtility;

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
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig(
         'mod {
			wizards.newContentElement.wizardItems.tinyaccordion {
			    header = Tinyaccordion
				elements {
					tinyaccordion_content {
                        iconIdentifier = ext-tinyaccordion-wizard-icon
						title = LLL:EXT:tinyaccordion/Resources/Private/Language/locallang_be.xml:tinyaccordion_title_content
						description = LLL:EXT:tinyaccordion/Resources/Private/Language/locallang_be.xml:tinyaccordion_plus_wiz_description
						tt_content_defValues {
							CType = list
							list_type = tinyaccordion_content
						}
					}
					tinyaccordion_contentui {
                        iconIdentifier = ext-tinyaccordion-wizard-icon
						title = LLL:EXT:tinyaccordion/Resources/Private/Language/locallang_be.xml:tinyaccordion_title_contentui
						description = LLL:EXT:tinyaccordion/Resources/Private/Language/locallang_be.xml:tinyaccordion_plus_wiz_description
						tt_content_defValues {
							CType = list
							list_type = tinyaccordion_contentui
						}
					}
					tinyaccordion_page {
                        iconIdentifier = ext-tinyaccordion-wizard-icon
						title = LLL:EXT:tinyaccordion/Resources/Private/Language/locallang_be.xml:tinyaccordion_title_page
						description = LLL:EXT:tinyaccordion/Resources/Private/Language/locallang_be.xml:tinyaccordion_plus_wiz_description
						tt_content_defValues {
							CType = list
							list_type = tinyaccordion_page
						}
					}
					tinyaccordion_pageui {
                        iconIdentifier = ext-tinyaccordion-wizard-icon
						title = LLL:EXT:tinyaccordion/Resources/Private/Language/locallang_be.xml:tinyaccordion_title_pageui
						description = LLL:EXT:tinyaccordion/Resources/Private/Language/locallang_be.xml:tinyaccordion_plus_wiz_description
						tt_content_defValues {
							CType = list
							list_type = tinyaccordion_pageui
						}
					}
					tinyaccordion_news {
                        iconIdentifier = ext-tinyaccordion-wizard-icon
						title = LLL:EXT:tinyaccordion/Resources/Private/Language/locallang_be.xml:tinyaccordion_title_news
						description = LLL:EXT:tinyaccordion/Resources/Private/Language/locallang_be.xml:tinyaccordion_plus_wiz_description
						tt_content_defValues {
							CType = list
							list_type = tinyaccordion_news
						}
					}
					tinyaccordion_newsui {
                        iconIdentifier = ext-tinyaccordion-wizard-icon
						title = LLL:EXT:tinyaccordion/Resources/Private/Language/locallang_be.xml:tinyaccordion_title_newsui
						description = LLL:EXT:tinyaccordion/Resources/Private/Language/locallang_be.xml:tinyaccordion_plus_wiz_description
						tt_content_defValues {
							CType = list
							list_type = tinyaccordion_newsui
						}
					}
				}
				show = *
			}
	     }'
        );
        
        // Register switchableControllerActions plugin migrator
        $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['ext/install']['update']['switchableControllerActionsPluginUpdaterTinyaccordion']
            = SwitchableControllerActionsPluginUpdater::class;
    }
);