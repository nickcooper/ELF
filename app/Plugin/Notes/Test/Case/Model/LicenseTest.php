<?php
App::uses('Note', 'Notes.Model');

class NoteTest extends CakeTestCase 
{
    public function setUp() 
    {
        parent::setUp();
        
        $this->Firm = ClassRegistry::init('Note');
    }

    public function testAdd() 
    {
        $this->assertEquals(1, 1);
    }
}