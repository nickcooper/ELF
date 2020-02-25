<div id="education" class="content_panel">
    <div class="form_section">
        <h3>Your Education <a href="#" class="help_tag">?</a></h3>
        <table class="vertical_data" border="0" cellspacing="0" cellpadding="0">
            <tbody><tr>
                <th scope="row">Highest Completed Education</th>
                <th scope="row">Transcript</th>
            </tr>
            <tr>
                <td><?php echo $education_degree['degree']; ?></td>
                <td>
                    <?php 
                        if (GenLib::isData($education_degree, 'Upload.0', array('id'))) :
                            echo $this->Html->link(
                                'View Transcript',
                                sprintf('/files/%s', $education_degree['Upload'][0]['file_name']),
                                array(
                                    'title' => 'View Transcript Document', 
                                    'target' => '_blank',
                                    'class' => 'iconify pdf',
                                )
                            );
                        endif;
                    ?>
                </td>
            </tr>
        </tbody></table>
        
            
        <h3>Education Certificates</h3>
        <table class="vertical_data" border="0" cellspacing="0" cellpadding="0">
            <tbody>
                <tr>
                    <th scope="row">Certification</th>
                    <th scope="row">Document</th>
                    <th scope="row">Actions</th>
                </tr>
                <?php if (GenLib::isData($education_degree, 'EducationCertificate.0', array('id'))) : ?>
                    <?php foreach($education_degree['EducationCertificate'] as $cert) : ?>
                <tr>
                    <td>
                        <?php echo $cert['certificate']; ?>
                    </td>
                    <td>
                        <?php 
                            echo $this->Html->link(
                                'View Certificate',
                                sprintf('/files/%s', $cert['Upload'][0]['file_name']),
                                array(
                                    'title' => 'View Certificate Document', 
                                    'target' => '_blank',
                                    'class' => 'iconify pdf',
                                )
                            );
                        ?>
                    </td>
                    <td width="85">
                        <?php 
                            echo $this->Html->link(
                                'Remove', 
                                array(
                                    'plugin' => 'accounts', 
                                    'controller' => 'educations', 
                                    'action' => 'delete', 
                                    $cert['id'],
                                    'return' => base64_encode($this->here)
                                ), 
                                array('class' => 'iconify warning'), __('Are you sure you want to delete certificate #%s?', $cert['id']), array('title' => 'Remove Certificate')
                            ); 
                        ?>
                    </td>
                </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
        </tbody></table>
    </div>
</div>
