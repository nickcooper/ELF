<?php

app::uses('Sanitize', 'Utility');

/**
 * EhspSsnFixShell
 *
 * @package    App
 * @subpackage App.Console.Command
 * @author     Iowa Interactive, LLC.
 */
class EhspSsnFixShell extends AppShell
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
        'Accounts.Account'
    );
    
    /**
     * file locations
     */
    private $files = array(
        'source'    => '/tmp/tbl_Professionals2.csv',
        'sql'       => '/tmp/ehsp_ssn_fix.sql'
    );

    /**
     * Menu choices
     *
     * @var array
     * @access private
     */
    private $_menu = array(
        'choices' => array(
            array('Generate SQL File', 'genFile'),
            array('Report Bad SSNs', 'badSsns'),
            'Q' => array('Quit', 'quit'),
        ),
        'default' => 'q',
    );


    /**
     * Display menu and return user selected choice.
     *
     * @return string User selected choice
     * @access public
     */
    public function menu()
    {
        $this->heading(__('EHSP SSN Fix'));

        // generate menu
        foreach ($this->_menu['choices'] as $key => $def)
        {
            list ($desc, $method) = $def;
            $this->out(sprintf('[%s] %s', $key, __($desc)));
        }
        $this->out('');

        return strtoupper(
            $this->in(
                __('Which action would you like to take?'),
                array_keys($this->_menu['choices']),
                $this->_menu['default']
            )
        );
    }


    /**
     * main method
     *
     * @return void
     * @access public
     */
    public function main()
    {
        $choice = $this->menu();

        call_user_func(
            array($this, $this->_menu['choices'][$choice][1])
        );
    }


    /**
     * genFile method
     *
     * @return void
     * @access public
     */
    public function genFile()
    {
        $this->heading('Generating SQL Script');
        
        // delete the previous file
        unlink($this->files['sql']);
        
        // fix hyphenated ssns
        $this->fixSsns();
        
        // reset imported ssns
        $this->resetSsns();
        
        // fin
        $this->outSuccess(sprintf('File Generated: %s', $this->files['sql']));
    }
    

    /**
     * fixSsns method
     *
     * @return void
     * @access public
     */
    public function fixSsns()
    {
        // open the sql file for writing
        if (($write_file = fopen($this->files['sql'], "a")) == false) 
        {
            throw new Exception('Failed to open write file.');
        }
        
        // get a list of account
        $accounts = $this->Account->find('all', array('TRIM(Account.ssn) REGEXP "^[0-9]{3}-[0-9]{2}-[0-9]{4}"'));
        
        foreach ($accounts as $account)
        {
            // define the data
            $id = $account['Account']['id'];
            $ssn = preg_replace('/[^0-9]/', '', $account['Account']['ssn']);
            
            if (!preg_match('/^[0-9]{9}$/', $ssn))
            {
                $ssn = '';
            }
            
            // encrypt
            $ssn = GenLib::encryptString(preg_replace('/[^0-9]/', '', $ssn));
            
            // define the sql statement
            $mysqli = new mysqli("sprocket", "jgrady", "111us3r", "elf_vader");
            $sql = "UPDATE accounts SET ssn = '" . $mysqli->escape_string($ssn) . "' WHERE accounts.id = " . $id . " LIMIT 1;";
            
            // write the update statement to the sql file
            fwrite($write_file,  $sql . "\n");
        }
        
        // close the file
        fclose($write_file);
        
        $this->outSuccess('Added Updates for Hyphenated SSNs');
    }
        
    

    /**
     * resetSsns method
     *
     * @return void
     * @access public
     */
    public function resetSsns()
    {   
        // open the sql file for writing
        if (($write_file = fopen($this->files['sql'], "a")) == false) 
        {
            throw new Exception('Failed to open write file.');
        }
        
        // open the source file for reading
        if (($read_file = fopen($this->files['source'], "rb")) == false) 
        {
            // close the files
            fclose($write_file);
            
            throw new Exception('Failed to open read file.');
        }
        
        // read the contents of the read_file
        $row = 0;
        
        while (($data = fgetcsv($read_file, 1024*1000, ',')) !== false)
        {
            // increment row
            $row++;
            
            // skip columns headers
            if ($row == 1)
            {
                continue;
            }
            
            // find a matching ELF account
            if (!$account = $this->Account->getAccountByLegacyId($data[1]))
            {
                // skip to the next record
                continue;
            }
            
            // define the data
            $id = $account['Account']['id'];
            $ssn = GenLib::encryptString($data[6]);
            
            // define the sql statement
            $sql = "UPDATE accounts SET ssn = '" . Sanitize::escape($ssn, 'default') . "' WHERE accounts.id = " . $id . " LIMIT 1;";
            
            // write the update statement to the sql file
            fwrite($write_file,  $sql . "\n");
        }
        
        // close the files
        fclose($write_file);
        fclose($read_file);
        
        $this->outSuccess('Added Updates for Imported SSNs');
    }
    

    /**
     * badSsns method
     *
     * @return void
     * @access public
     */
    public function badSsns()
    {
        $this->heading('Reporting Bad SSNs');
        
        // default return value
        $bad_ssns = 0;
        
        // get accounts
        $accounts = $this->Account->find('all');
        
        foreach ($accounts as $account)
        {
            $orig_ssn = $account['Account']['ssn'];
            $decrypted_ssn = Sanitize::clean(GenLib::decryptString($orig_ssn), array('encode'));
            
            // check for unencrypted ssns
            if (preg_match('/^[0-9]{3}/', $orig_ssn))
            {
                $this->outFailure(
                    sprintf(
                        '%s, %s, %s', 
                        $account['Account']['id'],
                        $account['Account']['label'],
                        $account['Account']['ssn']
                    )
                );
                
                $bad_ssns++;
            }
            
            // check for invalid decrypted ssns
            if (!preg_match('/^[0-9]{9}$/', $decrypted_ssn))
            {
                $this->outFailure(
                    sprintf(
                        '%s, %s, %s', 
                        $account['Account']['id'],
                        $account['Account']['label'],
                        $account['Account']['ssn']
                    )
                );
                
                $bad_ssns++;
            }
        }
        
        if ($bad_ssns)
        {
            $this->outFailure(sprintf('Total Bad SSNs: %s', $bad_ssns), 2);
        }
        else 
        {
            $this->outSuccess(sprintf('Total Bad SSNs: %s', $bad_ssns), 2);
        }
    }
}