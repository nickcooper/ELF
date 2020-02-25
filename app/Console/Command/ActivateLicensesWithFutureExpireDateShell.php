<?php
/**
 * ActivateLicensesWithFutureExpireDateShell shell
 *
 * @package    App
 * @subpackage App.Console.Command
 * @author     Iowa Interactive, LLC.
 */
class ActivateLicensesWithFutureExpireDateShell extends AppShell
{
    public $uses = array('Licenses.License','Licenses.LicenseStatus');

    /**
     * main funciton
     *
     * @return void
     * @access public
     */
    public function main()
    {
        try
        {
            $this->heading('Activate Licenses With Future Expire Date Shell');

            $output_dir = ROOT.DS.'app'.DS.'tmp'.DS.'logs'.DS;
            $output_sql = 'activate_licenses_with_future_expire_date.sql';
            $output_log = 'activate_licenses_with_future_expire_date_log.csv';

            if (($sql_file = fopen($output_dir.$output_sql, "w")) == false)
            {
                throw new Exception('Failed to open the sql output file.');
            }

            if (($log_file = fopen($output_dir.$output_log, "w")) == false)
            {
                throw new Exception('Failed to open the log output file.');
            }

            $expired_status_id = Hash::get($this->LicenseStatus->findByStatus('Expired'), 'LicenseStatus.id');
            $active_status_id = Hash::get($this->LicenseStatus->findByStatus('Active'), 'LicenseStatus.id');

            $this->License->includeForeignData = false;
            $licenses = $this->License->find(
                'all',
                array(
                    'fields' => array(
                        'License.id',
                        'License.label',
                        'License.license_number',
                        'License.expire_date'
                    ),
                    'recursive' => false,
                    'conditions' => array(
                        'License.license_status_id' => $expired_status_id,
                        'License.expire_date >=' => date('Y-m-d 00:00:00')
                    ),
                    'order' => 'License.license_number ASC'
                )
            );

            $licenses = array_unique($licenses, SORT_REGULAR);

            if (count($licenses) > 0)
            {
                $this->out(sprintf('%s records found.', count($licenses)));

                $csv_headers = array('License Number', 'Label', 'Expire Date');
                fputcsv($log_file, $csv_headers);

                $update_template = "UPDATE licenses SET license_status_id = '%s' WHERE id = '%s';\n";

                foreach ($licenses as $license)
                {
                    $log_row = array(
                        $license['License']['license_number'],
                        $license['License']['label'],
                        $license['License']['expire_date']
                    );
                    fputcsv($log_file, $log_row);

                    fwrite($sql_file, sprintf($update_template, $active_status_id, $license['License']['id']));
                }
            }

            fclose($log_file);
            fclose($sql_file);

            $this->out(sprintf('SQL file: %s', $output_dir.$output_sql));
            $this->out(sprintf('Log CSV file: %s', $output_dir.$output_log));
            $this->out();

        }
        catch (Exception $e)
        {
            // fail and exit
            $this->outFailure(sprintf('%s', $e->getMessage()));
            $this->out('');

            // fail so that Jenkins will report a failure occured
            exit(1);
        }
    }
}