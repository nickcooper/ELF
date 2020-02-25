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

$date_received = date("F j, Y", strtotime($data['Abatement']['date_received']));

$phase_1_start_date = date("F j, Y", strtotime($data['AbatementPhase'][0]['begin_date']));

$content = <<<EOD
<p>{$date}<br /></p>

<p>{$address}</p>

<p>Dear {$data['Account']['label']}:</p>

<p>RE: Lead Abatement Notification for {$project_address}</p>

<p>This is to confirm that we received the Lead Abatement Notification for {$project_address} on {$date_received}.  According to Iowa Administrative Code 641—Chapter 70, you are required to send notification to our office at least seven days before the projected start date of the abatement project.  We received this notice at least seven days before the projected start date of {$phase_1_start_date}.  If the project start or end dates change, you need to submit a revised lead abatement notification.</p>

<p>Please note that you may be subject to an on-site inspection by our staff at any time during the lead abatement project.  In addition, please note that the lead abatement report required by Iowa Administrative Code 641—Chapter 70 must be completed no later than 30 days after the lead abatement project is finished.  This report must be finished no later than {$data['report_due_date']} if you do not send us a revised notice to change the projected ending date.  We have attached information to remind you what is required for this report.</p>

<p>If you have any questions, please contact our office at 800-972-2026.</p>

<p>Bureau of Lead Poisoning Prevention<br />
Iowa Department of Public Health</p>
EOD;
$this->Pdf->lib->writeHTML($content, true, false, true, false, '');

$this->Pdf->lib->SetFont('Helvetica', '', 9);
$this->Pdf->lib->AddPage();

$content = <<<EOD
<p style="text-align: center; font-weight: bold;">REQUIREMENTS FOR LEAD ABATEMENT REPORT<br />
Iowa Administrative Code, 641—Chapter 70.6(6) l,m,n,o and p</p>

<p>l. No later than three weeks after the property passes clearance, the certified lead inspector/risk
assessor or certified elevated blood lead (EBL) inspector/risk assessor shall send a report to the lead abatement contractor that contains the items required by subparagraphs 70.6(6)“m”(7) through (9).</p>

<p>m. The certified lead abatement contractor or a certified project designer shall prepare a lead
abatement report containing the following information:<br />
(1) A copy of the original and any revised lead abatement notifications.<br />
(2) Starting and completion dates of the lead abatement project.<br />
(3) The name, address, and telephone number of the property owner(s).<br />
(4) The name, address, and signature of the certified lead abatement contractor and certified lead
abatement worker and of the certified firm contact for the firm conducting the lead abatement.<br />
(5) Whether or not containment was used and, if containment was used, the locations of the containment.<br />
(6) The occupant protection plan required by paragraph 70.6(6)“e.”<br />
(7) The name, address, and signature of each certified lead inspector/risk assessor or certified elevated blood lead (EBL) inspector/risk assessor conducting clearance sampling, the date on which the clearance testing was conducted, the results of the visual inspection for the presence of lead hazards that were not abated as specified, deteriorated paint and visible dust, debris, residue, or paint chips in the interior rooms and exterior areas where lead abatement was conducted, and the results of all post abatement clearance testing and all soil analyses, if applicable. The results of dust sampling shall be reported in micrograms of lead per square foot by location of sample, and the results of soil sampling shall be reported in parts per million of lead. The results shall not be reported as “not detectable.” If random selection was used to select the residential dwellings that were sampled, the report shall state that random selection was used, the number of residential dwellings that were sampled, and how this number was determined.<br />
(8) A statement that the lead abatement was or was not done as specified and that the rooms and
exterior areas where lead abatement was conducted did or did not pass the visual clearance and the clearance dust testing. If the certified lead inspector/risk assessor or certified elevated blood lead (EBL) inspector/risk assessor conducting the clearance testing cannot verify that all lead-based paint hazards have been abated, the report shall contain the following statement:<br />
“The purpose of this clearance report is to verify that the lead abatement project was done according to the project specifications. This residential dwelling may still contain hazardous lead-based paint, soil-lead hazards, or dust-lead hazards in the rooms or exterior areas that were not included in the lead abatement project.”<br />
(9) The name, address, and telephone number of each recognized laboratory conducting an analysis of clearance samples and soil samples, including the identification number for each such laboratory recognized by EPA under Section 405(b) of the Toxic Substances Control Act (15 U.S.C. 2685(b)).<br />
(10) A detailed written description of the lead abatement project, including lead abatement methods used, locations of rooms and components where lead abatement occurred, reasons for selecting particular lead abatement methods, and any suggested monitoring of encapsulants or enclosures.<br />
(11) Information regarding the owner’s obligations to disclose known lead-based paint and lead based paint hazards upon sale or lease of residential property as required by Subpart H of 24 CFR Part 35 and Subpart I of 40 CFR Part 745.<br />
(12) Information about the notification regarding lead-based paint prior to renovation, remodeling, or repainting as required by 641-Chapter 69.<br />
(13) If applicable, a copy of the written consent or waiver required by subrule 70.6(11).</p>

<p>n. The lead abatement report shall be completed no later than 30 days after the lead abatement project passes clearance testing.</p>

<p>o. The certified lead abatement contractor shall maintain all reports and plans required in this subrule for a minimum of three years.</p>

<p>p. The certified lead abatement contractor shall provide a copy of all reports required by this subrule to the building owner and to the person who contracted for the lead abatement, if different.</p>
EOD;
$this->Pdf->lib->writeHTML($content, true, false, true, false, '');
$this->Pdf->lib->lastPage();