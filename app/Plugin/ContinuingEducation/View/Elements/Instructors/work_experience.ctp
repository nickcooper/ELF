<div id="work_experience" class="content_panel">
    <div class="form_section">
    <h3>Your Work Experience <a href="#" class="help_tag">?</a></h3>
        <table border="0" cellpadding="0" cellspacing="0" class="light_data">
            <thead>
                <tr>
                    <th scope="col">Employer</th>
                    <th scope="col">Position</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($work_experiences as $work_experience) : ?>
                    <tr>
                        <td>
                            <?php 
                            echo $this->Html->link(
                                $work_experience['employer'], 
                                array(
                                    'plugin' => 'accounts', 
                                    'controller' => 'work_experiences', 
                                    'action' => 'edit', 
                                    $work_experience['id'],
                                    'fp' => 'Accounts',
                                    'fo' => 'Account',
                                    'fk' => $work_experience['account_id'],
                                    'return' => base64_encode($this->here)
                                ), 
                                array('title' => 'View experience details')
                            ); 
                            ?>
                        </td>
                    <td><?php echo $work_experience['position']; ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
