<?php

/**
 * FirmsNoMailShell
 *
 * @package    App
 * @subpackage App.Console.Command
 * @author     Iowa Interactive, LLC.
 */
class FirmsNoMailShell extends AppShell
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
    public $uses = array('Firms.Firm');

    public $source_file_path = '/tmp/firms_no_contact.csv';
    public $output_file_path = '/tmp/ehsp_firms_no_contact.sql';
    public $changed_file_path = '/tmp/ehsp_firms_no_contact_changed.csv';


    /**
     * main method
     *
     * @return void
     * @access public
     */
    public function main()
    {
        $this->heading('Firms No Mail Update');

        ini_set("auto_detect_line_endings", true);

        $this->out('Opening source file..');

        // open the source file for reading
        if (($source_file = fopen($this->source_file_path, "r")) == false) 
        {
            throw new Exception('Failed to open source file.');
        }

        $this->out('Looping through source records searching for matches..');

        $legacy_ids = array();

        $row_num = 0;
        while (($row = fgetcsv($source_file, 1024*1000)) !== false)
        {
            if ($row_num == 0)
            {
                $row_num++;
                continue;
            }

            if ($row[8] == 'true' || $row[9] == 'true')
            {
                $legacy_ids[] = $row[0];
            }
            $row_num++;
        }

        $firms = $this->Firm->find('all', 
            array(
                'conditions' => array(
                    'Firm.legacy_id' => $legacy_ids
                )
            )
        );

        $this->out(sprintf('%s ELF firm records found..', count($firms)));

        if (count($firms) > 0)
        {
            $sql = sprintf("UPDATE firms SET no_mail = 1 WHERE id IN (%s);", implode(',',Hash::extract($firms, '{n}.Firm.id')));
            
            if (($output_file = fopen($this->output_file_path, "w")) == false) 
            {
                throw new Exception('Failed to open output file.');
            }
            fwrite($output_file,  $sql . "\n");
            fclose($output_file);

            if (($changed_file = fopen($this->changed_file_path, "w")) == false) 
            {
                throw new Exception('Failed to open changed file.');
            }

            fwrite($changed_file, "\"Legacy ID\", \"Firm ID\", \"Firm Label\"\n");

            foreach ($firms as $firm)
            {
                fwrite($changed_file, sprintf("%s,%s,\"%s\"\n", $firm['Firm']['legacy_id'], $firm['Firm']['id'], $firm['Firm']['label']));
            }
            fclose($changed_file);

        }

        $this->out(sprintf('Update script path: %s', $this->output_file_path));
        $this->out(sprintf('Changed records CSV path: %s', $this->changed_file_path));

    }
}