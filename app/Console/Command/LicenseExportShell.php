<?php

app::uses('Sanitize', 'Utility');

/**
 * LicenseExportShell
 *
 * @package    App
 * @subpackage App.Console.Command
 * @author     Iowa Interactive, LLC.
 */
class LicenseExportShell extends AppShell
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
        'Licenses.License'
    );

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

        // start time
        $start_time = time();

        $this->output_file = sprintf('/tmp/license_export_%s.csv', date('YmdHis'));

        $this->License->includeForeignData = false;

        $licenses = $this->License->find(
            'all',
            array(
                'contain' => array(
                    'Application' => array(
                        'fields' => array(
                            'expire_date'
                        ),
                        'limit' => 1
                    ),
                    'LicenseType' => array(
                        'fields' => array(
                            'label'
                        )
                    ),
                    'LicenseStatus' => array(
                        'fields' => array(
                            'status'
                        )
                    ),
                ),
                'conditions' => array(
                    'LicenseStatus.status' => array('Active', 'Expired'),
                ),
                /*'limit' => 50*/
            )
        );

        // open the file for writing
        if (($write_file = fopen($this->output_file, "a")) == false) 
        {
            throw new Exception('Failed to open write file.');
        }

        $header_row = array(
            'First Name',
            'Last Name',
            'Firm Name',
            'License Status',
            'License Type',
            'License Number',
            'Address 1',
            'Address 2',
            'City',
            'State',
            'Postal',
            'Phone',
            'No Public Contact',
            'License Exp Date',
            'Last 4 SSN'
        );


        // write the header row to file
        fputcsv($write_file, $header_row);

        $data = array();

        $record_index = 1;
        $record_total = count($licenses);

        foreach ($licenses as $license)
        {
            $row = array(
                0 => '', //First Name
                1 => '', //Last Name
                2 => '', //Firm Name
                3 => $license['LicenseStatus']['status'], //License Status
                4 => $license['LicenseType']['label'], //License Type
                5 => $license['License']['license_number'], //License Number
                6 => '', //Address 1
                7 => '', //Address 2
                8 => '', //City
                9 => '', //State
                10 => '', //Postal
                11 => '', //Phone
                12 => '', //No Public Contact
                13 => date('Y-m-d', strtotime($license['Application'][0]['expire_date'])), //License Application Exp Date
                14 => '', //Last 4 SSN
            );

            // load the foreign model
            $foreign_model = ClassRegistry::init(sprintf('%s.%s', $license['License']['foreign_plugin'], $license['License']['foreign_obj']));

            switch ($license['License']['foreign_obj'])
            {
                case 'Account':
                    $account = $foreign_model->find(
                        'first',
                        array(
                            'fields' => array('first_name', 'last_name', 'no_public_contact', 'ssn_last_four'),
                            'contain' => array(
                                'PrimaryAddress' => array(
                                    'fields' => array('addr1', 'addr2', 'city', 'state', 'postal', 'phone1')
                                )
                            ),
                            'conditions' => array(
                                'Account.id' => $license['License']['foreign_key']
                            ),
                        )
                    );
                    $row[0] = $account['Account']['first_name'];
                    $row[1] = $account['Account']['last_name'];
                    $row[6] = $account['PrimaryAddress']['addr1'];
                    $row[7] = $account['PrimaryAddress']['addr2'];
                    $row[8] = $account['PrimaryAddress']['city'];
                    $row[9] = $account['PrimaryAddress']['state'];
                    $row[10] = $account['PrimaryAddress']['postal'];
                    $row[11] = $account['PrimaryAddress']['phone1'];
                    $row[12] = ($account['Account']['no_public_contact']) ? '1' : '0';
                    $row[14] = $account['Account']['ssn_last_four'];
                    break;
                case 'Firm':
                    $firm = $foreign_model->find(
                        'first',
                        array(
                            'fields' => array('label', 'no_public_contact'),
                            'contain' => array(
                                'PrimaryAddress' => array(
                                    'fields' => array('addr1', 'addr2', 'city', 'state', 'postal', 'phone1')
                                )
                            ),
                            'conditions' => array(
                                'Firm.id' => $license['License']['foreign_key']
                            ),
                        )
                    );
                    $row[2] = $firm['Firm']['label'];
                    $row[6] = $firm['PrimaryAddress']['addr1'];
                    $row[7] = $firm['PrimaryAddress']['addr2'];
                    $row[8] = $firm['PrimaryAddress']['city'];
                    $row[9] = $firm['PrimaryAddress']['state'];
                    $row[10] = $firm['PrimaryAddress']['postal'];
                    $row[11] = $firm['PrimaryAddress']['phone1'];
                    $row[12] = ($firm['Firm']['no_public_contact']) ? '1' : '0';
                    break;
                case 'TrainingProvider':
                    $training_provider = $foreign_model->find(
                        'first',
                        array(
                            'fields' => array('label', 'no_public_contact'),
                            'contain' => array(
                                'PrimaryAddress' => array(
                                    'fields' => array('addr1', 'addr2', 'city', 'state', 'postal', 'phone1')
                                )
                            ),
                            'conditions' => array(
                                'TrainingProvider.id' => $license['License']['foreign_key']
                            ),
                        )
                    );
                    $row[2] = $training_provider['TrainingProvider']['label'];
                    $row[6] = $training_provider['PrimaryAddress']['addr1'];
                    $row[7] = $training_provider['PrimaryAddress']['addr2'];
                    $row[8] = $training_provider['PrimaryAddress']['city'];
                    $row[9] = $training_provider['PrimaryAddress']['state'];
                    $row[10] = $training_provider['PrimaryAddress']['postal'];
                    $row[11] = $training_provider['PrimaryAddress']['phone1'];
                    $row[12] = ($training_provider['TrainingProvider']['no_public_contact']) ? '1' : '0';
                    break;

            }

            // write row to file
            fputcsv($write_file, $row);

            if ($this->params['progress'])
            {
                $this->updateProgress($start_time, time(), $record_total, $record_index);
            }

            $record_index++;
        }
        // close the file
        fclose($write_file);

        $this->out(sprintf('Output File: %s', $this->output_file));
    }
}