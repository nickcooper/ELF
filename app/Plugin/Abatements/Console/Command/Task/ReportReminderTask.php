<?php

/**
 * ReportReminderTask
 *
 * Performs the following actions:
 *     - Retrieves abatements 45 days after their latest expire date for a phase
 *     - Changes abatement status from Active to Complete
 *     - Batches notification reminder letter
 *     - Adds logging message
 * 
 * @package    App
 * @subpackage App.Console.Command.Task
 * @author     Iowa Interactive, LLC.
 */
class ReportReminderTask extends AppShell
{
    /**
     * Models
     *
     * @var array
     * @access public
     */
    public $uses = array(
        'Abatements.Abatement',
        'Abatements.AbatementPhase',
        'Abatements.AbatementStatus',
    );

    /**
     * Tag used to prefix error log file name.
     *
     * @var string
     */
    const TAG = 'abatements_error';

    /**
     * execute method
     * 
     * @return bool
     * @access public
     */
    public function execute()
    {
        $this->out('Running report reminder on active abatements', 1);

        $counts_total = 0;
        $counts_passed = 0;
        $counts_failed = 0;

        $checkDate = date('Ymd', strtotime('-45 days'));

        $abatements = $this->Abatement->getActiveAbatements();
        foreach ($abatements as $abatement)
        {
            $abatement_id = $abatement['Abatement']['id'];
            $phases = $abatement['AbatementPhase'];

            // Grab the end date of the last phase
            $lastPhase = $abatement['AbatementPhase'][count($phases)-1];
            $endDate = date('Ymd', strtotime($lastPhase['end_date']));

            // Perform logic three days after latest expiration/phase date
            if ($endDate == $checkDate)
            {
                try
                {
                    $counts_total++;

                    // 1) Change status to completed
                    $this->Abatement->setStatus($abatement_id, 'completed');

                    // 2) Batch notification letter
                    $this->Abatement->batchAbatementReminderLeter($abatement_id);

                    // 3) Log a history message
                    // $this->History->addMessage(
                    //     'Abatements',
                    //     'Abatement',
                    //     $abatement_id,
                    //     __('Changed status from active to completed')
                    // );

                    $counts_passed++;
                }
                catch (Exception $e)
                {
                    $counts_failed++;
                    $this->log(
                        sprintf(__('Unable to mark abatement as completed: %s'), $e->getMessage()),
                        self::TAG
                    );
                }
            }
        }
        $this->out(sprintf('Task Complete. %s/%s/%s (pass/fail/total)', $counts_passed, $counts_failed, $counts_total));
    }
}
