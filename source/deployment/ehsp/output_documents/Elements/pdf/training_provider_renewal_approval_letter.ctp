<?
$this->Pdf->lib->SetMargins('18','50');
$this->Pdf->lib->setCellPaddings(0,0,0,0);
$this->Pdf->lib->setCellMargins(0,0,0,0);
$this->Pdf->lib->SetFont('Helvetica', '', 11);
$this->Pdf->lib->AddPage();

$address = implode("<br />", array_filter(array($data['TrainingProvider']['label'],$data['TrainingProvider']['PrimaryAddress']['addr1'],$data['TrainingProvider']['PrimaryAddress']['addr2'],"{$data['TrainingProvider']['PrimaryAddress']['city']}, {$data['TrainingProvider']['PrimaryAddress']['state']} {$data['TrainingProvider']['PrimaryAddress']['postal']}")));

$date = date('F j, Y');

if (isset($batch))
{
    $date = date('F j, Y', strtotime($batch['batch_date']));
}

$contact_first_name = $data['TrainingProvider']['Manager']['0']['Account']['first_name'];
$contact_last_name = $data['TrainingProvider']['Manager']['0']['Account']['last_name'];

$instructors = array();
foreach ($data['TrainingProvider']['InstructorAssignment'] as $instructor)
{
    $instructors[] = $instructor['Account']['label'];
}
$instructors = implode(', ', $instructors);

$course_labels = array();
foreach ($data['TrainingProvider']['Course'] as $course)
{
    $course_labels[] = $course['CourseCatalog']['label'];
}
$course_labels = implode(', ', $course_labels);

$expire_date = date('F j, Y', strtotime($data['License']['expire_date']));

$content = <<<EOD
<p>{$date}<br /></p>

<p>{$address}</p>

<p>Dear {$data['TrainingProvider']['label']}:</p>

<p>We have reviewed the information that you submitted to be an approved training provider for Iowa’s lead-based paint training courses.  The information that you submitted indicates that you, {$contact_first_name} {$contact_last_name}, will serve as the training manager and that {$data['TrainingProvider']['label']} is the name of the organization.  The following people are listed as instructors:</p>

<p>{$instructors}</p>

<p>This letter is to confirm that {$data['TrainingProvider']['label']} has completed and submitted the training application and the required $200 fee.  All of the information meets the standard requirements of the Iowa Administrative Code 641-70.4.  Therefore, {$data['TrainingProvider']['label']}, is now an approved training provider and is able to teach all of the lead-based paint training activities currently available in Iowa.  You have been approved to teach the following courses:</p>

<p>{$course_labels}</p>

<p>Your training provider approval number will be: {$data['License']['license_number']}</p>

<p>Your status as an approved training provider is valid for three years, provided that all of the requirements are followed.  {$data['TrainingProvider']['label']} will be considered approved until {$expire_date}.  Please be aware that you must reapply at least 90 days prior to expiration date.</p>

<p>Enclosed are the necessary training materials on CD for the courses you are approved to teach.  Also enclosed are hard copies of the testing materials including, three versions of the course test and answer sheet for each type of certification you are approved to teach and one answer key for each test.</p>

<p>Bureau of Lead Poisoning Prevention<br />
Phone: 800-972-2026<br />
E-Mail:  Lead.Bureau@idph.iowa.gov</p>
EOD;

$this->Pdf->lib->writeHTML($content, true, false, true, false, '');
$this->Pdf->lib->lastPage();