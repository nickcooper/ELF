<?php

App::import('Vendor', 'Phpickle/phpickle');

/**
 * PhpickleShell
 *
 * @package    App
 * @subpackage App.Console.Command
 * @author     Iowa Interactive, LLC.
 */
class PhpickleShell extends AppShell
{
    /**
     * database connection
     */
    private $DB = null;
    
    /**
     * database table list
     */
    private $tables_list = array();
    
    /**
     * currently selected table
     */
    private $table = array();
    
    /**
     * table column list
     */
    private $colunm_list = array();
    
    /**
     * currently selected column
     */
    private $column = array();
    
    
    

    //---------------------------------
    // MAIN PROCESS METHOD
    //---------------------------------
    
    /**
     * main method
     *
     * @return void
     * @access public
     */
    public function main()
    {
        try {
            // WARNING
            $this->heading('BACKUP THE DATABASE BEFORE YOU CONTINUE OR YOU RISK A BIEBERING!');
            
            
            
            // get our mysql connection inputs 
            $this->heading('Connect to database.');
            
            $host = $this->in('Mysql host?', null, 'sprocket');
            $this->out('');
            
            $user = $this->in('Mysql user?', null, null);
            $this->out('');
            
            $password = $this->in('Mysql password?', null, null);
            $this->out('');
            
            $database = $this->in('Mysql schema?', null, null);
            $this->out('');
            
            
            
            // attempt to connect to database
            if (!$this->DB = $this->dbConnect($host, $user, $password, $database))
            {
                throw new Exception ('Failed to connect to database.');
            }
            $this->outSuccess(sprintf('Connected to %s on %s.', $database, $host));
            $this->out();
            
            
            
            // get a list of tables
            $this->heading('Table data to unpickle.');
            
            $this->tables = $this->getTableList();
            
            // get our table input
            if (!$this->setTable($this->tableMenu()))
            {
                throw new Exception ('Failed to set table.');
            }
            $this->out();
            
            
            
            // get a list of columns
            $this->columns = $this->getColumnList();
            
            // get our field input
            if (!$this->setColumn($this->columnMenu()))
            {
                throw new Exception ('Failed to set column.');
            }
            $this->out();
            
            
            
            // unpickle the data and serialize yo!
            $this->heading('Unpickling in progress.');
            
            if (!$this->unPickleAndSerializeYo())
            {
                throw new Exception ('Failed to unpickle, serialize or update records.');
            }
            $this->out();
            
            $this->outSuccess('Data unpickled and serialized yo!');
            $this->out();
        }
        catch (Exception $e)
        {
            // fail and exit
            $this->outFailure(sprintf('%s', $e->getMessage()));   
        }
        
        
    }
    
    
    

    //---------------------------------
    // FUNCTIONAL MEHTODS
    //---------------------------------
    
    /**
     * get records from table, unpickle, serialize and update record
     */
    private function unPickleAndSerializeYo()
    {
        // define the sql statement
        $select_sql = sprintf('SELECT id, %s FROM %s;', $this->column, $this->table);
        
        // query records
        $results = $this->DB->query($select_sql);
        
        // loop
        for ($i=0; $i <= $results->num_rows; $i++)
        {
            $record = $results->fetch_array(MYSQLI_ASSOC);
            
            // skip empty values
            if (empty($record[$this->column]))
            {
                continue;
            }
            
            // unpickle
            $unpickled_data = phpickle::loads($record[$this->column]);
            
            if (!is_array($unpickled_data))
            {
                $unpickled_data = array($unpickled_data);
            }
            
            $data = array();
            
            foreach ($unpickled_data as $id => $d)
            {
                switch (true)
                {
                    // object
                    case is_object($d):
                        $data[$id] = $d->value; // $d->value is DPS specific (sorry)
                        break;
                    
                    // array
                    case is_array($d):
                        $data[$id] = implode(', ', $d);
                        break;
                        
                    // str, int, etc.
                    default:
                        $data[$id] = $d;
                        break;
                }
            }
            
            // serialize
            $data = serialize($data);
            
            // format update sql
            $update_sql = sprintf(
                'UPDATE %s SET %s = "%s" WHERE id = %s LIMIT 1;',
                $this->table,
                $this->column,
                $this->DB->escape_string($data),
                $record['id']
            );
            
            // update the record with unpickled, serialized data
            if (!$this->DB->query($update_sql))
            {
                $this->outFailure(sprintf('Failed record id %s. %s', $record['id'], $data));
            }
        }
        
        return true;
    }
    
    /**
     * get a list of tables or columns
     */
    private function getList($type)
    {
        $list = array();
        
        // define the correct sql
        switch ($type)
        {
            case 'tables':
                $sql = 'SHOW TABLES;';
                break;
            
            case 'columns':
                $sql = sprintf('SHOW COLUMNS FROM %s;', $this->table);
                break;
            
            default:
                $sql = '';
                break;
        }
        
        // get a list of tables
        $results = mysqli_query($this->DB, $sql);
        
        while ($result = mysqli_fetch_array($results))
        {
            $list[] = $result[0];
        }
        
        return $list;
    }
    
    /**
     * list menu
     */
    public function listMenu($type)
    {
        // generate menu
        foreach ($this->{$type} as $key => $val)
        {
            $this->out(sprintf('[%s] %s', $key, __($val)));
        }
        $this->out('');

        return strtoupper(
            $this->in(
                __(sprintf('Select %s:', Inflector::singularize($type))),
                array_keys($this->{$type}),
                null
            )
        );
    }
    
    /**
     * select an item form a list and set it as an obj var
     */
    public function setFromList($list_type, $list_key)
    {
        // pluaral and singular of list type
        $types = $list_type;
        $type = Inflector::singularize($list_type);
        
        // is the list key in the list array
        if (array_key_exists($list_key, $this->{$types}))
        {
            // set item to object var
            $this->{$type} = $this->{$types}[$list_key];
            
            return true;
        }
        
        return false;
    }
    
    /**
     * connect to database
     */
    private function dbConnect($host, $user, $password, $database)
    {
        $mysqli = new mysqli($host, $user, $password, $database);
        
        // return connection of false
        return ($mysqli->connect_errno ? false : $mysqli);
    }



    //---------------------------------
    // WRAPPER METHODS
    //---------------------------------
    
    /**
     * list wrapper function for tables
     */
    private function getTableList()
    {
        return $this->getList('tables');
    }
    
    /**
     * list wrapper function for columns
     */
    private function getColumnList()
    {
        return $this->getList('columns');
    }
    
    /**
     * menu wrapper function for tables
     */
    private function tableMenu()
    {
        return $this->listMenu('tables');
    }
    
    /**
     * menu wrapper function for columns
     */
    private function columnMenu()
    {
        return $this->listMenu('columns');
    }
    
    /**
     * set wrapper function for tables
     */
    private function setTable($list_key)
    {
        return $this->setFromList('tables', $list_key);
    }
    
    /**
     * set wrapper function for columns
     */
    private function setColumn($list_key)
    {
        return $this->setFromList('columns', $list_key);
    }
}