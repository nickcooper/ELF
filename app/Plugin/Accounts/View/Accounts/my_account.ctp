<div id="pre">
    <div class="span-5">
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
                    'public_personal_info',
                    array(
                        'label'         => $label,
                        'required'      => null,
                        'account'       => $account['Account'],
                        'account_photo' => $account_photo,
                        'fo_link'       => $fo_link,
                        'return'        => $return,
                    )
                );
            ?>
        <!-- Close Of Personal Information -->
    </div>
</div>
<div id="section" class="span-19 last">
    <div class="pad">
        <div id="account_information" class="content_panel">

        <!-- Start Of Contact Information -->
            <?php
                if ($account['Address']) :
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
                endif;
            ?>
        <!-- Close Of Personal Information -->

        <!-- Start Of License Info -->
            <?php
                $label = __('Your Licenses');
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

        <!-- Start Of License I Manage Info -->
            <?php
                /*
                if ($managed_licenses) :
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
                            'return'        => $return,
                        )
                    );
                endif;
                */
            ?>
        <!-- close #account_licenses -->

        <!-- Start Of Application Info -->
            <?php
                $label = __('Your Applications');
                // Add anchor var
                $anchor = strtolower(Inflector::slug($label))."_section";
                // Add return link
                $return = base64_encode(sprintf('%s#%s', $this->here, $anchor));
                echo sprintf('<a name="%s"></a>', $anchor);
                echo $this->element(
                    'application_info',
                    array(
                        'label'         => $label,
                        'required'      => null,
                        'description'   => array(
                            'description' => "Enter application description here",
                            'element_plugin' => 'accounts'),
                        'account'       => $account['Account'],
                        'licenses'      => $account['License'],
                        'applications'  => $app_info,
                        'return'        => $return,
                    )
                );
            ?>
        <!-- close #account applications -->

        <!-- Start Of WorkExperience -->
            <?php
                if ($account['WorkExperience']) :
                    $label = __('Your Work Experience');
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
                                'description'   => array('description' => $element_descriptions['Work Experience'], 'element_plugin' => 'accounts'),
                            'account'     => $account['Account'],
                            'experiences' => $account['WorkExperience'],
                            'return'        => $return,
                        )
                    );
                endif;
            ?>
        <!-- Close Of WorkExperience -->

        <!-- Start Of PracticalWorkExperience -->
            <?php
                if ($account['PracticalWorkExperience']) :
                    $label = __('Your Practical Work Experience');
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
                endif;
            ?>
        <!-- Close Of WorkExperience -->

        <!-- Start Of Education -->
                <?php
                    if ($account['EducationDegree']) :
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
                    endif;
                ?>
        <!-- Close Of Education -->

        <!-- Start Of OtherLicense -->
            <?php
                if ($account['OtherLicense']) :
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
                endif;
            ?>
        <!-- Close Of OtherLicense -->

        <!-- Start Of Course -->
            <?php
                if ($account['CourseRoster']) :
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
                endif;
            ?>
        <!-- close #course Taken -->

        <!-- Start Of Reference -->
            <?php
                if ($account['Reference']) :
                    $label = __('Your References');
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
                endif;
            ?>
        <!-- Close Of WorkEducation -->

        <!-- Start Of Documents -->
            <?php
                if ($account['Document']) :
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
                endif;
            ?>
        <!-- close Documents -->

        <!-- Start Of Insurance Information -->
            <?php
                if ($account['InsuranceInformation']) :
                    $label = __('Your Insurance Information');
                    // Add anchor var
                    $anchor = strtolower(Inflector::slug($label))."_section";
                    // Add return link
                    $return = base64_encode(sprintf('%s#%s', $this->here, $anchor));
                    echo sprintf('<a name="%s"></a>', $anchor);
                    echo $this->element(
                        'insurance_information',
                        array(
                            'label'     => $label,
                            'required'  => null,
                            'account'   => $account['Account'],
                            'insurances' => $account['InsuranceInformation'],
                            'return'        => $return,
                        )
                    );
                endif;
            ?>
        <!-- close Insurance Information -->
        </div>
	</div>
</div>
