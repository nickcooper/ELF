<?php

App::uses('Controller', 'Controller');
App::uses('ComponentCollection', 'Controller');
App::uses('AclComponent', 'Controller/Component');
App::uses('DbAcl', 'Model');
App::uses('Shell', 'Console');

/**
 * AclSetupShell
 *
 * @package    App
 * @subpackage App.Console.Command
 * @author     Iowa Interactive, LLC.
 */
class AclSetupShell extends AppShell
{
    /**
     * Models used by this shell
     *
     * @var array
     * @access public
     */
    public $uses = array('Aro', 'Aco', 'Accounts.Group');

    /**
     * Output all permissions to a csv file. This function accepts a single command line
     * argument specifying the filename of a csv. If a filename is not provided it defaults
     * to permissions.csv.
     *
     * @return void
     */
    public function getPermissions()
    {
        if (empty($this->args[0]))
        {
            $filename = 'permissions.csv';
        }
        else
        {
            $filename = $this->args[0];
        }

        $this->setupAclObjects();

        $groups = $this->Group->find('list');

        $this->out('Getting AROs');
        $aros = $this->Aro->find(
            'all',
            array(
                'conditions' => array('model' => 'Group'),
                'contain' => false,
            )
        );

        $this->out('Getting ACOs');
        $acos = $this->Aco->find(
            'all',
            array(
                'contain' => false,
            )
        );

        $result = '';

        // output group names at the top
        $result .= 'Action';

        foreach ($aros as $aro)
        {
            $group_id = $aro['Aro']['foreign_key'];

            if (isset($groups[$group_id]))
            {
                $result .= ',' . $groups[$group_id];
            }
            else
            {
                $result .= ",Unknown group id $group_id";
            }
        }

        $result .= "\n";

        // loop through each action and output permissions for each group
        foreach ($acos as $aco_index => $aco)
        {
            $path = $this->Aco->getPath($aco['Aco']['id']);
            $aliases = Set::classicExtract($path, '{n}.Aco.alias');
            $aco_alias = join('/', $aliases);

            $action_permissions = "$aco_alias";

            foreach ($aros as $aro_index => $aro)
            {
                $aro_alias = $aro['Aro']['alias'];
                $timestamp = date_format(date_create(), 'Y-m-d H:i:s');
                $this->out("[$timestamp] Getting permissions to $aco_alias for $aro_alias");
                $action_permissions .= ',' . $this->Acl->check($aro_alias, $aco_alias);
            }

            $result .= $action_permissions . "\n";
        }

        file_put_contents($filename, $result);
    }

    /**
     * Input permissions from a csv file. This function accepts a single command line
     * argument specifying the filename of a csv. If a filename is not provided it defaults
     * to permissions.csv. Permissions are set one line at a time. So, for example. a deny of a
     * controller followed by an allow of an action within that controller will result in
     * a deny for all but the specified action.
     *
     * @return void
     */
    public function setPermissions()
    {
        // read file
        if (empty($this->args[0]))
        {
            $filename = 'permissions.csv';
        }
        else
        {
            $filename = $this->args[0];
        }

        if (!$fp = fopen($filename, 'r'))
        {
            throw new Exception('Invalid filename');
        }

        $this->setupAclObjects();
        $contents = fgetcsv($fp);

        // figure out the aro alias for our group names in the first line
        $groups = $this->Group->find('list');
        $aros = $this->Aro->find(
            'all',
            array(
                'conditions' => array('model' => 'Group'),
                'contain' => false,
            )
        );

        $aro_aliases = array();

        foreach ($contents as $i => $group_name)
        {
            if ($group_id = array_search($group_name, $groups))
            {
                foreach ($aros as $aro)
                {
                    if ($aro['Aro']['foreign_key'] == $group_id)
                    {
                        $aro_aliases[$i] = $aro['Aro']['alias'];
                    }
                }
            }
        }

        while ($contents = fgetcsv($fp))
        {
            $aco = $contents[0];

            foreach ($aro_aliases as $index => $aro)
            {
                $timestamp = date_format(date_create(), 'Y-m-d H:i:s');
                if ($contents[$index])
                {
                    //$this->out("[$timestamp] Allowing $aco for $aro");
                    $this->Acl->allow($aro, $aco);
                }
                else
                {
                    //$this->out("[$timestamp] Denying $aco for $aro");
                    $this->Acl->deny($aro, $aco);
                }
            }
        }

        $this->outSuccess('setPermissions completed.');
    }

    /**
     * Set up the ACL objedts that are used through the shell
     *
     * @return void
     */
    private function setupAclObjects()
    {
        if (empty($controller))
        {
            $controller = new Controller(new CakeRequest());
        }

        $collection = new ComponentCollection();
        $this->Acl = new AclComponent($collection);
        $this->Acl->startup($controller);
        $this->Aco = $this->Acl->Aco;
        $this->controller = $controller;
    }
}
