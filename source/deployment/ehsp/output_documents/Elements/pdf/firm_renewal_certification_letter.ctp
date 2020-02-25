<?
$this->Pdf->lib->SetMargins('18','50');
$this->Pdf->lib->setCellPaddings(0,0,0,0);
$this->Pdf->lib->setCellMargins(0,0,0,0);
$this->Pdf->lib->SetFont('Helvetica', '', 11);
$this->Pdf->lib->AddPage();

$address = implode("<br />", array_filter(array($data['Firm']['label'],$data['Firm']['PrimaryAddress']['addr1'],$data['Firm']['PrimaryAddress']['addr2'],sprintf('%s, %s %s',$data['Firm']['PrimaryAddress']['city'], $data['Firm']['PrimaryAddress']['state'], $data['Firm']['PrimaryAddress']['postal']))));

foreach($data['Firm']['FirmLicense'] as $rec)
{
	if(in_array($rec['License']['LicenseType']['label'], $firm_licenses))
	{
		continue;
	}
	$firm_roster .= sprintf("%s - %s<br />", $rec['License']['label'], $rec['License']['LicenseType']['label']);
}

$date = date('F j, Y');

if (isset($batch))
{
    $date = date('F j, Y', strtotime($batch['batch_date']));
}

$expire_date = date('F j, Y', strtotime($data['License']['expire_date']));

$content = <<<EOD
<p>{$date}<br /></p>

<p>{$address}</p>

<p>Dear {$data['Firm']['Contact']['first_name']} {$data['Firm']['Contact']['last_name']}:</p>

<p>The department has reviewed the information you submitted and determined that your firm has met the requirements for renewal of your certification in the state of Iowa.  Our records indicate the following professionals are associated with your firm:</p>

<p>{$firm_roster}</p>

<p>Your certification number is {$data['License']['license_number']}.  Your current certification will expire on {$expire_date}.  By that date, you must renew your certification.  You will be sent a renewal form prior to your expiration date.</p>

<p>You must notify our department if the address listed in this letter is no longer your firm’s address.  Your employees must abide by the appropriate standards of conduct set forth in Iowa Administrative Code 641-Chapter 70.</p>

<p>If you have any questions, please contact our office at the number below.</p>

<p>Bureau of Lead Poisoning Prevention<br />
Phone: 800-972-2026<br />
E-mail: Lead.Bureau@idph.iowa.gov</p>
EOD;

$this->Pdf->lib->writeHTML($content, true, false, true, false, '');
$this->Pdf->lib->lastPage();