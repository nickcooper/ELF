<?php

/**
 * MoveExpirationsShell
 *
 * @package    App
 * @subpackage App.Console.Command
 * @author     Iowa Interactive, LLC.
 */
class MoveExpirationsShell extends AppShell
{
    /**
     * PHP script memory limit
     *
     * @var array
     * @access private
     */
    private $_scriptMemLimit = '1024M';
    
    /**
     * load models
     */
    public $uses = array(
        'ContinuingEducation.CourseRoster',
        'Licenses.Expiration',
        'Licenses.Application'
    );

    public $output_file = '/tmp/move_expirations.sql';

    /**
     * main method
     *
     * @return void
     * @access public
     */
    public function main()
    {
        // set the script memory limit
        ini_set('memory_limit', $this->_scriptMemLimit);

        $rosters = $this->CourseRoster->find('all',
            array(
                'fields' => array(
                    'CourseRoster.id',
                    'CourseRoster.account_id',
                    'CourseRoster.expire_date'
                ),
                'contain' => array(
                    'CourseSection' => array(
                        'fields' => array(
                            'CourseSection.id',
                            'CourseSection.course_catalog_id'
                        )
                    )
                ),
                'conditions' => array(
                    'CourseRoster.expire_date' => null
                )
            )
        );

        $this->out(sprintf('%s course roster records found with null expire_date.', count($rosters)));
        
        $data = array();

        $sql = "SET foreign_key_checks = 0;\n";

        foreach ($rosters as $roster)
        {
            $expiration = $this->Expiration->find('first',
                array(
                    'fields' => array(
                        'Expiration.expire_date'
                    ),
                    'conditions' => array(
                        'Expiration.parent_obj' => 'Account',
                        'Expiration.parent_key' => $roster['CourseRoster']['account_id'],
                        'Expiration.foreign_obj' => 'CourseCatalog',
                        'Expiration.foreign_key' => $roster['CourseSection']['course_catalog_id']
                    )
                )
            );
            if ($expiration)
            {
                $sql .= sprintf("UPDATE course_rosters SET expire_date = '%s' WHERE id = %s;\n",
                    $expiration['Expiration']['expire_date'],
                    $roster['CourseRoster']['id']
                );
                /*
                $record = array(
                    'CourseRoster' => array(
                        'id' => $roster['CourseRoster']['id'],
                        'expire_date' => $expiration['Expiration']['expire_date']
                    )
                );

                $data[] = $record;
                */
            }
        }

        $sql .= "SET foreign_key_checks = 1;\n";

        if (($write_file = fopen($this->output_file, "w")) == false) 
        {
            throw new Exception('Failed to open write file.');
        }
        fwrite($write_file, $sql);
        fclose($write_file);
        //$this->CourseRoster->query('SET foreign_key_checks = 0;');

        //$this->CourseRoster->create();
        //$this->CourseRoster->saveMany($data);

        //$this->CourseRoster->query('SET foreign_key_checks = 1;');
    }

    public function remove_bad_dates()
    {
        $rosters = $this->CourseRoster->find(
            'all',
            array(
                'contain' => array(
                    'CourseSection'
                ),
                'conditions' => array(
                    'CourseRoster.expire_date !=' => null
                ),
                'order' => 'CourseRoster.account_id ASC, CourseSection.end_date DESC',
            )
        );
        $current = array();
        $marked = array();
        foreach ($rosters as $roster)
        {
            $key = $roster['CourseRoster']['account_id'].":".$roster['CourseSection']['course_catalog_id'].":".$roster['CourseRoster']['expire_date'];

            if (in_array($key, $current))
            {
                $marked[] = $roster['CourseRoster']['id'];
            }
            else
            {
                $current[] = $key;
            }

        }
        $this->out(sprintf('%s course roster records found in same catalog with same expire_date.', count($marked)));

        $sql = "SET foreign_key_checks = 0;\n";

        foreach ($marked as $id)
        {
            $sql .= sprintf("UPDATE course_rosters SET expire_date = null WHERE id = %s;\n", $id);
        }

        if (($write_file = fopen($this->output_file, "w")) == false) 
        {
            throw new Exception('Failed to open write file.');
        }
        $sql .= "SET foreign_key_checks = 1;\n";

        fwrite($write_file, $sql);
        fclose($write_file);
    }
}