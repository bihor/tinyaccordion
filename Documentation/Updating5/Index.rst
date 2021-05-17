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


Updating to ver. 5.0.0
-------------------------------

If you are updating from a version < 5.0.0, you must change your custom template-file!

The file Layouts/Default.html was removed because of a major bug in TYPO3 8.
It is not possible to use this file even with TYPO3 8.7.10.

Got to your template in file fileadmin-folder and replace this lines::

  <f:layout name="Default" />

  <f:section name="main">

with::

  <div class="tx-tinyaccordion-pi1">

and::

  </f:section>

with::

  </div>

