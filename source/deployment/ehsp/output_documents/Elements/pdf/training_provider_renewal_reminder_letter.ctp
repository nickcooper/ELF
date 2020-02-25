<?
$this->Pdf->lib->SetMargins('18','50');
$this->Pdf->lib->setCellPaddings(0,0,0,0);
$this->Pdf->lib->setCellMargins(0,0,0,0);
$this->Pdf->lib->SetFont('Helvetica', '', 11);
$this->Pdf->lib->AddPage();

$address = implode("<br />", array_filter(array($data['TrainingProvider']['label'],$data['TrainingProvider']['PrimaryAddress']['addr1'],$data['TrainingProvider']['PrimaryAddress']['addr2'],"{$data['TrainingProvider']['PrimaryAddress']['city']}, {$data['TrainingProvider']['PrimaryAddress']['state']} {$data['TrainingProvider']['PrimaryAddress']['postal']}")));

$expire_date = date('F j, Y', strtotime($data['License']['expire_date']));

$content = <<<EOD
<p>{$letter['current_date']}</p>

<p>{$address}</p>

<p>Dear {$data['TrainingProvider']['label']}:</p>

<p>Your approval status as an Iowa approved training provider will expire on {$expire_date}.  You can renew your training provider approval status by completing and returning the enclosed renewal form along with your training provider fee of &#36;{$data['Fee']['fee']}.   Please make out your non-refundable check to Iowa Department of Public Health.</p>

<p>Once approved, your approval status is valid for three years.  It is a violation of Iowa law to continue offering training courses beyond your expiration date unless you have renewed.</p>
            
<p>If you have any questions regarding your firm’s certification, please contact us at the number below.</p>
<p> </p>
<p> </p>
<p>Bureau of Lead Poisoning Prevention<br />
Phone: 800-972-2026<br />
E-mail: Lead.Bureau@idph.iowa.gov</p>
EOD;
$this->Pdf->lib->writeHTML($content, true, false, true, false, '');
$this->Pdf->lib->lastPage();