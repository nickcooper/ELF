<?php
/**
 * Report model
 *
 * Interface for report related data
 *
 * @package Reports.Model
 * @author  Iowa Interactive, LLC.
 */
class Report extends ReportsAppModel
{
    /**
     * Model name
     *
     * @var string
     * @access public
     */
    public $name = 'Report';

    public $useTable = false;

    /**
     * gets billing item related data
     *
     * @param string $start datatime format
     * @param string $end datatime format
     *
     * @return array returns formatted array
     */
    public function getBillingItemsReportData($start = null, $end = null)
    {
        // default return value
        $rows = array();

        // load the billing items model
        $BillingItem = ClassRegistry::Init('Payments.BillingItem');

        // build query where clause
        $where = '';
        if ($start && $end)
        {
            $where = ' WHERE DATE(date) BETWEEN DATE("'. $start .'") AND DATE("'. $end .'") ';
        }

        // load the license type model
        $LicenseType = ClassRegistry::Init('Licenses.LicenseType');

        $license_type_abbrs = $LicenseType->find(
            'list',
            array(
                'fields' => array(
                    'LicenseType.abbr'
                ),
            )
        );

        foreach ($license_type_abbrs as $abbr)
        {
            $fee_keys[] = sprintf('%s_initial', strtolower($abbr));
        }

        // load the Fee model
        $Fee = ClassRegistry::Init('Payments.Fee');

        $fee_labels = $Fee->find(
            'list',
            array(
                'fields' => array(
                    'Fee.label'
                ),
                'conditions' => array(
                    'Fee.fee_key' => $fee_keys
                )
            )
        );

        $counts = array();

        foreach ($fee_labels as $fee_label)
        {
            $counts[$fee_label] = 0;
        }

        // get a list of unique labels
        $labels = Hash::extract(
            $BillingItem->find(
                'all',
                array(
                    'fields' =>array(
                        'BillingItem.label'
                    ),
                    'conditions' => array(
                        'DATE(BillingItem.date) BETWEEN ? AND ?' => array($start, $end)
                    ),
                    'group' => array('BillingItem.label'),
                    'order' => array('BillingItem.label ASC')
                )
            ),
            '{n}.BillingItem.label'
        );

        // loop the label
        foreach($labels as $label)
        {
            // get a count of all matching labels
            $count = $BillingItem->find(
                'count',
                array(
                    'conditions' => array(
                        'BillingItem.label' => $label,
                        'DATE(BillingItem.date) BETWEEN ? AND ?' => array($start, $end)
                    )
                )
            );

            $counts[$label] = $count;
        }

        foreach ($counts as $label => $count)
        {
            $rows[] = array(
                'label' => $label,
                'count' => $count
            );
        }

        return $rows;
    }
}