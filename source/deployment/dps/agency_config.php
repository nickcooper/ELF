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
 * Minimal ContinuingEducation settings
 */
Configure::write('ContinuingEducation.minimal.instructor', 'Young, Brian');
Configure::write('ContinuingEducation.minimal.success_mail_list', array('jgrady@iowai.org'));


/**
 * Values to be used in any date field dropdowns
 */
Configure::write('min_year', '2009');
Configure::write('max_year', date('Y'));


/**
 * Variant License Numbers
 */
Configure::write('Licenses.license_number_variants', false);

/**
 * Searchable for dps
 */
Configure::write('Searchable.License',
    array(
        'locator' => array(
            'search' => array(
                'License.license_number',
                'License.label',
                'License.legacy_number',
                'License Type ID' => 'License.license_type_id',
                'License Status ID' => 'License.license_status_id',
                'License Variant ID' => 'License.license_variant_id',
            ),
            'fields' => array(
                'License Number' => 'License.license_number',
                'License Holder' => 'License.label',
                'Legacy License Number' => 'License.legacy_number'
            ),
        ),
        'index' => array(
            'fields' => array(
                'License Number' => 'License.license_number',
                'License Holder' => 'License.label',
                'Legacy Number' => 'License.legacy_number',
                'Status' => 'LicenseStatus.status',
                'Expire Date' => 'License.expire_date',
                'Variant' => 'LicenseVariant.abbr',
                'Modified' => 'License.modified',
            ),
            'contain' => array('LicenseType', 'LicenseStatus'),
            'elements'  => array(
                'add_button_element' => 'searchable_add_new_button',
                'filter_element' => 'index_action_bar',
                'left_element' => 'section_heading',
            ),
            'view_vars' => array(
                'variants' => 'getVariantList',
                'license_types' => 'getLicenseTypeList',
                'license_statuses' => 'getLicenseStatusList',
                'pending_count' => 'pendingCount',
                'date_fields' => array(
                    'License.issued_date' => 'Issue Date',
                    'License.expire_date' => 'Expiration Date',
                ),
            ),
        ),
        'firm_locator' => array(
            'conditions' => array(
                    'AND' => array(
                        'License.license_status_id' => '1',      //Active licenses only
                        'License.license_type_id' => array(1,2),      //MA or MB license types only
                    ),
            ),
        ),
        'custom_filter' => array(
            'conditions' => array(
                    'AND' => array(
                        'License.license_status_id' => '1',      //Active licenses only
                        'License.foreign_obj' => 'Account',      //Individual license types only
                    ),
            )
        )
    )
);
        
Configure::write('Searchable.Account', 
    array(
        'locator' => array(
            'search' => array(
                'Account.email',
                'Account.username',
                'Account.label',
                'Account.first_name',
                'Account.last_name',
                'Account.ssn_last_four',
                'Account.modified',
            ),
            'fields' => array(
                'Account' => 'Account.label',
                'Username' => 'Account.username',
                'Email' => 'Account.email',
                'SSN' => 'Account.ssn_last_four',
                'Modified' => 'Account.modified',
            ),
        ),
        'index' => array(
            'fields' => array(
                'Name' => 'Account.label',
                'Email' => 'Account.email',
                'Username' => 'Account.username',
                'SSN Last Four' => 'Account.ssn_last_four',
                'Modified' => 'Account.modified',
            ),
            'elements'  => array(
                'add_button_element' => 'searchable_add_new_button',
                'filter_element' => 'index_action_bar',
            ),
        ),
        'add' => array(
            'element' => 'account_form',
            'method' => 'addAccount',
            'view_vars' => array(
                'groups' => 'getGroups',
            )
        )
    )
);

