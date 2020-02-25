<?php

// set the template
$this->Pdf->lib->setTemplate(APP.'Plugin/OutputDocuments/View/Layouts/agency/pdf/letterhead.pdf');

// set the margins
$this->Pdf->lib->SetMargins('28','50','18');
$this->Pdf->lib->SetFont('Times', '', 11);

$date = date('Y-m-d');

if (isset($batch))
{
    $date = date('Y-m-d', strtotime($batch['batch_date']));
}

// set the primary foreign_obj
$foreign_obj = (isset($data['Firm']) ? 'Firm' : 'Account');

// foramat the address
$address = implode(
    "<br />", 
    array_filter(
        array(
            $data['License']['label'],
            $data[$foreign_obj]['PrimaryAddress']['addr1'],
            $data[$foreign_obj]['PrimaryAddress']['addr2'],
            sprintf(
                "%s %s, %s", 
                $data[$foreign_obj]['PrimaryAddress']['city'], 
                $data[$foreign_obj]['PrimaryAddress']['state'], 
                $data[$foreign_obj]['PrimaryAddress']['postal']
            )
        )
    )
);

if (preg_match('/^,$/', trim($address)))
{
    $address = '';
}

//print "<pre>"; print_r($data); print "</pre>"; exit;
$endorsement_text = '';
$variants = Hash::extract($data, 'LicenseVariant.{n}.Variant.label');

if (count($variants) > 0)
{
    $endorsement_text = sprintf("<strong>License Endorsements:</strong> %s<br />", implode(', ', $variants));
}

// create a new page
$this->Pdf->lib->AddPage();

$expire_date = date('Y-m-d', strtotime($data['Application'][0]['expire_date']));

// page content
$content = <<<EOD
    <p><strong>Date:</strong> {$date}</p>
    
    <p>{$address}</p>
    
    <p><strong>RE:</strong> {$data['LicenseType']['label']}</p>
    
    <p><strong>Dear Applicant,</strong></p>
    <p>Congratulations, your application for licensure by the Iowa Board of Electrical Examiners has been approved and your wallet-sized license is enclosed with this letter. The review and approval of your application to practice in Iowa as an Electrician or Electrical Contractor was based upon Iowa Code 103 and Iowa Administrative Rules 661-500.</p>
    
    <p><strong>License Number:</strong> {$data['License']['license_number']} - {$data['LicenseType']['label']}<br />
    {$endorsement_text}
    <strong>Expiration Date:</strong> {$expire_date}</p>
    
    <p>• Please verify that the type of electrical license listed on the wallet-sized license is correct. If it is not, please contact our office at elecinfo@dps.state.ia.us, (515) 725-6147, or 1-866-923-1082 at your earliest convenience.<br />
    • All Apprentice and Unclassified licenses are issued for <strong>one</strong> calendar year beginning January 1st.<br />
    • All Electrical Contractors, Residential Contractors, Residential Masters, Residential Electricians, Masters A or B, Journeymen A or B, and Special Electrician licenses are issued for <strong>three</strong> years.<br />
    • Journeyman, Master, Residential Master and Residential Electrician licensees are required to have 18 Continuing Education Units (CEUs) to renew these licenses; with a minimum of 6 hours in study of the National Electrical Code (appropriate to current State of Iowa adopted NEC). The remaining 12 hours may be used studying areas of other electrical-related material as approved by the Board, or they may also be over the NEC. If you have not held your license for the full 3-year license-cycle, your CEUs are prorated, and you are required to have 6 CEUs for each year the license has been issued, with a minimum of 6 hours in study of the National Electrical Code (appropriate to current State of Iowa adopted NEC).</p>
    
    <p>Again, congratulations on achieving this important designation and we look forward to working with you in the future on the maintenance and renewal of your license.</p>
    
    <p>Sincerely,</p>
    <p>Electrical Examining Board & Staff</p>
EOD;

// add the content to the file
$this->Pdf->lib->writeHTML($content, true, false, true, false, '');

// end current page
$this->Pdf->lib->lastPage();
?>