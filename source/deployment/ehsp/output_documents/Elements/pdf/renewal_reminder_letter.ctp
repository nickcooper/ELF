<?
//set the date to current date, or use the batch date, if it exists
$date = date("F j, Y");

if (isset($batch))
{
    $date = date("F j, Y", strtotime($batch['batch_date']));
}

// set the receipient name to a better format for displaying
$label = sprintf(
                '%s %s %s',
                $data['Account']['first_name'],
                $data['Account']['middle_initial'],
                $data['Account']['last_name']
            );

// build the address data
$address = implode("<br />", array_filter(array($label, $data['Account']['PrimaryAddress']['addr1'], $data['Account']['PrimaryAddress']['addr2'], "{$data['Account']['PrimaryAddress']['city']}, {$data['Account']['PrimaryAddress']['state']} {$data['Account']['PrimaryAddress']['postal']}")));

$license_expire_date = date("F j, Y", strtotime($data['License']['expire_date']));

//set the refersher end date
$refresher_end_date = isset($data['Account']['RefresherDate'][0]['expire_date'])
    ? date("F j, Y", strtotime($data['Account']['RefresherDate'][0]['expire_date']))
    : '##incomplete##';

$this->Pdf->lib->SetMargins('18','50');
$this->Pdf->lib->setCellPaddings(0,0,0,0);
$this->Pdf->lib->setCellMargins(0,0,0,0);
$this->Pdf->lib->SetFont('Helvetica', '', 11);
$this->Pdf->lib->AddPage();

$content = <<<EOD
<p>{$date}<br /></p>

<p>{$address}</p>

<p>Dear {$label}:</p>

<p>Your certification will expire on {$license_expire_date}.  You can renew your certification by completing and returning the enclosed renewal form along with your certification fee of &#36;{$data['Fee']['fee']}.  Please make your non-refundable check out to Iowa Department of Public Health.</p>

<p>You must renew your certification by {$license_expire_date}.  It is a violation of Iowa law to continue to perform any lead professional activity such as lead inspections, risk assessments, renovations or lead abatement until you have renewed your certification.</p>

<p><b>Please also note that you must complete a refresher course by {$refresher_end_date}.</b>  You can find a training provider for this course by logging onto our website at<br />
http://www.idph.state.ia.us/eh/lead_poisoning_prevention.asp.</p>
             
<p>If you have any questions regarding your certification, please contact us at the number below.</p>  
<p> </p>
<p> </p>
<p>Bureau of Lead Poisoning Prevention<br />
Phone: 800-972-2026<br />
E-mail: Lead.Bureau@idph.iowa.gov</p>
EOD;

$this->Pdf->lib->writeHTML($content, true, false, true, false, '');
$this->Pdf->lib->lastPage();