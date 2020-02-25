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
Configure::write('ContinuingEducation.minimal.instructor', false);
Configure::write('ContinuingEducation.minimal.success_mail_list', array());

/**
 * Variant License Numbers
 */
Configure::write('Licenses.license_number_variants', true);

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
                    'License.foreign_obj' => 'Account',      //Individual license types only
                )
            )
        ),
        'custom_filter' => array(
            'conditions' => array(
                'AND' => array(
                    'License.license_status_id' => '1',      //Active licenses only
                    'License.foreign_obj' => 'Account',      //Individual license types only
                )
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
                'SSN' => 'Account.ssn_last_four',
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
        'text' => array(
            'description' => 'Search for the Firm you wish to select. If you do not find the Firm you are looking for, 
                            you will have the ability to create a new Firm. You can do this by returning to the My Account 
                            page and selecting Firm from the Add New License drop-down.',
        ),
        'active_incomplete_expired_firms' => array(
            'conditions' => array(
                'AND' => array(
                    'License.license_status_id' => array(1,4,9),
                    'License.foreign_obj' => 'Firm',
                )
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
                'Payment Id' => 'Payment.id',
                'Payment Type' => 'PaymentType.label',
                'Transaction ID' => 'Payment.identifier',
                'Payment Date' => 'Payment.created',
                'Received Date' => 'Payment.payment_received_date',
                'Entered Date' => 'Payment.payment_date',
                'Total' => 'Payment.total',
                'Amount Paid' => 'Payment.amount_paid',
            ),
            'contain' => array('PaymentType'),
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
        // Abatements
        'abatement_initial' => array(array('type' => 'abatement_approval_letter')),
        'abatement_reminder' => array(array('type' => 'abatement_reminder_letter')),
        'abatement_revised' => array(array('type' => 'revised_abatement_notification_letter')),
        'abatement_revised_multi_phased' => array(array('type' => 'revised_multi_phased_abatement_notification_letter')),
        // Accounts
        'INSP_initial' => array(array('type' => 'initial_certification_letter')),
        'INSP_conversion' => array(array('type' => 'initial_certification_letter')),
        'SAMP_initial' => array(array('type' => 'initial_certification_letter')),
        'SAMP_conversion' => array(array('type' => 'initial_certification_letter')),
        'CONT_initial' => array(array('type' => 'initial_certification_letter')),
        'CONT_conversion' => array(array('type' => 'initial_certification_letter')),
        'WORK_initial' => array(array('type' => 'initial_certification_letter')),
        'WORK_conversion' => array(array('type' => 'initial_certification_letter')),
        'LSR_initial' => array(array('type' => 'initial_certification_letter')),
        'LSR_conversion' => array(array('type' => 'initial_certification_letter')),
        'INSP_renewal' => array(array('type' => 'renewal_certification_letter')),
        'SAMP_renewal' => array(array('type' => 'renewal_certification_letter')),
        'CONT_renewal' => array(array('type' => 'renewal_certification_letter')),
        'WORK_renewal' => array(array('type' => 'renewal_certification_letter')),
        'LSR_renewal' => array(array('type' => 'renewal_certification_letter')),
        'INSP_renewal_gap' => array(array('type' => 'renewal_gap_certification_letter'),),
        'SAMP_renewal_gap' => array(array('type' => 'renewal_gap_certification_letter')),
        'CONT_renewal_gap' => array(array('type' => 'renewal_gap_certification_letter')),
        'WORK_renewal_gap' => array(array('type' => 'renewal_gap_certification_letter')),
        'LSR_renewal_gap' => array(array('type' => 'renewal_gap_certification_letter')),
        'INSP_interim' => array(array('type' => 'interim_certification_letter')),
        'SAMP_interim' => array(array('type' => 'interim_certification_letter')),
        'CONT_interim' => array(array('type' => 'interim_certification_letter')),
        'WORK_interim' => array(array('type' => 'interim_certification_letter')),
        'LSR_interim' => array(array('type' => 'interim_certification_letter')),
        'individual_renewal_reminder' => array(array('type' => 'renewal_reminder_letter')),
        'individual_refresher_reminder' => array(array('type' => 'refresher_reminder_letter')),
        'individual_late_reminder' => array(array('type' => 'late_reminder_letter')),
        // Firms
        'FIRM_initial' => array(
            array('type' => 'firm_initial_certification_letter'),
            array('type' => 'firm_initial_certification_certificate'),
        ),
        'FIRM_renewal' => array(
            array('type' => 'firm_renewal_certification_letter'),
            array('type' => 'firm_renewal_certification_certificate'),
        ),
        'FIRM_renewal_gap' => array(
            array('type' => 'firm_renewal_gap_certification_letter'),
            array('type' => 'firm_renewal_gap_certification_certificate'),
        ),
        'firm_renewal_reminder' => array(array('type' => 'firm_renewal_reminder_letter')),
        'firm_late_reminder' => array(array('type' => 'firm_late_reminder_letter')),
        // Training Providers
        'TRAIN_initial' => array(
            array('type' => 'training_provider_approval_letter'),
            array('type' => 'training_provider_approval_certificate'),
        ),
        'TRAIN_renewal' => array(
            array('type' => 'training_provider_renewal_approval_letter'),
            array('type' => 'training_provider_renewal_certificate')),
        'training_provider_renewal_reminder' => array(array('type' => 'training_provider_renewal_reminder_letter')),
        'training_provider_renovator' => array(array('type' => 'renovator_trained_letter'))
    )
);

/**
 * Output Document Document Config
 */
Configure::write('OutputDocuments.docs',
    array(
        // Abatements
        'abatement_approval_letter' => array(
            'label' => 'Abatement Approval Letter',
            'data' => 'getOutputDocumentData',
            'types' => array(
                'pdf' => array(
                    'descr' => '',
                    'layout' => '',
                    'elements' => 'abatement_approval_letter',
                )
            )
        ),
        'abatement_reminder_letter' => array(
            'label' => 'Abatement Reminder Letter',
            'data' => 'getOutputDocumentData',
            'types' => array(
                'pdf' => array(
                    'descr' => '',
                    'layout' => '',
                    'elements' => 'abatement_reminder_letter',
                )
            )
        ),
        'revised_abatement_notification_letter' => array(
            'label' => 'Revised Abatement Notification Letter',
            'data' => 'getOutputDocumentData',
            'types' => array(
                'pdf' => array(
                    'descr' => '',
                    'layout' => '',
                    'elements' => 'revised_abatement_notification_letter',
                )
            )
        ),
        'revised_multi_phased_abatement_notification_letter' => array(
            'label' => 'Revised Multi Phased Abatement Notification Letter',
            'data' => 'getOutputDocumentData',
            'types' => array(
                'pdf' => array(
                    'descr' => '',
                    'layout' => '',
                    'elements' => 'revised_multi_phased_abatement_notification_letter',
                )
            )
        ),
        // Accounts
        'initial_certification_letter' => array(
            'label' => 'Initial Certification Letter',
            'data' => 'accountLicenseData',
            'types' => array(
                'pdf' => array(
                    'descr' => '',
                    'layout' => '',
                    'elements' => 'initial_certification_letter',
                )
            )
        ),
        'renewal_certification_letter' => array(
            'label' => 'Renewal Certification Letter',
            'data' => 'accountLicenseData',
            'types' => array(
                'pdf' => array(
                    'descr' => '',
                    'layout' => '',
                    'elements' => 'renewal_certification_letter',
                )
            )
        ),
        'renewal_gap_certification_letter' => array(
            'label' => 'Renewal Gap Certification Letter',
            'data' => 'accountLicenseData',
            'types' => array(
                'pdf' => array(
                    'descr' => '',
                    'layout' => '',
                    'elements' => 'renewal_gap_certification_letter',
                )
            )
        ),
        'interim_certification_letter' => array(
            'label' => 'Interim Certification Letter',
            'data' => 'accountLicenseData',
            'types' => array(
                'pdf' => array(
                    'descr' => '',
                    'layout' => '',
                    'elements' => 'interim_certification_letter',
                )
            )
        ),
        'renewal_reminder_letter' => array(
            'label' => 'Renewal Reminder Letter',
            'data' => 'accountLicenseData',
            'types' => array(
                'pdf' => array(
                    'descr' => '',
                    'layout' => '',
                    'elements' => 'renewal_reminder_letter',
                )
            )
        ),
        'refresher_reminder_letter' => array(
            'label' => 'Refresher Reminder Letter',
            'data' => 'accountLicenseData',
            'types' => array(
                'pdf' => array(
                    'descr' => '',
                    'layout' => '',
                    'elements' => 'refresher_reminder_letter',
                )
            )
        ),
        'late_reminder_letter' => array(
            'label' => 'Late Reminder Letter',
            'data' => 'accountLicenseData',
            'types' => array(
                'pdf' => array(
                    'descr' => '',
                    'layout' => '',
                    'elements' => 'late_reminder_letter',
                )
            )
        ),
        // Firms
        'firm_initial_certification_letter' => array(
            'label' => 'Firm Initial Certification Letter',
            'data' => 'getOutputDocumentData',
            'types' => array(
                'pdf' => array(
                    'descr' => '',
                    'layout' => '',
                    'elements' => 'firm_initial_certification_letter',
                )
            )
        ),
        'firm_initial_certification_certificate' => array(
            'label' => 'Firm Initial Certification Certificate',
            'data' => 'getOutputDocumentData',
            'types' => array(
                'pdf' => array(
                    'descr' => '',
                    'layout' => '',
                    'elements' => 'firm_initial_certification_certificate',
                )
            )
        ),
        'firm_late_reminder_letter' => array(
            'label' => 'Firm Late Reminder Letter',
            'data' => 'getOutputDocumentData',
            'types' => array(
                'pdf' => array(
                    'descr' => '',
                    'layout' => '',
                    'elements' => 'firm_late_reminder_letter',
                )
            )
        ),
        'firm_renewal_certification_letter' => array(
            'label' => 'Firm Renewal Certification Letter',
            'data' => 'getOutputDocumentData',
            'types' => array(
                'pdf' => array(
                    'descr' => '',
                    'layout' => '',
                    'elements' => 'firm_renewal_certification_letter',
                )
            )
        ),
        'firm_renewal_certification_certificate' => array(
            'label' => 'Firm Renewal Certification Certificate',
            'data' => 'getOutputDocumentData',
            'types' => array(
                'pdf' => array(
                    'descr' => '',
                    'layout' => '',
                    'elements' => 'firm_renewal_certification_certificate',
                )
            )
        ),
        'firm_renewal_gap_certification_letter' => array(
            'label' => 'Firm Renewal Gap Certification Letter',
            'data' => 'getOutputDocumentData',
            'types' => array(
                'pdf' => array(
                    'descr' => '',
                    'layout' => '',
                    'elements' => 'firm_renewal_gap_certification_letter',
                )
            )
        ),
        'firm_renewal_gap_certification_certificate' => array(
            'label' => 'Firm Renewal Gap Certification Certificate',
            'data' => 'getOutputDocumentData',
            'types' => array(
                'pdf' => array(
                    'descr' => '',
                    'layout' => '',
                    'elements' => 'firm_renewal_gap_certification_certificate',
                )
            )
        ),
        'firm_renewal_reminder_letter' => array(
            'label' => 'Firm Renewal Reminder Letter',
            'data' => 'getOutputDocumentData',
            'types' => array(
                'pdf' => array(
                    'descr' => '',
                    'layout' => '',
                    'elements' => 'firm_renewal_reminder_letter',
                )
            )
        ),
        // Training Providers
        'training_provider_approval_letter' => array(
            'label' => 'Training Provider Approval Letter',
            'data' => 'getOutputDocumentData',
            'types' => array(
                'pdf' => array(
                    'descr' => '',
                    'layout' => '',
                    'elements' => 'training_provider_approval_letter',
                )
            )
        ),
        'training_provider_approval_certificate' => array(
            'label' => 'Training Provider Approval Certificate',
            'data' => 'getOutputDocumentData',
            'types' => array(
                'pdf' => array(
                    'descr' => '',
                    'layout' => '',
                    'elements' => 'training_provider_approval_certificate',
                )
            )
        ),
        'training_provider_renewal_approval_letter' => array(
            'label' => 'Training Provider Renewal Approval Letter',
            'data' => 'getOutputDocumentData',
            'types' => array(
                'pdf' => array(
                    'descr' => '',
                    'layout' => '',
                    'elements' => 'training_provider_renewal_approval_letter',
                )
            )
        ),
        'training_provider_renewal_certificate' => array(
            'label' => 'Training Provider Renewal Certificate',
            'data' => 'getOutputDocumentData',
            'types' => array(
                'pdf' => array(
                    'descr' => '',
                    'layout' => '',
                    'elements' => 'training_provider_renewal_certificate',
                )
            )
        ),
        'training_provider_renewal_reminder_letter' => array(
            'label' => 'Training Provider Renewal Reminder Letter',
            'data' => 'getOutputDocumentData',
            'types' => array(
                'pdf' => array(
                    'descr' => '',
                    'layout' => '',
                    'elements' => 'training_provider_renewal_reminder_letter',
                )
            )
        ),
        'renovator_trained_letter' => array(
            'label' => 'Renovator Trained Letter',
            'data' => 'getOutputDocumentData',
            'types' => array(
                'pdf' => array(
                    'descr' => '',
                    'layout' => '',
                    'elements' => 'renovator_trained_letter',
                )
            )
        ),
    )
);