

.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. ==================================================
.. DEFINE SOME TEXTROLES
.. --------------------------------------------------
.. role::   underline
.. role::   typoscript(code)
.. role::   ts(typoscript)
   :class:  typoscript
.. role::   php(code)


ChangeLog
---------

.. ### BEGIN~OF~TABLE ###

.. container:: table-row

   Version
         0.1.0

   Changes
         Initial upload.


.. container:: table-row

   Version
         1.0.0

   Changes
         New rewritten in Extbase. See chapter Updating! You must configure
         everything new!


.. container:: table-row

   Version
         1.1.0

   Changes
         Uid of the content element added to the UL-ID. Code cleaning.


.. container:: table-row

   Version
         1.1.2

   Changes
         Titles of content elements are now hidden by default.


.. container:: table-row

   Version
         2.0.0

   Changes
         FlexForms and templates extended. Maybe you need to save your plugin-
         settings again if you use the templates from typo3conf...


.. container:: table-row

   Version
         2.1.1

   Changes
         The extension can be configured to use the extension key from version
         0.1.0.


.. container:: table-row

   Version
         2.2.0

   Changes
         New documentation format.

         The UI Accordion template can be selected directly without moving the file to fileadmin.

         Camaliga-elements can be used now too.


.. container:: table-row

   Version
         2.2.1

   Changes
         HTML5 problem solved.


.. container:: table-row

   Version
         3.0.0

   Changes
         Moved to namespaces. Now compatible to TYPO3 7. Note: you need to change the TypoScript in TYPO3 7.6.
         See chapter Configuration.

.. container:: table-row

   Version
         3.0.1

   Changes
         TYPO3 7 related bugfix.

.. container:: table-row

   Version
         3.0.2

   Changes
         TypoScript for TYPO3 7 added.

         Bugfix: {uid} was empty in TYPO3 7.

.. container:: table-row

   Version
         3.0.3

   Changes
         sys_language_uid will now not be ignored.

.. container:: table-row

   Version
         3.0.5

   Changes
         Actions pages, pages_ui_accordion and content_ui_accordion added.

         Sorting option added: first sort by pid (Record Storage Page).

         New variables: {pids} and {element.pid}.

.. container:: table-row

   Version
         4.0.0

   Changes
         Redesigned for TYPO3 7. TYPO3 6 compatibilty removed. You need to run the update script in the extension manager after installing.

         Extension manager configuration and TypoScript for TYPO3 6 removed. The extension key changed.

         New templates: Pages and templates for UI accordion.

         Several new FlexForm settings.

.. container:: table-row

   Version
         5.0.0

   Changes
         Rewritten for TYPO3 8 by Ingo Pfennigstorf.

         The Layout Default.html removed because of a major bug in TYPO3 8. It is not allways possible to use a layout-file called Default.html in TYPO3 8.

.. container:: table-row

   Version
         6.0.0

   Changes
         Removed the includeJS: page.includeJS.tx_tinyaccordion.
         
         The JavaScript-file for a TinyAccordion is now included in the template by a ViewHelper. If you use custom templates, you need to add the ViewHelper to your template!

.. container:: table-row

   Version
         6.1.0

   Changes
         Runs now with TYPO3 9, if the extension typo3db_legacy is installed.

.. container:: table-row

   Version
         7.0.0

   Changes
         Old update-script removed.
         
         tt_news-support replaced by news-support (maybe not working correct).
         
         Runs now with TYPO3 9 and 10; typo3db_legacy not needed anymore.

.. container:: table-row

   Version
         7.1.0

   Changes
         Runs now with composer too.

         Documentation update.

.. container:: table-row

   Version
         7.2.1

   Changes
         Runs now with TYPO3 10 and 11.3.

         Can display now table elements too.

         Bugfix: news template.

.. container:: table-row

   Version
         7.3.0

   Changes
         Runs now with TYPO3 11.5 too.

         Directory Scripts renamed to JavaScript.

         Replacement of the Viewhelper tiny:addPublicResources. It is now deprecated. Use f:asset.script instead.

.. container:: table-row

   Version
         8.0.0

   Changes
        Breaking: all plugins must be changed via an update-script (in the install-tool)!

        Breaking: the Viewhelper cam:addPublicResources was removed.

        Breaking: removed the templates for Camaliga (can be solved with Camaliga).

.. ###### END~OF~TABLE ######
