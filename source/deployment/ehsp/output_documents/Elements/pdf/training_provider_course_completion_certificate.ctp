<?
$this->Pdf->lib->setTemplate(APP.'Plugin/OutputDocuments/Media/templates/seal.pdf');
$this->Pdf->lib->setPageOrientation('L');
$this->Pdf->lib->SetMargins('18','50');
$this->Pdf->lib->AddPage();

$address = implode("<br />", array_filter(array($data['TrainingProvider']['label'],$data['TrainingProvider']['PrimaryAddress']['addr1'],$data['TrainingProvider']['PrimaryAddress']['addr2'],"{$data['TrainingProvider']['PrimaryAddress']['city']}, {$data['TrainingProvider']['PrimaryAddress']['state']} {$data['TrainingProvider']['PrimaryAddress']['postal']}")));

$content = <<<EOS 
<p>IOWA DEPARTMENT OF PUBLIC HEALTH<br />
LEAD POISONING PREVENTION PROGRAM<br />
Lucas State Office Building, Des Moines, IA  50319-0075<br />
515/281-3479 or 800-972-2026</p>

<p>CERTIFICATE OF TRAINING</p>

<p>This is to certify that</p>

<p>{$address}</p>

<p>Certification #: {$data['License']['license_number']}</p>

<p>Approval Number: {$data['License']['license_number']}</p>

<p>Has successful completed the ".$letter['training_provider_course_name']." training course 
(Iowa Department of Public Health approval number ".$letter['training_provider_approval_number'].")
Held in ".$letter['training_provider_course_location']." on ".$letter['training_provider_course_date']." with a test score of ".$letter['training_provider_course_score']."</p>";
EOD;

$this->Pdf->lib->writeHTML($body, true, false, true, false, '');
$this->Pdf->lib->lastPage();