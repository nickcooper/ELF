<?php echo $this->element('section_nav'); ?>
<div id="section" class="span-19 last">
	<div class="pad">
		<div class="actions">
			<?php
                echo $this->Html->link(
                    __('Finished'),
                    array('controller' => 'accounts', 'action' => 'index'),
                    array('class' => 'button')
                );
            ?>
		</div>
        <div id="account_information" class="content_panel">
        <!-- Start Of Personal Information -->
                <?php
                    // account photo
                    $account_photo = Router::url('/img/photos/default-image.png', true);

                    if (
                        GenLib::isData($account, 'AccountPhoto', array('web_path'))
                        && file_exists(
                            sprintf(
                                '%swebroot/%s/%s',
                                APP,
                                $account['AccountPhoto']['file_path'],
                                $account['AccountPhoto']['file_name']
                            )
                        )
                    ):
                        $account_photo = $account['AccountPhoto']['web_path'];
                    endif;

                    $label = __('Personal Information');
                    // Add anchor var
                    $anchor = strtolower(Inflector::slug($label))."_section";
                    // Add return link
                    $return = base64_encode(sprintf('%s#%s', $this->here, $anchor));
                    echo sprintf('<a name="%s"></a>', $anchor);
                    echo $this->element(
                        'personal_info',
                        array(
                            'label'         => $label,
                            'required'      => null,
                            'account'       => $account['Account'],
                            'account_photo' => $account_photo,
                            'return'        => $return,
                        )
                    );
                ?>
        <!-- Close Of Personal Information -->

        <!-- Start Of Contact Information -->
                <?php
                    $label = __('Contact Information');
                    // Add anchor var
                    $anchor = strtolower(Inflector::slug($label))."_section";
                    // Add return link
                    $return = base64_encode(sprintf('%s#%s', $this->here, $anchor));
                    echo sprintf('<a name="%s"></a>', $anchor);
                    echo $this->element(
                        'AddressBook.addresses',
                        array(
                            'label'          => $label,
                            'required'       => null,
                            'account'        => $account['Account'],
                            'addresses'      => $account['Address'],
                            'description'    => array('description' => $element_descriptions['Addresses'],
                                                        'element_plugin' => 'address_book'),
                            'foreign_plugin' => 'Accounts',
                            'foreign_obj'    => 'Account',
                            'foreign_key'    => $account['Account']['id'],
                            'return'        => $return,
                        )
                    );
                ?>
        <!-- Close Of Personal Information -->

        <!-- Start Of License Info -->
            <?php
                    $label = __('Licenses');
                    // Add anchor var
                    $anchor = strtolower(Inflector::slug($label))."_section";
                    // Add return link
                    $return = base64_encode(sprintf('%s#%s', $this->here, $anchor));
                    echo sprintf('<a name="%s"></a>', $anchor);
                    echo $this->element(
                    'license_info',
                    array(
                        'label'         => $label,
                        'required'      => null,
                        'description'   => array(
                            'description' => "In this section, you can apply for a new license by selecting the Add New License button.  If you have existing licenses, they are listed below.  To view additional information or to Renew or Change your license type (Upgrade/Downgrade), select the link for the license.  From there you will be able to Renew or Change License Type.",
                            'element_plugin' => 'accounts'),
                        'account'       => $account['Account'],
                        'licenses'      => $account['License'],
                        'license_types' => $license_types,
                        'return'        => $return,
                    )
                );
            ?>
        <!-- close #account_licenses -->

        <!-- Start Of Managed Licenses Info -->
            <?php
                    $label = __('Licenses You Manage');
                    // Add anchor var
                    $anchor = strtolower(Inflector::slug($label))."_section";
                    // Add return link
                    $return = base64_encode(sprintf('%s#%s', $this->here, $anchor));
                    echo sprintf('<a name="%s"></a>', $anchor);
                    echo $this->element(
                    'manage_license_info',
                    array(
                        'label'             => $label,
                        'required'          => null,
                        'account'           => $account['Account'],
                        'managed_licenses'  => $managed_licenses,
                        'license_types'     => $license_types,
                        'return'            => $return,
                    )
                );
            ?>
        <!-- close #account_licenses -->
        <!-- Start Of WorkExperience -->
                <?php
                    $label = __('Work Experience');
                    // Add anchor var
                    $anchor = strtolower(Inflector::slug($label))."_section";
                    // Add return link
                    $return = base64_encode(sprintf('%s#%s', $this->here, $anchor));
                    echo sprintf('<a name="%s"></a>', $anchor);
                    echo $this->element(
                        'work_experience',
                        array(
                            'label'       => $label,
                            'required'    => null,
                            'account'     => $account['Account'],
                            'description'    => array(
                                'element_plugin' => 'accounts',
                                'description' => $element_descriptions['Work Experience']
                            ),
                            'experiences' => $account['WorkExperience'],
                            'return'        => $return,
                        )
                    );
                ?>
        <!-- Close Of WorkExperience -->

        <!-- Start Of PracticalWorkExperience -->
                <?php
                    $label = __('Practical Work Experience');
                    // Add anchor var
                    $anchor = strtolower(Inflector::slug($label))."_section";
                    // Add return link
                    $return = base64_encode(sprintf('%s#%s', $this->here, $anchor));
                    echo sprintf('<a name="%s"></a>', $anchor);
                    echo $this->element(
                        'practical_work_experiences',
                        array(
                            'label'       => $label,
                            'required'    => null,
                            'account'     => $account['Account'],
                            'practical_work_experiences' => $account['PracticalWorkExperience'],
                            'return'        => $return,
                        )
                    );
                ?>
        <!-- Close Of WorkExperience -->

        <!-- Start Of Education -->
                <?php
                    $label = __('Education');
                    // Add anchor var
                    $anchor = strtolower(Inflector::slug($label))."_section";
                    // Add return link
                    $return = base64_encode(sprintf('%s#%s', $this->here, $anchor));
                    echo sprintf('<a name="%s"></a>', $anchor);
                    echo $this->element(
                        'education',
                        array(
                            'label'         => $label,
                            'required'      => null,
                            'description'   => array('description' => $element_descriptions['Education'], 'element_plugin' => 'accounts'),
                            'educations'    => $account['EducationDegree'],
                            'account'       => $account['Account'],
                            'return'        => $return,
                        )
                    );
                ?>
        <!-- Close Of WorkEducation -->

        <!-- Start Of OtherLicense -->
                <?php
                    $label = __('Other Licenses');
                    // Add anchor var
                    $anchor = strtolower(Inflector::slug($label))."_section";
                    // Add return link
                    $return = base64_encode(sprintf('%s#%s', $this->here, $anchor));
                    echo sprintf('<a name="%s"></a>', $anchor);
                    echo $this->element(
                        'other_licenses',
                        array(
                            'label'      => $label,
                            'required'   => null,
                            'description'   => array('description' => $element_descriptions['Other Professional Licenses'], 'element_plugin' => 'accounts'),
                            'account'    => $account['Account'],
                            'other_licenses' => $account['OtherLicense'],
                            'return'        => $return,
                        )
                    );
                ?>
        <!-- Close Of OtherLicense -->

        <!-- Start Of Course -->
                <?php
                    $label = __('Courses');
                    // Add anchor var
                    $anchor = strtolower(Inflector::slug($label))."_section";
                    // Add return link
                    $return = base64_encode(sprintf('%s#%s', $this->here, $anchor));
                    echo sprintf('<a name="%s"></a>', $anchor);
                    echo $this->element(
                        'courses_taken',
                        array(
                            'label'         => $label,
                            'required'      => null,
                            'description'   => array('description' => $element_descriptions['Courses'], 'element_plugin' => 'accounts'),
                            'rosters'         => $account['CourseRoster'],
                            'account'         => $account['Account'],
                            'return'        => $return,
                        )
                    );
                ?>
        <!-- close #course Taken -->

        <!-- Start Of Reference -->
                <?php
                    $label = __('References');
                    // Add anchor var
                    $anchor = strtolower(Inflector::slug($label))."_section";
                    // Add return link
                    $return = base64_encode(sprintf('%s#%s', $this->here, $anchor));
                    echo sprintf('<a name="%s"></a>', $anchor);
                    echo $this->element(
                        'references',
                        array(
                            'label'      => $label,
                            'required'   => null,
                            'description'   => array('description' => $element_descriptions['References'], 'element_plugin' => 'accounts'),
                            'account'    => $account['Account'],
                            'references' => $account['Reference'],
                            'return'        => $return,
                        )
                    );
                ?>
        <!-- Close Of WorkEducation -->

        <!-- Start Of Documents -->
                <?php
                    $label = __('Supporting Documentation');
                    // Add anchor var
                    $anchor = strtolower(Inflector::slug($label))."_section";
                    // Add return link
                    $return = base64_encode(sprintf('%s#%s', $this->here, $anchor));
                    echo sprintf('<a name="%s"></a>', $anchor);
                    echo $this->element(
                        'documents',
                        array(
                            'label'     => $label,
                            'required'  => null,
                            'description'    => array('description' => $element_descriptions['Supporting Documents'], 'element_plugin' => 'accounts'),
                            'account'   => $account['Account'],
                            'documents' => $account['Document'],
                            'return'        => $return,
                        )
                    );
                ?>
        <!-- close Documents -->
        </div>

        <div class="actions">
			<?php echo $this->Html->link('Finished', array('controller' => 'accounts', 'action' => 'index'), array('class'=>'button')); ?>
		</div>
	</div>
</div>
