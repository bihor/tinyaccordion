<?php
defined('TYPO3_MODE') || die('Access denied.');

call_user_func(
    function () {
        \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
            'Tinyaccordion',
            'Pi1',
            [
                \Quizpalme\Tinyaccordion\Controller\SelectionController::class => 'content, content_ui_accordion, news, news_ui_accordion, camaliga, camaliga_ui_accordion, page, page_ui_accordion'
            ],
            [
                \Quizpalme\Tinyaccordion\Controller\SelectionController::class => ''
            ]
        );

        // wizards
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig(
         'mod {
			wizards.newContentElement.wizardItems.plugins {
				elements {
					tinyaccordion {
                        iconIdentifier = ext-tinyaccordion-wizard-icon
						title = LLL:EXT:tinyaccordion/Resources/Private/Language/locallang_be.xml:tinyaccordion_title
						description = LLL:EXT:tinyaccordion/Resources/Private/Language/locallang_be.xml:tinyaccordion_plus_wiz_description
						tt_content_defValues {
							CType = list
							list_type = tinyaccordion_pi1
						}
					}
				}
				show = *
			}
	     }'
        );
    }
);

if (TYPO3_MODE === 'BE') {
    /** @var \TYPO3\CMS\Core\Imaging\IconRegistry $iconRegistry */
    $iconRegistry = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Imaging\IconRegistry::class);
    $iconRegistry->registerIcon(
        'ext-tinyaccordion-wizard-icon',
        \TYPO3\CMS\Core\Imaging\IconProvider\BitmapIconProvider::class,
        ['source' => 'EXT:tinyaccordion/Resources/Public/Icons/ce_wiz.png']
    );
}