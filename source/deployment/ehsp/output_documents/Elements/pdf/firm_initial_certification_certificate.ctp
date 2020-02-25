<?php
$this->Pdf->lib->setTemplate(APP.'Plugin/OutputDocuments/Media/templates/seal.pdf');
$this->Pdf->lib->setPageOrientation('L');
$this->Pdf->lib->SetMargins('40','40');
$this->Pdf->lib->AddPage();

$firm_licenses = array();
foreach($data['Firm']['FirmLicense'] as $rec)
{
	if(in_array($rec['License']['LicenseType']['label'], $firm_licenses))
	{
		continue;
	}
	$firm_licenses[] = $rec['License']['LicenseType']['label'];
}
$firm_licenses = implode(', ', $firm_licenses);

$address = implode("<br />", array_filter(array($data['Firm']['PrimaryAddress']['addr1'],$data['Firm']['PrimaryAddress']['addr2'],sprintf('%s, %s %s',$data['Firm']['PrimaryAddress']['city'], $data['Firm']['PrimaryAddress']['state'], $data['Firm']['PrimaryAddress']['postal']))));

$issue_date = date('F j, Y', strtotime($data['License']['issued_date']));
$expire_date = date('F j, Y', strtotime($data['License']['expire_date']));

$content = <<<EOS
<p style="text-align:center; font-size: 3.0em;">
Iowa Department of Public Health<br />
<span style="text-align:center; font-size: 0.8em;">Bureau of Lead Poisoning Prevention</span>
</p>

<p style="text-align:center;">
<strong>
<span style="font-size: 2.2em;"><u>{$data['Firm']['label']}</u></span><br />
{$address}
</strong>
</p>

<p style="text-align:center; font-size: 1.5em;">
is certified as a firm under 641-Chapter 70, IAC
<br />
For the following categories: {$firm_licenses}
</p>
<p style="text-align:center; font-size: 1.5em;">Certification No.: {$data['License']['license_number']}</p>
<p style="text-align:center; font-size: 1.5em;">Issued: {$issue_date} and Expires: {$expire_date}</p>
EOS;

$this->Pdf->lib->writeHTML($content, true, false, true, false, '');
$this->Pdf->lib->lastPage();