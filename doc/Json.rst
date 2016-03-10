
Json
====

If you want to serialize a money object into a JSON, you can just use the PHP method json_encode for that. Please find
below example of how to achieve this.

.. code-block:: php
   
    <?php
    $money = Money::USD(350);
    $json = json_encode($money);
    echo $json; //prints '{"amount":"350","currency":"USD"}'