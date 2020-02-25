<?
$this->Pdf->lib->SetMargins('18','50');
$this->Pdf->lib->setCellPaddings(0,0,0,0);
$this->Pdf->lib->setCellMargins(0,0,0,0);
$this->Pdf->lib->SetFont('Helvetica', '', 11);
$this->Pdf->lib->AddPage();

$address = implode("<br />", array_filter(array($data['Account']['label'],$data['Account']['PrimaryAddress']['addr1'],$data['Account']['PrimaryAddress']['addr2'],sprintf('%s, %s %s',$data['Account']['PrimaryAddress']['city'], $data['Account']['PrimaryAddress']['state'], $data['Account']['PrimaryAddress']['postal']))));

$project_address = implode(", ", array_filter(array($data['LocationAddress']['addr1'],$data['LocationAddress']['addr2'],$data['LocationAddress']['city'],$data['LocationAddress']['state'],$data['LocationAddress']['postal'])));

$date = date('F j, Y');

if (isset($batch))
{
    $date = date('F j, Y', strtotime($batch['batch_date']));
}

$phase_1_start_date = date("F j, Y", strtotime($data['AbatementPhase'][0]['begin_date']));
$phase_1_end_date = date("F j, Y", strtotime($data['AbatementPhase'][0]['end_date']));

$revised_date = date("F j, Y", strtotime($data['Abatement']['modified']));

$content = <<<EOD
<p>{$date}<br /></p>

<p>{$address}</p>

<p>Dear {$data['Account']['label']}:</p>

<p>RE: Lead Abatement Notification for {$project_address}</p>

<p>This is to confirm that on {$revised_date} we received a revision to the lead abatement notification for {$project_address}.  The projected start date is {$phase_1_start_date} and the projected end date is {$phase_1_end_date}.  If this changes again, you will need to submit another revised lead abatement notification with the new dates.</p>

<p>Please note that you may be subject to an on-site inspection by our staff at any time during the lead abatement project. In addition, please note that the lead abatement report required by Iowa Administrative Code 641—Chapter 70 must be completed no later than 30 days after the lead abatement project is finished.  You will need to complete an abatement report no later than {$data['report_due_date']} if you do not send us another revised notice to change the projected ending date.  We have attached information to remind you what is required for this report.</p>
 
<p>If you have any questions, please contact our office at 800-972-2026.</p>

<p>Bureau of Lead Poisoning Prevention<br />
Iowa Department of Public Health</p>
EOD;
$this->Pdf->lib->writeHTML($content, true, false, true, false, '');
$this->Pdf->lib->lastPage();