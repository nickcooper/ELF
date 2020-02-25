<?php
// Configure::load() expects there to be a $config variable in here.
$config = array();

/**
 * Default Common Checkout Config
 */
Configure::write('common_checkout_config',
    array(
        'wsdl_url' => 		'https://stageccp.dev.cdc.nicusa.com/CommonCheckout/CCPWebService/ServiceWeb.wsdl',
        'form_url' => 		'https://stageccp.dev.cdc.nicusa.com/CommonCheckout/CommonCheckPage/Default.aspx',
        'success_url' => 	sprintf('%s%s/payments/payments/credit_card_success', Configure::read('App.baseUrl'), Configure::read('App.base')),
        'failure_url' => 	sprintf('%s%s/payments/payments/credit_card_fail', Configure::read('App.baseUrl'), Configure::read('App.base')),
        'cancel_url' => 	sprintf('%s%s/payments/payments/credit_card_cancel', Configure::read('App.baseUrl'), Configure::read('App.base')),
        'duplicate_url' => 	sprintf('%s%s/payments/payments/credit_card_duplicate', Configure::read('App.baseUrl'), Configure::read('App.base')),
        'state_code' => 	'IA', // Example: IA
        'merchant_id' => 	'EHSP', // Example: EHSP
        'merchant_key' => 	'EHSP', // Example: EHSP
        'service_code' => 	'ELF', // Example: ELF
    )
);

/**
 * Variant License Numbers
 */
Configure::write('Licenses.license_number_variants', true);

/**
 * Output Document Trigger Config
 */
Configure::write('OutputDocuments.triggers',
    array()
);

/**
 * Output Document Document Config
 */
Configure::write('OutputDocuments.docs',
    array()
);