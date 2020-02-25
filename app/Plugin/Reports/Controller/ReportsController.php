<?php
/**
 * ReportsController
 *
 * @package Report.Controller
 * @author  Iowa Interactive, LLC.
 */
class ReportsController extends AppController
{
    /**
     * Controller name
     *
     * @var string
     * @access public
     */
    public $name = 'Reports';

    /**
     * Autoload models
     *
     * @var array
     * @access public
     */
    public $uses = array(
        'Payments.Payment',
        'Payments.PaymentItem',
        'Account.Account',
        'Fee',
        'Licenses.License',
        'Licenses.Application',
        'Licenses.LicenseType',
        'Reports.Report'
    );

    /**
     * Default pagination options.
     *
     * @var Array
     * @access public
     */
    public $paginate = array(
        'limit' => 50,
    );

    /**
     * beforeFilter method
     * 
     * @return bool
     * @access public
     */
    public function beforeFilter ()
    {
        parent::beforeFilter();

        $date_shortcuts = array(
            'today' => array(
                'start' => date('Y-m-d 00:00:00'),
                'end' => date('Y-m-d 23:59:59')),
            'yesterday' => array(
                'start' => date('Y-m-d 00:00:00', strtotime('-1 day')),
                'end' => date('Y-m-d 23:59:59', strtotime('-1 day'))),
            'month' => array(
                'start' => date('Y-m-01 00:00:00'),
                'end' => date('Y-m-d 23:59:59')),
            'last_month' => array(
                'start' => date('Y-m-01 00:00:00', strtotime('-1 month')),
                'end' => date('Y-m-'.date('t', strtotime('-1 month')).' 23:59:59', strtotime('-1 month')))
            );
        $this->set('date_shortcuts', $date_shortcuts);

        return true;
    }

    /**
     * Paginated list for ledger
     *
     * @return bool
     * @access public
     */
    public function ledger_report ()
    {
        $rows = array();

        // Contain fields from associated models
        $this->paginate['PaymentItem'] = array(
            'contain' => array(
                'Payment' => array(
                    'fields' => array('id'),
                )
            )
        );

        // Conditions defined by filters
        if (!empty($this->request->params['named']))
        {
            $this->paginate['PaymentItem']['conditions'] = array(
                'PaymentItem.created >=' => $this->request->params['named']['start'],
                'PaymentItem.created <=' => $this->request->params['named']['end']
            );

            $records = $this->paginate('PaymentItem');

            foreach ($records as $record)
            {
                $rows[] = array(
                    $record['Payment']['id'],
                    array('label' => $record['PaymentItem']['label'], 'url' => '/'),
                    $record['PaymentItem']['fee'],
                    $record['PaymentItem']['created']
                );
            }
        }

        // Define report specific view variables
        $this->set('action', 'ledger_report');
        $this->set('page_name', 'Ledger Report');

        // set headers
        $this->set('headers', array('Payment ID','Label','Fee','Date Time'));

        // set rows
        $this->set('rows', $rows);

        if (isset($this->request->params['named']['output']) && $this->request->params['named']['output'] == 'csv')
        {
            $this->RequestHandler->renderAs($this, 'csv', array('attachment' => 'ledger_report.csv'));
        }

        $this->set('filter', $this->request->params['named']);

        $this->render('report');

        return true;
    }

    /**
     * Displays totals for licenses
     * 
     * @return bool
     * @access public
     */
    public function billing_items_report ()
    {
        $rows = array();

        // Conditions defined by filters
        if (!empty($this->request->params['named']))
        {
            $rows = $this->Report->getBillingItemsReportData(
                $this->request->params['named']['start'],
                $this->request->params['named']['end']
            );
        }

        // Define report specific view variables
        $this->set('action', 'billing_items_report');
        $this->set('page_name', 'Billing Items Report');
        $this->set('filter', $this->request->params['named']);

        // set headers
        $this->set('headers', array('License Type','Count'));

        // set rows
        $this->set('rows', $rows);
    }

    /**
     * Search used by all report's filtering
     *
     * @return false
     * @access public
     */
    public function search ($action)
    {
        // If form data exists
        if (!empty($this->request->data))
        {
            $data = array();

            // Build datetime string from array
            foreach ($this->request->data['Report'] as $key => $value)
            {
                if ($key == 'start' || $key == 'end')
                {
                    $data[$key] = formatDateTime($value);
                }
            }

            // If download link was clicked send csv flag
            if (!empty($this->request->data['submit_download']))
            {
                $data['output'] = 'csv';
            }

            // Redirect back to the reports page with data
            $this->redirect(array_merge(array('action' => $action), $data));
        }
        // If no form data was submitted redirect back to reports page
        $this->redirect(array('action' => $action));

        return false;
    }

    /**
     * Paginated list of licenses
     *
     * @return bool
     * @access public
     */
    public function index ()
    {
        $this->set('page_name', 'Reports');
        return;
    }
}