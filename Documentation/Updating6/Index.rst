.. include:: /Includes.rst.txt

Updating to ver. 6.0.0
----------------------

If you are updating from a version < 6.0.0, you must change your custom template-file or your TypoScript, because this TypoScript line was removed::

    page.includeJS.tx_tinyaccordion = {$plugin.tx_tinyaccordion.settings.jsFile}

You can add that line to your TypoScript, if you do not use jQuery UI, or you can add this 2 lines at the beginning of your custom template::

    {namespace tiny=Quizpalme\Tinyaccordion\ViewHelpers}
    <tiny:addPublicResources path="EXT:tinyaccordion/Resources/Public/Scripts/packed.js"></tiny:addPublicResources>