Configure::write('Searchable.Abatement', 
    array(
        'locator' => array(
            'search' => array(
                'Firm Name'       => 'Firm.label',
                'Requestor'       => 'License.label',
                'Project Address' => 'LocationAddress.addr1',
                'Project City'    => 'LocationAddress.city',
                ),
            'fields' => array(
                'Firm Name'            => 'Firm.label',
                'Abatement Contractor' => 'License.label',
                'Project Address'      => 'LocationAddress.addr1',
                'Project City'         => 'LocationAddress.city',
                ),
            ),
        'index' => array(
            'title' => 'Abatements',
            'fields' => array(
                'Abatement Number'     => 'Abatement.abatement_number',
                'Abatement Contractor' => 'License.label',
                'Project Address'      => 'LocationAddress.addr1',
                'Project City'         => 'LocationAddress.city',
                'Start Date'           => 'AbatementPhase.0.begin_date',
                'End Date'             => 'AbatementPhase.0.end_date',
                'Status'               => 'AbatementStatus.label',
            ),
            'contain' => array(
                'Firm',
                'License',
                'LocationAddress',
                'AbatementStatus',
                'AbatementPhase',
                'PropertyOwner',
                'DwellingType',
            ),
            'elements'  => array(
                'add_button_element' => 'searchable_add_new_button',
                'filter_element'     => 'index_action_bar',
                'left_element'       => 'section_heading',
                ),
            'view_vars' => array(
                'dwelling_types' => 'getDwellingTypeList',
            )
        )
    )
);

Configure::write('Searchable.CourseCatalog', 
    array(
        'locator' => array(
            'search' => array(
                'CourseCatalog.label',
            ),
            'fields' => array(
                'Course Title' => 'CourseCatalog.label',
                'Program' => 'Program.abbr'
            )
        ),
        'index' => array(
            'search' => array(
                'Course Title' => 'CourseCatalog.label',
                'Program' => 'Program.abbr'
            ),
            'fields' => array(
                'Course Title' => 'CourseCatalog.label',
                'Program' => 'Program.label',
                'Code Hours' => 'CourseCatalog.code_hours',
                'Non-Code Hours' => 'CourseCatalog.non_code_hours',
                'Test Attempts' => 'CourseCatalog.test_attempts',
                ),
            'contain' => array(
                'Program',
            ),
            'elements' => array(
                'add_button_element' => 'searchable_add_new_course_catalog_button',
                'left_element'       => 'searchable_side_menu',
                'filter_element'     => 'course_catalogs_index_action_bar',
                ),
            'view_vars' => array(
                'pending_count' => 'pendingInstructorCount',
            ),
        ),
    )
);

Configure::write('Searchable.CourseLocation', 
    array(
        'locator' => array(
            'search' => array(
                'Address.label',
                'TrainingProvider.label'
            ),
            'fields' => array(
                'Address' => 'Address.label',
                'Trainin Provider' => 'TrainingProvider.label'
            ),
            'contain' => array(
                'TrainingProvider',
                'Address',
            )
        ),
        'index' => array(
            'fields' => array(
                'Location' => 'Address.label',
                'Training Provider' => 'TrainingProvider.label',
            ),
            'contain' => array(
                'TrainingProvider',
                'Address',
            ),
            'elements' => array(
                'add_button_element' => null,
                'left_element' => 'searchable_side_menu',
                'filter_element' => 'course_locations_index_action_bar',
            ),
            'view_vars' => array(
                'pending_count' => 'pendingInstructorCount',
            )
        )
    )
);

Configure::write('Searchable.CourseSection', 
    array(
        'locator' => array(
            'search' => array(
                'CourseSection.label',
                'CourseSection.course_section_number',
                'Address.city',
                'Account.first_name',
                'Account.last_name',
            ),
            'fields' => array(
                'Course' => 'CourseSection.label',
                'Course Number' => 'CourseSection.course_section_number'
            ),
            'contain' => array(
                'Account',
                'TrainingProvider',
                'Address',
            )
        ),
        'index' => array(
            'fields' => array(
                'Title' => 'CourseSection.label',
                'Course Number' => 'CourseSection.course_section_number',
                'Training Provider' => 'TrainingProvider.label',
                'Location' => 'Address.city',
                'Start' => 'CourseSection.start_date',
            ),
            'contain' => array(
                'TrainingProvider',
                'Address',
                'Account',
            ),
            'elements' => array(
                'left_element'       => 'searchable_side_menu',
                'add_button_element' => 'searchable_add_new_course_section_button',
                'filter_element'     => 'course_sections_index_action_bar',
            ),
            'view_vars' => array(
                'course_catalogs' => 'getCourseCatalog',
                'pending_count' => 'getPendingInstructorCount',
            ),
            'order' => array(
                'CourseSection.start_date' => 'DESC'
            )
        ),
        'add' => array(
            'element' => '',
            'method' => '',
        )
    )
);

