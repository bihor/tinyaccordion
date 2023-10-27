<?php
use TYPO3\CMS\Extbase\Utility\ExtensionUtility;

foreach (['content', 'contentui', 'page', 'pageui', 'news', 'newsui'] as $plugin) {
    $pluginName = ExtensionUtility::registerPlugin(
        'Tinyaccordion',
        ucfirst($plugin),
        'LLL:EXT:tinyaccordion/Resources/Private/Language/locallang_be.xml:tinyaccordion_title_' . $plugin,
        'EXT:tinyaccordion/Resources/Public/Icons/Extension.gif',
        'tinyaccordion'
    );

    $GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist'][$pluginName] = 'pi_flexform';
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue(
        $pluginName,
        'FILE:EXT:tinyaccordion/Configuration/FlexForms/flexform_pi1.xml'
    );

    $pluginSignature = 'tinyaccordion_' . $plugin;
    $GLOBALS['TCA']['tt_content']['types']['list']['subtypes_excludelist'][$pluginSignature] = 'recursive,select_key';
}