<?php
$this->Pdf->lib->setTemplate(APP.'Plugin/OutputDocuments/Media/templates/seal.pdf');
$this->Pdf->lib->setPageOrientation('L');
$this->Pdf->lib->SetMargins('18','50');
$this->Pdf->lib->AddPage();

$address = implode("<br />", array_filter(array($data['TrainingProvider']['PrimaryAddress']['addr1'],$data['TrainingProvider']['PrimaryAddress']['addr2'],"{$data['TrainingProvider']['PrimaryAddress']['city']}, {$data['TrainingProvider']['PrimaryAddress']['state']} {$data['TrainingProvider']['PrimaryAddress']['postal']}")));

$course_labels = array();
foreach ($data['TrainingProvider']['Course'] as $course)
{
    $course_labels[] = $course['CourseCatalog']['label'];
}
$course_labels = implode(', ', $course_labels);

if($data['Application'][0]['materials_received'])
{
    $issue_date = date("F j, Y", strtotime($data['Application'][0]['materials_received']));
}
else
{
    $issue_date = date("F j, Y", strtotime($data['Application'][0]['effective_date']));
}

$expire_date = date('F j, Y', strtotime($data['License']['expire_date']));

$content = <<<EOS
<p style="text-align:center; font-size: 3.2em;">
Iowa Department of Public Health<br />
Bureau of Lead Poisoning Prevention
</p>

<p style="text-align:center;">
<strong>
<span style="font-size: 2.2em;"><u>{$data['TrainingProvider']['label']}</u></span><br />
{$address}
</strong>
</p>
<p style="text-align:center; font-size: 1.5em;">License No.: {$data['License']['license_number']}</p>

<p style="text-align:center; font-size: 1.5em;">
is approved as a Training Provider under 641-Chapter 70, IAC
<br />
For the following categories: {$course_labels}
</p>
<p style="text-align:center; font-size: 1.5em;">Issued: {$issue_date} and Expires: {$expire_date}</p>
EOS;

$this->Pdf->lib->writeHTML($content, true, false, true, false, '');
$this->Pdf->lib->lastPage();