Configure::write('Searchable.Instructor', 
    array(
        'locator' => array(
            'search' => array(
                'Account.first_name',
                'Account.last_name',
                'Account.username'
            ),
            'fields' => array(
                'Name' => 'Account.label',
                'Username' => 'Account.username'
            ),
            'contain' => array(
                'Account',
                'Program'
            )
        ),
        'index' => array(
            'fields' => array(
                'Name' => 'Account.label',
                'License Type' => 'LicenseType.label',
                'Program' => 'Program.label',
                'Approved' => 'approved',
                'Enabled' => 'enabled'
            ),
            'contain' => array(
                'Account',
                'Program'
            ),
            'elements' => array(
                'left_element'       => 'searchable_side_menu',
                'add_button_element' => 'searchable_add_new_instructor_button',
                'filter_element'     => 'instructors_index_action_bar'
            ),
            'view_vars' => array(
                'pending_count' => 'pendingCount',
                'programs' => 'getProgramList',
            )
        )
    )
);

Configure::write('Searchable.ThirdPartyTest', 
    array(
        'index' => array(
            'fields' => array(
                'Test Name' => 'ThirdPartyTest.label',
                'Entity'    => 'ThirdPartyTest.entity',
                'Interim'   => 'ThirdPartyTest.interim',
                'Enabled'   => 'ThirdPartyTest.enabled',
                'Modified'  => 'ThirdPartyTest.modified',
            ),
            'contain' => array('Address'),
            'elements'  => array(
                'add_button_element' => 'searchable_add_third_party_test',
                'left_element'       => 'section_heading',
            ),
            'view_vars' => array(
                'pending_count' => 'pendingCount',
            )
        )
    )
);

Configure::write('Searchable.TrainingProvider', 
    array(
        'locator' => array(
            'search' => array(
                'TrainingProvider.label'
            ),
            'fields' => array(
                'Training Provider' => 'TrainingProvider.label'
            )
        ),
        'add' => array(
            'element' => 'form_training_provider',
            'method' => 'add'
        )
    )
);

Configure::write('Searchable.Firm', 
    array(
        'locator' => array(
            'search' => array(
                'Firm.label',
                'Firm.slug',
                'Firm.alias',
                'FirmType.label'
            ),
            'fields' => array(
                'Firm' => 'Firm.label',
                'Alias' => 'Firm.alias',
                'Type' => 'FirmType.label',
                'Modified' => 'Firm.modified'
            ),
            'contain' => array('FirmType','License'),
            'view_vars' => array('firmTypes' => 'getFirmTypes'),
        ),
        'add' => array(
            'element' => 'firm_form',
            'method' => 'addFirm',
            'view_vars' => array('firmTypes' => 'getFirmTypes'),
        ),
        'active_incomplete_expired_firms' => array(
            'conditions' => array(
                'AND' => array(
                    'License.license_status_id' => array(1,4,9),
                    'License.foreign_obj' => 'Firm',
                ),
            )
        )
    )
);

Configure::write('Searchable.Payment', 
    array(
        'locator' => array(
            'search' => array( 
                'Payment.transaction_data', 
                'Payment.identifier',
                'Payment.total',
                'Payment.modified' 
            ),
            'fields' => array(
                'Transaction' => 'Payment.transaction_data', 
                'ID' => 'Payment.identifier',
                'Total' => 'Payment.total',
                'Modified' => 'Payment.modified',
            ),
            'contain' => array('PaymentType')
        ),
        'index' => array(
            'search' => array(
                'Payment.transaction_data', 
                'PaymentType.label', 
                'Payment.identifier',
                'Payment.total',
            ),
            'fields' => array(
                'Payer' => 'Account.label',
                'Payment Type' => 'PaymentType.label',
                'Transaction ID' => 'Payment.identifier',
                'Payment Date' => 'Payment.created',
                'Received Date' => 'Payment.payment_received_date',
                'Entered Date' => 'Payment.payment_date',
                'Total' => 'Payment.total',
                'Amount Paid' => 'Payment.amount_paid',
            ),
            'contain' => array('PaymentType', 'Account'),
            'elements' => array(
                'filter_element' => 'index_action_bar',
            ),
            'view_vars' => array(
                'payment_types' => 'getPaymentTypeList',
                'date_fields' => array(
                    'Payment.created' => 'Payment Date',
                    'Payment.payment_received_date' => 'Received Date',
                    'Payment.payment_date' => 'Entered Date'
                ),
            )
        )
    )
);

