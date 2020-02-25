<?php
/**
 * License Behavior.
 *
 * Enables a model to act as a license foreign object.
 *
 * @package App.Model.Behavior
 * @author  Iowa Interactive, LLC.
 */
class LicenseBehavior extends ModelBehavior
{
    /**
     * Required table fields
     */
    private $required_table_fields = array('no_public_contact', 'no_mail', 'label');

    /**
     * behavior setup
     *
     * @param array $Model  cake sets this param on it's own
     * @param array $config defined in the actAs array of the model loading this behavior
     *
     * @return void
     * @access public
     */
    public function setup(Model $Model, $config=array())
    {
        // default settings
        if (!isset($this->settings[$Model->alias]))
        {
            $this->settings[$Model->alias] = array(
                'contain' => array(),
            );
        }

        // overwrite default settings with passed settings
        $this->settings[$Model->alias] = array_merge($this->settings[$Model->alias], (array) $config);

        // Any model acting as a License must contain the required fields
        foreach ($this->required_table_fields as $field)
        {
            if (!array_key_exists($field, $Model->schema()))
            {
                throw new Exception(sprintf('Table (%s) acting as License is missing field (%s).', $Model->table, $field));
            }
        }
    }

    /**
     * getForeignObjContain method
     *
     * @param obj $obj foreign obj object
     *
     * @return the defined foreign obj query contain array
     * @access public
     */
    public function getForeignObjContain($obj = null)
    {
        return $this->settings[get_class($obj)]['license']['contain'];
    }

    /**
     * afterSave method
     *
     * @param Model $Model   Instantiated model
     * @param array $created this is handled by cakephp, true if created new record, false if updated a record
     *
     * @return void
     * @access public
     */
    public function afterSave(Model $Model, $created=null)
    {
        // update the license holder for license records
        if ($created == false)
        {
            if (isset($Model->data[$Model->alias]['label']))
            {
                // initialize License object and find all licenses associated with the Foreign model object,
                // exclude any foreign object data
                $License = ClassRegistry::init('Licenses.License');
                $License->includeForeignData = false;
                $licenses = $License->find(
                    'all',
                    array(
                        'conditions' => array(
                            'License.foreign_obj' => $Model->alias,
                            'License.foreign_key' => $Model->data[$Model->alias]['id']
                        ),
                    )
                );

                // reset global variable to include any foreign object data
                $License->includeForeignData = true;

                // loop through each license and dispatch event to update the License.label field for that license
                foreach($licenses as $license)
                {
                    // dispatch the after save event to update the License.label field
                    $Model->dispatch(
                        'Model-License-afterSave',
                        array(
                            'License.label' => sprintf('%s', $Model->data[$Model->alias]['label']),
                            'License.id' => $license['License']['id']
                        )
                    );
                }
            }
        }
    }
}