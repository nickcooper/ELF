<?php
class ReportsControllerTest extends ControllerTestCase
{
    public function testIndex()
    {
        $result = $this->testAction('/reports/reports/index');
        debug($result);
    }
}