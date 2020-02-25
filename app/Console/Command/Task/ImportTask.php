<?php
/**
 * ImportShell
 *
 * @package    App
 * @subpackage App.Console.Command.Task
 * @author     Iowa Interactive, LLC.
 */
class ImportTask extends AppShell
{
    /**
     * Models our task has access to.
     *
     * @var array
     * @access public
     */
    public $uses = array('Licenses.LicenseType');

    /**
     * Primary save model
     *
     * @var str
     * @access public
     */
    public $primary_model = null;

    /**
     * Data mapping from csv to cake models
     *
     * @var array
     * @access public
     */
    public $data_map = null;

    /**
     * Data for cake model
     *
     * @var array
     * @access public
     */
    public $data = null;

    /**
     * Main process method
     *
     * @param array $row data provided by the import csv file
     *
     * @return true if successful
     * @access public
     */
    public function importRow($row)
    {
        // map the csv data to cake data
        if (!$this->data = $this->mapData($row))
        {
            throw new Exception('Failed to map csv data to cake data array.');
        }

        // save data to database
        $this->saveRecord();

        // passed
        return true;
    }

    /**
     * Save data record
     *
     * @return void
     * @access public
     */
    public function saveRecord()
    {
        // primary save model
        $PrimaryModel = $this->{$this->primary_model};

        // initiate a new object record for the primary model to insert
        $PrimaryModel->create();

        // attempt data save
        if (!$PrimaryModel->save($this->data) || $PrimaryModel->getAffectedRows() == 0)
        {
            if (count($PrimaryModel->validationErrors) > 0)
            {
                $errors = Set::flatten($PrimaryModel->validationErrors);
                foreach ($errors as $key => $error)
                {
                    $this->out($key.' => '.$error);
                }
            }

            throw new Exception('Failed to save data.');
        }
    }

    /**
     * Map data from csv array to cake data array
     *
     * @param array $row data provided by the import csv file
     *
     * @return array returns the cake data array
     * @access public
     */
    public function mapData($row = array())
    {
        $retVal = array();

        foreach ($this->data_map as $key => $val)
        {
            list($model, $field) = explode('.', $val);

            $retVal[$model][$field] = (trim($row[$key]) == '' ? null : $row[$key]);
        }

        return $retVal;
    }

    /**
     * Get the plugin name from a switch case.
     *
     * @param str $obj model object name
     *
     * @return mixed Plugin name if matched, false if not.
     * @access public
     */
    public function getPluginNameFromObj($obj)
    {
        switch ($obj)
        {
        case 'Account':
            return 'Accounts';
        break;
        case 'Firm':
            return 'Firms';
        break;
        case 'TrainingProvider':
            return 'ContinuingEducation';
        break;
        case 'Abatement':
            return 'Abatements';
        break;
        default:
            throw new Exception(sprintf("Plugin name unknown for (%s)", $obj));
        break;
        }

        return false;
    }
}
