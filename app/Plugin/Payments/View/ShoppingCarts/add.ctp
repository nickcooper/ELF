<DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/admin-page.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<!-- InstanceBeginEditable name="doctitle" -->
<title>DPH Environmental Health Portal Prototypes</title>
<!-- InstanceEndEditable -->
<!-- Stylesheets -->
<!-- > Framework Stylesheets -->
<link rel="stylesheet" href="../../css/blueprint/bp-screen.css" type="text/css" media="screen, projection">
<link rel="stylesheet" href="../../css/blueprint/bp-print.css" type="text/css" media="print">
<!--[if lt IE 8]><link rel="stylesheet" href="blueprint/bp-ie.css" type="text/css" media="screen, projection"><![endif]-->

<!-- > Custom Stylesheets -->
<link rel="stylesheet" href="../../css/layout.css" media="screen, projection" />
<link rel="stylesheet" href="../../css/style.css" media="screen, projection" />
<link rel="stylesheet" href="../../css/style-admin.css" media="screen, projection" />
<!--[if IE]><link rel="stylesheet" href="css/style-ie.css" type="text/css" media="screen, projection"><![endif]-->
<link rel="stylesheet" href="../../css/print.css" media="print" />

<!-- optional: 
<link rel="stylesheet" href="css/link-icons.css" media="screen, projection" /> -->

<!-- javascript -->
<script language="javascript" type="text/javascript" src="../../js/jquery-1.7.1.min.js"></script>
<script language="javascript" type="text/javascript" src="../../js/jquery-date_input.pack.js"></script>
<script language="javascript" type="text/javascript" src="../../js/jquery-viewport.min.js"></script>
<script language="javascript" type="text/javascript" src="../../js/jquery.jMomo.js"></script>
<script language="javascript" type="text/javascript" src="../../js/global-js.js"></script>
<script language="javascript" type="text/javascript" src="../../js/admin-js.js"></script>

<link REL="SHORTCUT ICON" HREF="../../favicon.ico">

<!-- InstanceBeginEditable name="head" -->
<!-- InstanceEndEditable -->
</head>
<body id="admin">
<div id="wrapper-header">
<div id="wrapper-body">
    <div class="container clearfix">
        <div id="body" class="span-24">
            <!-- InstanceBeginEditable name="Main Content" -->
            <div id="section" class="span-24">
                <div class="pad">
                    <h2>Add Items</h2>
                    <hr/>
                    <table border="0" cellspacing="0" cellpadding="0">
                        <thead>
                            <tr>
                                <th scope="col">Item</th>
                                <th scope="col">Item Type</th>
                                <th scope="col">Number</th>
                                <th scope="col">Fee</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><a href="../lead/licenses/view_license.php">Lead Inspector/Risk Assessor License</a></td>
                                <td>New</td>
                                <td>2012-LEAD-8802</td>
                                <td>$ 500.00</td>
                                <td class ="action"><?php echo $this->Html->link('ADD', array('plugin' => 'payments',
                                'controller' => 'shoppingcarts', 'action' => 'add')); ?> </td> 
                            </tr>
                            <tr>
                                <td><a href="../lead/licenses/view_license.php">Food Technician</a></td>
                                <td>New</td>
                                <td>2012-LSAM-1010</td>
                                <td>$ 100.10</td>
                                <td><a href="#">ADD</a></td>
                            </tr>
                            <tr>
                                <td><a href="../lead/licenses/view_license.php">Health Technician</a></td>
                                <td>New</td>
                                <td>2008-LSAM-9000</td>
                                <td>$ 900.99</td>
                                <td><a href="#">ADD</a></td>
                            </tr>
                            <tr>
                                <td><a href="../lead/licenses/view_license.php">Clean Technician</a></td>
                                <td>Renewal</td>
                                <td>2010-JAMM-7483</td>
                                <td>$ 30.00</td>
                                <td><a href="#">ADD</a></td>
                            </tr>
                            <tr>
                                <td><a href="../lead/licenses/view_license.php">Damage Technician</a></td>
                                <td>Renewal</td>
                                <td>2012-DSAM-0999</td>
                                <td>$ 500.00</td>
                                <td><a href="#">ADD</a></td>
                            </tr>
                            <tr>
                                <td><a href="../lead/licenses/view_license.php">Equipment Technician</a></td>
                                <td>Renewal</td>
                                <td>2008-LOLD-3948</td>
                                <td>$ 500.00</td>
                                <td><a href="#">ADD</a></td>
                            </tr>
                            <tr>
                                <td><a href="../lead/licenses/view_license.php">Sampling Technician</a></td>
                                <td>Renewal</td>
                                <td>2008-LSAM-0443</td>
                                <td>$ 500.00</td>
                                <td><a href="#">ADD</a></td>
                            </tr>
                            <tr>
                                <td><a href="../lead/licenses/view_license.php">Environment Technician</a></td>
                                <td>Renewal</td>
                                <td>2008-LSAM-0555</td>
                                <td>$ 300.00</td>
                                <td><a href="#">ADD</a></td>
                            </tr>
                            <tr>
                                <td><a href="../lead/licenses/view_license.php">Radio Technician</a></td>
                                <td>Renewal</td>
                                <td>2012-LEMM-0002</td>
                                <td>$ 125.00</td>
                                <td><a href="#">ADD</a></td>
                            </tr>
                            <tr>
                                <td><a href="../lead/licenses/view_license.php">Firm Technician</a></td>
                                <td>New</td>
                                <td>2012-LEAD-0001</td>
                                <td>$ 000.00</td>
                                <td><a href="#">ADD</a></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>          
            <!-- InstanceEndEditable -->
    </div>
</div>
</body>
<!-- InstanceEnd --></html>


