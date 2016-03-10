
Integer Limit
=============

Since version 3.0.0 you can use integers that are greater than PHP_INT_MAX. In the background Money is using either
the extension BC Math or GMP to do calculations. This you need one of those libraries to able to perform unlimited
integer calculations.

Remember, because of the integer limit in PHP, you should inject a string that represents your huge amount. Please find
the example below.


.. code-block:: php
   
   <?php
   $hugeAmount = new Money('12345678901234567890', new Currency('USD'));