/**
 * Output Document Trigger Config
 */
Configure::write('OutputDocuments.triggers',
    array(
        // Master
        'MA_initial'        => array(array('type' => 'acceptance_letter'), array('type' => 'wallet_card')),
        'MA_conversion'     => array(array('type' => 'acceptance_letter'), array('type' => 'wallet_card')),
        'MA_renewal'        => array(array('type' => 'acceptance_letter'), array('type' => 'wallet_card')),
        'MA_renewal_gap'    => array(array('type' => 'acceptance_letter'), array('type' => 'wallet_card')),
        'IMA_initial'       => array(array('type' => 'acceptance_letter'), array('type' => 'wallet_card')),
        'IMA_conversion'    => array(array('type' => 'acceptance_letter'), array('type' => 'wallet_card')),
        
        'MB_initial'        => array(array('type' => 'acceptance_letter'), array('type' => 'wallet_card')),
        'MB_conversion'     => array(array('type' => 'acceptance_letter'), array('type' => 'wallet_card')),
        'MB_renewal'        => array(array('type' => 'acceptance_letter'), array('type' => 'wallet_card')),
        'MB_renewal_gap'    => array(array('type' => 'acceptance_letter'), array('type' => 'wallet_card')),
        'IMB_initial'       => array(array('type' => 'acceptance_letter'), array('type' => 'wallet_card')),
        'IMB_conversion'    => array(array('type' => 'acceptance_letter'), array('type' => 'wallet_card')),
        'IMB_renewal'       => array(array('type' => 'acceptance_letter'), array('type' => 'wallet_card')),
        'IMB_renewal_gap'   => array(array('type' => 'acceptance_letter'), array('type' => 'wallet_card')),
        
        'RM_initial'        => array(array('type' => 'acceptance_letter'), array('type' => 'wallet_card')),
        'RM_conversion'     => array(array('type' => 'acceptance_letter'), array('type' => 'wallet_card')),
        'RM_renewal'        => array(array('type' => 'acceptance_letter'), array('type' => 'wallet_card')),
        'RM_renewal_gap'    => array(array('type' => 'acceptance_letter'), array('type' => 'wallet_card')),
        
        // Journeymen 
        'JA_initial'        => array(array('type' => 'acceptance_letter'), array('type' => 'wallet_card')),
        'JA_conversion'     => array(array('type' => 'acceptance_letter'), array('type' => 'wallet_card')),
        'JA_renewal'        => array(array('type' => 'acceptance_letter'), array('type' => 'wallet_card')),
        'JA_renewal_gap'    => array(array('type' => 'acceptance_letter'), array('type' => 'wallet_card')),
        
        'JB_initial'        => array(array('type' => 'acceptance_letter'), array('type' => 'wallet_card')),
        'JB_conversion'     => array(array('type' => 'acceptance_letter'), array('type' => 'wallet_card')),
        'JB_renewal'        => array(array('type' => 'acceptance_letter'), array('type' => 'wallet_card')),
        'JB_renewal_gap'    => array(array('type' => 'acceptance_letter'), array('type' => 'wallet_card')),
        
        // Electrician
        'SE_initial'        => array(array('type' => 'acceptance_letter'), array('type' => 'special_wallet_card')),
        'SE_conversion'     => array(array('type' => 'acceptance_letter'), array('type' => 'special_wallet_card')),
        'SE_renewal'        => array(array('type' => 'acceptance_letter'), array('type' => 'special_wallet_card')),
        'SE_renewal_gap'    => array(array('type' => 'acceptance_letter'), array('type' => 'special_wallet_card')),
        
        'RE_initial'        => array(array('type' => 'acceptance_letter'), array('type' => 'wallet_card')),
        'RE_conversion'     => array(array('type' => 'acceptance_letter'), array('type' => 'wallet_card')),
        'RE_renewal'        => array(array('type' => 'acceptance_letter'), array('type' => 'wallet_card')),
        'RE_renewal_gap'    => array(array('type' => 'acceptance_letter'), array('type' => 'wallet_card')),
        
        // Apprentice
        'AE_initial'        => array(array('type' => 'acceptance_letter'), array('type' => 'wallet_card')),
        'AE_conversion'     => array(array('type' => 'acceptance_letter'), array('type' => 'wallet_card')),
        'AE_renewal'        => array(array('type' => 'acceptance_letter'), array('type' => 'wallet_card')),
        'AE_renewal_gap'    => array(array('type' => 'acceptance_letter'), array('type' => 'wallet_card')),
        
        // Unclassified
        'UP_initial'        => array(array('type' => 'acceptance_letter'), array('type' => 'wallet_card')),
        'UP_conversion'     => array(array('type' => 'acceptance_letter'), array('type' => 'wallet_card')),
        'UP_renewal'        => array(array('type' => 'acceptance_letter'), array('type' => 'wallet_card')),
        'UP_renewal_gap'    => array(array('type' => 'acceptance_letter'), array('type' => 'wallet_card')),
        
        // Firms
        'EC_initial'        => array(array('type' => 'firm_acceptance_letter'), array('type' => 'contractor_wallet_card')),
        'EC_conversion'     => array(array('type' => 'firm_acceptance_letter'), array('type' => 'contractor_wallet_card')),
        'EC_renewal'        => array(array('type' => 'firm_acceptance_letter'), array('type' => 'contractor_wallet_card')),
        'EC_renewal_gap'    => array(array('type' => 'firm_acceptance_letter'), array('type' => 'contractor_wallet_card')),
        
        'REC_initial'       => array(array('type' => 'firm_acceptance_letter'), array('type' => 'contractor_wallet_card')),
        'REC_conversion'    => array(array('type' => 'firm_acceptance_letter'), array('type' => 'contractor_wallet_card')),
        'REC_renewal'       => array(array('type' => 'firm_acceptance_letter'), array('type' => 'contractor_wallet_card')),
        'REC_renewal_gap'   => array(array('type' => 'firm_acceptance_letter'), array('type' => 'contractor_wallet_card')),
    )
);

