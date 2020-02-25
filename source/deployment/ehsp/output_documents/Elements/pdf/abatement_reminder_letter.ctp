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

$content = <<<EOD
<p>{$date}<br /></p>

<p>{$address}</p>

<p>Dear {$data['Account']['label']}:</p>

<p>According to our records, you are the responsible lead abatement contractor for the following lead abatement projects:</p>

<table>
<tr>
	<td width="40%"><strong>Project Address:</strong></td>
	<td width="20%"><strong>Projected<br />
		Start Date:</strong></td>
	<td width="20%"><strong>Projected<br />
		End Date:</strong></td>
	<td width="20%"><strong>Report Due:</strong></td>
</tr>
<tr>
	<td>{$project_address}</td>
	<td>{$phase_1_start_date}</td>
	<td>{$phase_1_end_date}</td>
	<td>{$data['report_due_date']}</td>
</tr>
</table>
<p> </p>

<p>The lead abatement reports for the above completed projects should be finished by now. The Iowa Administrative Code 641- Chapter 70 requires that you give a copy of the report to the homeowner and keep a copy in your files for three (3) years after the project is completed.  You do not need to send a copy of this report to our office unless it is specifically requested.  A member of our staff may be contacting you to review these reports to ensure that they meet the requirements of our regulations.  A copy of these requirements is attached to this letter. If any of the above project dates have changed, you must send in a revised notification that lists the corrected dates.  A revised project end date may change the report due date.</p>

<p>If you have any questions, please contact our office at 800-972-2026.</p>

<p>Bureau of Lead Poisoning Prevention<br />
Iowa Department of Public Health</p>
EOD;
$this->Pdf->lib->writeHTML($content, true, false, true, false, '');
$this->Pdf->lib->lastPage();