
Getting started
===============

All amounts are represented in the smallest unit (eg. cents), so USD 5.00 is written as

.. code-block:: php
   
   <?php
   $fiver = new Money(500, new Currency('USD'));
   // or shorter:
   $fiver = Money::USD(500);

Installation
------------

Install the library using composer. Add the following to your composer.json:

.. code-block:: json

{
    "require": {
        "mathiasverraes/money": "~3.0"
    },
}