/**
 * Output Document Document Config
 */
Configure::write('OutputDocuments.docs',
    array(
        'acceptance_letter' => array(
            'label' => 'Acceptance Letter',
            'data' => 'accountLicenseData',
            'types' => array(
                'pdf' => array(
                    'descr' => '',
                    'elements' => 'acceptance_letter',
                )
            )
        ),
        'wallet_card' => array(
            'label' => 'Wallet Card',
            'data' => 'accountLicenseData',
            'types' => array(
                'csv' => array(
                    'descr' => '',
                    'elements' => 'wallet_card_csv',
                )
            )
        ),
        'contractor_wallet_card' => array(
            'label' => 'Contractor Wallet Card',
            'data' => 'getOutputDocumentData',
            'types' => array(
                'csv' => array(
                    'descr' => '',
                    'elements' => 'contractor_wallet_card_csv',
                )
            )
        ),
        'special_wallet_card' => array(
            'label' => 'Special Wallet Card',
            'data' => 'accountLicenseData',
            'types' => array(
                'csv' => array(
                    'descr' => '',
                    'elements' => 'special_wallet_card_csv',
                )
            )
        ),
        'firm_acceptance_letter' => array(
            'label' => 'Acceptance Letter',
            'data' => 'getOutputDocumentData',
            'types' => array(
                'pdf' => array(
                    'descr' => '',
                    'elements' => 'acceptance_letter',
                )
            )
        )
    )
);