<?php  
class UserGroupSetupShell extends Shell
{ 
    var $uses = array('Accounts.Account','Accounts.Group'); 

    function main()
    {
        $options = array(
            '[A] Add new user');

        $choices = array('A');

        foreach ($options as $option)
        {
            $this->out($option);
        }

        $this->hr();

        $selection = $this->in('Choose an action', $choices, $choices[0]);

        switch($selection)
        {
            case 'A':
                $this->addUser();
                exit;
            default:
                $this->out('Something is messed up. Panic.');
        }
    }

    public function addUser()
    {
        App::import('Component','Auth'); 
        $this->Auth = @(new AuthComponent(null)); 

        $this->out('Create new user'); 
        $this->hr(); 

        // set username
        while (empty($username))
        { 
            $username = $this->in('Username:'); 
            if (empty($username)) $this->out('Username must not be empty'); 
        } 

        // set password
        while (empty($pwd1))
        { 
            $pwd1 = $this->in('Password:'); 
            if (empty($pwd1)) $this->out('Password must not be empty'); 
        } 

        // confirm password
        while (empty($pwd2))
        { 
            $pwd2 = $this->in('Password Confirmation:'); 
            if ($pwd1 !== $pwd2)
            { 
                $this->out('Password and confirmation do not match'); 
                $pwd2 = null; 
            } 
        } 

        // select a group
        $groups = $this->Group->find('list');
        $group_ids = array_keys($groups);

        $this->out();
        $this->out('Available Groups:');
        $this->hr();

        foreach ($groups as $id => $name)
        {
            $this->out("[$id] $name");
        }

        $this->hr(); 

        $group_id = $this->in('Choose a group', $group_ids, $group_ids[0]);
        $data = array(
            'username' => $username,
            'password' => $this->Auth->password($pwd1),
            'group_id' => $group_id);

        $this->Account->create();

        if ($this->Account->save($data))
        { 
            $this->out('User created successfully'); 
        }
        else
        { 
            $this->out('ERROR while creating User'); 
        } 
    } 
} 
?> 
