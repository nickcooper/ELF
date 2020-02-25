<?php
/**
 * ConfigurationController
 *
 * @category Configuration
 * @package  Configuration.Controller
 * @author   Iowa Interactive, LLC.
 */
class ConfigurationController extends ConfigurationAppController
{
    /**
     * Controller name
     *
     * @var string
     * @access public
     */
    public $name = 'Configuration';

    /**
     * Autoload models
     *
     * @var array
     * @access public
     */
    public $uses = array('Configuration.Configuration');

    /**
     * index method
     *
     * List of plugins and programs
     *
     * @return void
     */
    public function index()
    {

    }

    /**
     * core method
     *
     * List of core configurations
     *
     * @return void
     */
    public function core()
    {
        // get a list of options
        $options = $this->Configuration->find('all', array('order' => array('name', 'program_id')));
            
        // process the form submit
        if ($this->request->data)
        {
            $data = array();
            
            // loop through the groups and format for save
            $allowed_login_group_rec = $this->Configuration->findByName('allowed_login_groups');
            $id = $allowed_login_group_rec['Configuration']['id'];
            $group_ids = array();
            foreach ($this->request->data['Group'] as $value)
            {
                if ($value)
                {
                    $group_ids[] = $value;
                }
            }
            $data[$id]['Configuration']['id'] = $id;
            $data[$id]['Configuration']['value'] = implode(',', $group_ids);
            
            // loop through the data and format for save
            foreach ($this->request->data['Configuration'] as $id => $value)
            {
                $data[$id]['Configuration']['id'] = $id;
                $data[$id]['Configuration']['value'] = $value;
            }
            
            if (!$this->Configuration->saveMany($data))
            {
                $this->Session->setFlash('Failed to update configurations.');
            }
            else 
            {
                $this->Session->setFlash('Updated configurations.');
            }
            
            // reload the configurations
            $this->loadConfigs();
            
            // get the newly saved options
            $options = $this->Configuration->find('all', array('order' => array('name', 'program_id')));
        }
        
        // get a list of groups
        $Group = ClassRegistry::init('Accounts.Group', 'Model');
        $groups = $Group->find('all', array('order' => 'FIELD(label, "Super Admin") DESC'));
        $this->set('groups', $groups);
        
        // get a list of programs
        $Program = ClassRegistry::init('Accounts.Program', 'Model');
        $programs = $Program->find('list');
        $this->set('programs', $programs);
        
        // group the options by program
        $program_options = array();
        
        foreach ($options as $option)
        {
            $key = (!empty($option['Configuration']['program_id']) ? $option['Configuration']['program_id'] : 0);
            $sub_key = $option['Configuration']['id'];
            
            $program_options[$key][$sub_key] = $option;
            
            // unserialize options
            if (is_array(unserialize($option['Configuration']['options'])))
            {
                $values = array();
                foreach (unserialize($option['Configuration']['options']) as $option)
                {
                    $values[$option] = $option;
                }
                
                $program_options[$key][$sub_key]['Configuration']['options'] = $values;
            }
        }
        
        $this->set('program_options', $program_options);
        
        // exclude some options from displaying in the form
        $this->set('exclude', array('allowed_login_groups'));
    }

    /**
     * plugin method
     *
     * List of plugin configurations
     *
     * @return void
     */
    public function plugin()
    {

    }

    /**
     * program method
     *
     * List of program configurations
     *
     * @return void
     */
    public function program()
    {

    }
}