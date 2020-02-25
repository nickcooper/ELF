<?php
class FeeFixture extends CakeTestFixture 
{
    public $import = array('model' => 'Payments.PaymentType', 'records' => true);
    public $fields = array(
        'id' => array('type' => 'integer', 'key' => 'primary'),
        'label' => array('type' => 'string', 'null' => true, 'length' => 45),
        'created' => 'datetime',
        'modified' => 'datetime'
    );
    
    public $records = array(
        array(
            'id' => 1,
            'label' => 'test',
        ),
    );
}