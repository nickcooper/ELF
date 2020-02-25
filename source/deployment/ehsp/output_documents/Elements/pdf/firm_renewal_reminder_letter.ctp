<?
$this->Pdf->lib->SetMargins('18','50');
$this->Pdf->lib->setCellPaddings(0,0,0,0);
$this->Pdf->lib->setCellMargins(0,0,0,0);
$this->Pdf->lib->SetFont('Helvetica', '', 11);
$this->Pdf->lib->AddPage();

$address = implode("<br />", array_filter(array($data['Firm']['label'],$data['Firm']['PrimaryAddress']['addr1'],$data['Firm']['PrimaryAddress']['addr2'],sprintf('%s, %s %s',$data['Firm']['PrimaryAddress']['city'], $data['Firm']['PrimaryAddress']['state'], $data['Firm']['PrimaryAddress']['postal']))));

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

<p>Your firm’s certification for lead based paint activities with the state of Iowa will expire on {$expire_date}.  Please complete and return the enclosed renewal form to renew your firm’s certification.</p>

<p>In order to stay current with personal certifications, you must stay current with your firm certification.  If your firm certification expires, the firm will not be able to conduct any lead professional activities such as lead inspections, risk assessments, renovations or lead abatement until you have renewed this certification.</p>
            
<p>If you have any questions regarding your firm’s certification, please contact us at the number below.</p>
<p> </p>
<p> </p>
<p>Bureau of Lead Poisoning Prevention<br />
Phone: 800-972-2026<br />
E-mail: Lead.Bureau@idph.iowa.gov</p>
EOD;

$this->Pdf->lib->writeHTML($content, true, false, true, false, '');
$this->Pdf->lib->lastPage();