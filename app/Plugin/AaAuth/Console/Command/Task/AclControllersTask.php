<?php
Configure::write('Cache.disable', true);

/**
 * AclControllersTask
 *
 * @package    App.Plugin.AaAuth
 * @subpackage App.Plugin.AaAuth.Console.Command
 * @author     Iowa Interactive, LLC.
 */
class AclControllersTask extends AppShell
{
    public $name = 'AclControllersTask';
    public $uses = array('Account','Aro','Group', 'Aco', 'Acl', 'AclComponent');

    /**
      * Main entry point
      *
      * @return void
      * @access public
      */
    public function execute()
    {
        //Step 1.) Build user groups in the Groups table
        $group_data = array(
            array('program_id' => '', 'program_group_id' => '', 'label' => 'Default Group', 'descr' => 'All new accounts are assigned to this group. No admin/special permissions.', 'enabled' => '1'),
            array('program_id' => '', 'program_group_id' => '', 'label' => 'Super Admin', 'descr' => 'Iowa Interactive staff accounts only. Full application access.', 'enabled' => '1'),
            array('program_id' => '1', 'program_group_id' => '1', 'label' => 'Program Admin (Bureau of Lead Poisoning Prevention)', 'descr' => 'Individual program administrative group.', 'enabled' => '1'),
            array('program_id' => '4', 'program_group_id' => '4', 'label' => 'Public Users', 'descr' => 'Public Users', 'enabled' => '1')
        );

        $this->Group->saveAll($group_data);

        //retrieve new groups, now with Group IDs
        $individual_groups = $this->Group->find('all');

        //create aro for each new group
        foreach ($individual_groups as $ig)
        {
            $group_aro_data = array(array('parent_id' => '', 'model' => 'Group', 'foreign_key' => $ig['Group']['id'], 'alias' => $ig['Group']['label']));

            $this->Aro->saveAll($group_aro_data);
        }

        //Step 2.) Build Iowa Interactive user accounts and assign them to the Super Admin group
        $account_data = array(
            array('group_id' => '2', 'username' => 'jacob.grady@iowaid', 'title' => 'Mr.', 'label' => 'Grady, Jacob', 'first_name' => 'Jacob', 'last_name' => 'Grady', 'middle_initial' => 'W', 'email' => 'jgrady@iowai.org', 'ssn_last_four' => '9999', 'dob' => '1969-12-31', 'enabled' => '1', 'probation' => '0'),
            array('group_id' => '2', 'username' => 'adam.knaack@iowaid', 'title' => 'Mr.', 'label' => 'Knaack, Adam', 'first_name' => 'Adam', 'last_name' => 'Knaack', 'middle_initial' => '', 'email' => 'adam@iowai.org', 'ssn_last_four' => '9999', 'dob' => '1969-12-31', 'enabled' => '1', 'probation' => '0'),
            array('group_id' => '2', 'username' => 'jpuls@iowaid', 'title' => 'Ms.', 'label' => 'Puls, Jessica', 'first_name' => 'Jessica', 'last_name' => 'Puls', 'middle_initial' => '', 'email' => 'jpuls@iowai.org', 'ssn_last_four' => '9999', 'dob' => '1969-12-31', 'enabled' => '1', 'probation' => '0'),
            array('group_id' => '2', 'username' => 'andrew@iowaid', 'title' => 'Mr.', 'label' => 'Meyer, Andrew', 'first_name' => 'Andrew', 'last_name' => 'Meyer', 'middle_initial' => '', 'email' => 'andrew@iowai.org', 'ssn_last_four' => '9999', 'dob' => '1969-12-31', 'enabled' => '1', 'probation' => '0'),
            array('group_id' => '2', 'username' => 'enrique24.ramirez@iowaid', 'title' => 'Mr.', 'label' => 'Ramirez, Enrique', 'first_name' => 'Enrique', 'last_name' => 'Ramirez', 'middle_initial' => '', 'email' => 'eramirez@iowai.org', 'ssn_last_four' => '9999', 'dob' => '1969-12-31', 'enabled' => '1', 'probation' => '0'),
            array('group_id' => '2', 'username' => 'nick.cooper@iowaid', 'title' => 'Mr.', 'label' => 'Cooper, Nicholas', 'first_name' => 'Nicholas', 'last_name' => 'Cooper', 'middle_initial' => '', 'email' => 'ncooper@iowai.org', 'ssn_last_four' => '9999', 'dob' => '1969-12-31', 'enabled' => '1', 'probation' => '0'),
            array('group_id' => '2', 'username' => 'rick.rosno@iowaid', 'title' => 'Mr.', 'label' => 'Rosno, Rick', 'first_name' => 'Rick', 'last_name' => 'Rosno', 'middle_initial' => '', 'email' => 'rick@iowai.org', 'ssn_last_four' => '9999', 'dob' => '1969-12-31', 'enabled' => '1', 'probation' => '0'),
            array('group_id' => '2', 'username' => 'joel.jernstad@iowaid', 'title' => 'Mr.', 'label' => 'Jernstad, Joel', 'first_name' => 'Joel', 'last_name' => 'Jernstad', 'middle_initial' => '', 'email' => 'jjernstad@iowai.org', 'ssn_last_four' => '9999', 'dob' => '1969-12-31', 'enabled' => '1', 'probation' => '0'),
            array('group_id' => '2', 'username' => 'mike.biegger@iowaid', 'title' => 'Mr.', 'label' => 'Biegger, Mike', 'first_name' => 'Mike', 'last_name' => 'Biegger', 'middle_initial' => '', 'email' => 'michael.biegger@iowai.org', 'ssn_last_four' => '9999', 'dob' => '1969-12-31', 'enabled' => '1', 'probation' => '0'),
            array('group_id' => '2', 'username' => 'jason.robinson@iowaid', 'title' => 'Mr.', 'label' => 'Robinson, Jason', 'first_name' => 'Jason', 'last_name' => 'Robinson', 'middle_initial' => 'S', 'email' => 'jrobinson@iowai.org', 'ssn_last_four' => '9999', 'dob' => '1969-12-31', 'enabled' => '1', 'probation' => '0')
        );

        $this->Account->saveAll($account_data);


        //build Iowa Interactive user aros and assign them to the Super Admin group
        $individual_accounts = $this->Account->find('all', array('conditions' => array('group_id' => '2')));

        foreach ($individual_accounts as $ia)
        {
            $superAdmin_aro_data = array(array('parent_id' => '2', 'model' => 'Account', 'foreign_key' => $ia['Account']['id'], 'alias' => $ia['Account']['username']));

            $this->Aro->saveAll($superAdmin_aro_data);
        }

        //Step 3.) - Delete all current acos, if any, and rebuild the new ones
        $acos_list = $this->Aco->find('all');

        //delete them
        foreach ($acos_list as $aco)
        {
            $this->Aco->delete($aco['Aco']['id']);
        }

        //manually rebuild all existing acos

        $this->buildAllAcos();

        //Step 4.) Grant permission to every method/action/aco for the Super Admin and Program Admin user groups
        $this->dispatchShell('acl', 'grant', '-v', 'Group.2', 'controllers');
        $this->dispatchShell('acl', 'grant', '-v', 'Group.3', 'controllers');

    } // end function execute()

    /**
     * Build data parameters based on node type
     *
     * @return void
     */
    public function buildAllAcos()
    {
        $this->dispatchShell('acl', 'create', '-v', 'aco', '', 'controllers');
    }

} // end class AclControllersShell