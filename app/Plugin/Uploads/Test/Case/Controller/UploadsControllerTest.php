<?php
class UploadsControllerTest extends ControllerTestCase
{
    public function testAdd()
    {
        $result = $this->testAction('/uploads/uploads/add');
        debug($result);
    }
}