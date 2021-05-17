

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


Reference
^^^^^^^^^

Short reference of TypoScript options for plugin.*tx\_tinyaccordion (there are some more settings available):

.. ### BEGIN~OF~TABLE ###

.. container:: table-row

   Property
         view.templateRootPath or view.templateRootPaths

   Data type
         string

   Description
         Path to the template file. Note: the templates must be in a subfolder
         named “Selection”!

   Default
         EXT:tinyaccordion/Resources/Private/Templates/


.. ###### END~OF~TABLE ######

Example::

  plugin.tx_tinyaccordion {
	view {
		templateRootPaths {
			0 = EXT:tinyaccordion/Resources/Private/Templates/
			1 = fileadmin/Resources/tinyaccordion/templates/
		}
	}
  }


If you want to change the styles: copy them from the TypoScript-file
(Configuration/TypoScript/setup.txt) to your own css-file and remove
the default-styles::

    plugin.tx_tinyaccordion._CSS_DEFAULT_STYLE >

Note: the default JavaScript-file is included like this in the templates via a new ViewHelper::

    {namespace tiny=Quizpalme\Tinyaccordion\ViewHelpers}
    <tiny:addPublicResources path="EXT:tinyaccordion/Resources/Public/Scripts/packed.js"></tiny:addPublicResources>

If this doesn´t work or if you want to include another JavaScript-
file, copy the JavaScript-file to your fileadmin-folder and remove the
default JavaScript and include your own JavaScript via TypoScript like this::

    page.includeJS.tx_tinyaccordion = fileadmin/templates/Scripts/tinyaccordion.js

There are more settings::

   plugin.tx_tinyaccordion.settings.flexform.*

They are used e.g. in the News.html and Camaliga.html template.


Examples
""""""""

Here you can see how you can use another HTML-file
(Selection/News.html or Selection/Content.html in that folder).
Changes the default template folder in the Constants::

    plugin.tx_tinyaccordion.view.templateRootPath = fileadmin/template/tinyaccordion/

This second example shows you, how you can use the jQuery UI Accordion
instead of the TinyAccordion. Remove the default styles::

    plugin.tx_tinyaccordion._CSS_DEFAULT_STYLE >

If you want to edit the template, copy the example-file (Resources/Private/Templates/Selection/News-
ui-accordion.html) to your fileadmin-folder in a folder named
“Selection”, edit it and include it like this in TypoScript Setup::

    plugin.tx_tinyaccordion.view.templateRootPaths.1 = fileadmin/template/files/

Note: the path to the file is then like this: fileadmin/template/files/Selection/News-ui-accordion.html

Note furthermore: you need to include jQuery UI Accordion by your own.
