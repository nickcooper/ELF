<?
$body = "<p>".$letter['current_date']."<br />
".$letter['label']."<br />
".$letter['address_1']."<br />
".$letter['address_2']."<br />
".$letter['city'].", ".$letter['state']." ".$letter['zip']."
</p>
<p>Dear ".$letter['label'].":</p>

<p>We have been notified by your training provider that you have successfully completed a training course that meets the renovation education requirements for your abatement certification. This could be the:</p>

 <p>8-hour initial Lead Safe Renovator course or<br />
 4-hour Lead Safe Renovator refresher course or<br />
 8-hour Updated Lead Abatement Refresher course</p>

<p>Please consider receipt of this letter as notice that you have satisfactorily completed state requirements to update your lead abatement certification with training on the renovation materials.  You are now qualified to do renovation work in Iowa.</p>

<p>If you attended the 4-hour Lead Safe Renovator Refresher Course, this will not extend your refresher due date.  Your refresher due date for lead abatement will remain the same.</p>

<p>Also, please note that if you are currently certified as a lead abatement worker or lead abatement contractor, you do NOT need to apply for an additional certification as a lead safe renovator.  Your existing certification for abatement covers renovation work too.</p>

<p> </p>

<p>Bureau of Lead Poisoning Prevention<br />
Phone: 800-972-2026<br />
E-Mail:  Lead.Bureau@idph.iowa.gov</p>";

$this->Pdf->lib->SetMargins('18','50');
$this->Pdf->lib->AddPage();
$this->Pdf->lib->writeHTML($body, true, false, true, false, '');
$this->Pdf->lib->lastPage();




