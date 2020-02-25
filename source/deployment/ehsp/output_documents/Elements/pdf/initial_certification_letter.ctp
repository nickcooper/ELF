<?
$this->Pdf->lib->setTemplate(APP.'Plugin/OutputDocuments/Media/templates/template1.pdf');

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

// generically default the photo to the default image
$photo = Router::url('/img/photos/default-image.png', true);

// set photo path to the uploaded photo, if it exists
if (GenLib::isData($data, 'Account.AccountPhoto.0', array('id')))
{
    $path = $data['Account']['AccountPhoto'][0]['file_path'];
    $filename = $data['Account']['AccountPhoto'][0]['file_name'];

    $photo = Router::url(sprintf('/%s/%s', $path, $filename), true);
}

$this->Pdf->lib->SetMargins('18','50');
$this->Pdf->lib->setCellPaddings(0,0,0,0);
$this->Pdf->lib->setCellMargins(0,0,0,0);
$this->Pdf->lib->SetFont('Helvetica', '', 11);
$this->Pdf->lib->AddPage();

// build the letter
$content = <<<EOD
<p>{$date}<br /></p>

<p>{$address}</p>

<p>Dear {$label}:</p>

<p>The department has reviewed the information you submitted and determined that you have met the requirements for certification in the state of Iowa as an {$data['LicenseType']['label']}.  Your certification number is:  {$data['License']['license_number']}.</p>
	
<p>Your certification will expire on {$license_expire_date}.  By that date, you must renew by verifying your information and paying a certification fee of &#36;{$data['Fee']['fee']}.  <strong>Also, please be aware that you are required to take a refresher course by {$refresher_end_date}.</strong></p>

<p>Below you will find a <strong>self-laminating</strong> ID card with your certification number and expiration date.  Keep this card with you when you are at a job site. If you have any questions, please contact us at the number below.</p>

<p>You can find the certification requirements and work practice standards for all lead professionals in Iowa Administrative Code 641 – Chapter 70, which is at: http://www.legis.state.ia.us/aspx/ACODocs/DOCS/641.70.pdf   You <strong><u>must</u></strong> be currently certified to perform work that requires certification.</p>
<p> </p>
<p> </p>
<p>Bureau of Lead Poisoning Prevention<br />
Phone: 800-972-2026<br />
E-mail: Lead.Bureau@idph.iowa.gov</p>
EOD;
$this->Pdf->lib->writeHTML($content, true, false, true, false, '');

$content = <<<EOD
<div>
<img src="{$photo}" style="align:left; width:24mm; height:32mm;" border="0" />
</div>
EOD;
$this->Pdf->lib->writeHTMLCell(24, 32, 123, 206, $content, 0, false, false, false, 'C');

$content = <<<EOD
<div style="font-weight: bold; font-size:10pt;">
IOWA DEPARTMENT<br>
OF PUBLIC HEALTH
<p>{$label}</p>
<p>{$data['LicenseType']['label']}</p>
</div>
EOD;
$this->Pdf->lib->writeHTMLCell(52, 32, 149, 207, $content, 0, false, false, false, 'C');

$content = <<<EOD
<div style="font-weight: bold; font-size:10pt;">
Certification Number: {$data['License']['license_number']}<br>
Expiration Date: {$license_expire_date}
</div>
EOD;

$this->Pdf->lib->writeHTMLCell(82, 14, 119, 243, $content, 0, false, false, false, 'C');
$this->Pdf->lib->lastPage();