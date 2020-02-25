<?php
/**
 * Managers Controller
 *
 * @category Account
 * @package  App.Controller
 * @author   Iowa Interactive, LLC.
 */
class ManagersController extends AppController
{
    /**
     * Controller name
     *
     * @var string
     * @access public
     */
    public $name = 'Managers';

    /**
     * Autoload models
     *
     * @var array
     * @access public
     */
    public $uses = array('Accounts.Manager', 'Licenses.License');

    /**
     * Autoload components
     *
     * @var array
     * @access public
     */
    public $components = array('ForeignObject');

    /**
     * add method
     *
     * @return void
     *
     * @todo When adding the very first manager automatically assign
     * them as primary manager.
     */
    public function add()
    {
        // do we have a searchable id?
        $searchable_id = null;
        if (isset($this->params['named']['searchable']))
        {
            $searchable_id = $this->params['named']['searchable'];
        }
        elseif ($this->request->is('post') && isset($this->request->data['Searchable']))
        {
            $searchable_id = $this->request->data['Searchable'];
        }

        // check for Searchable id
        if ($searchable_id)
        {
            // check to see if the account is already a manager
            if ($manager = $this->Manager->getManager($searchable_id, $this->foreign_obj, $this->foreign_key))
            {
                // account is already a manager for foreign obj
                $this->Session->setFlash(
                    sprintf('The selected account is already a manager of this %s.', $this->humanized_foreign_obj)
                );
            }
            else
            {
                // grab a count of all managers for foreign obj - set primary if none found
                $primary = !($this->Manager->getManagers($this->foreign_obj, $this->foreign_key));

                // format the data
                $this->Manager->create();
                $this->Manager->set('account_id', $searchable_id);
                $this->Manager->set('foreign_plugin', $this->foreign_plugin);
                $this->Manager->set('foreign_obj', $this->foreign_obj);
                $this->Manager->set('foreign_key', $this->foreign_key);
                $this->Manager->set('primary_flag', $primary);

                // add the account id to the managers table
                if ($this->Manager->add($this->Manager->data))
                {
                    // success
                    $this->Session->setFlash(
                        sprintf(
                            'The new manager was added to the %s.', preg_replace('/([A-Z])/', ' $1', $this->foreign_obj)
                        )
                    );
                }
                else
                {
                    // fail
                    $this->Session->setFlash(
                        sprintf(
                            'Failed to add new manager to %s',
                            $this->foreign_obj
                        )
                    );

                }
            }

            // redirect
            $this->redirect(
                array(
                    'plugin' => 'accounts',
                    'controller' => 'managers',
                    'action' => 'edit',
                    'fp' => $this->foreign_plugin,
                    'fo' => $this->foreign_obj,
                    'fk' => $this->foreign_key,
                    'return' => $this->params['named']['return']
                )
            );
        }

        // no Searchable id, let's go get one
        $this->redirect(
            array(
                'plugin' => 'searchable',
                'controller' => 'searchable',
                'action' => 'locator',
                'fp' => 'Accounts',
                'fo' => 'Account',
                'return' => base64_encode($this->here)
            ),
            null,
            false,
            'skip'
        );
    }

    /**
     * edit method
     *
     * @return void
     */
    public function edit()
    {
        // process form submit
        if ($this->request->data && isset($this->request->data['Manager']['primary_flag']))
        {
            $msg = 'The primary manager could not be reassigned. Try again?';
            if ($this->Manager->setPrimaryManager(
                $this->request->data['Manager']['primary_flag'],
                $this->foreign_key, $this->foreign_obj, $this->foreign_plugin
            )
            )
            {
                $msg = 'The primary manager has been updated.';
            }

            // report the outcome
            $this->Session->setFlash($msg);
        }

        // get the primary manager for foreign obj
        $primary_manager = $this->Manager->getPrimaryManager(
            $this->foreign_obj, $this->foreign_key
        );
        $this->set('primary_manager', $primary_manager);

        // get the managers for foreign obj
        $managers = $this->Manager->getManagers($this->foreign_obj, $this->foreign_key);
        $this->set('managers', $managers);

        $license = $this->License->getForeignObjLicense($this->foreign_key, $this->foreign_obj, $this->foreign_plugin);
        $this->set('license', $license);
    }

    /**
     * delete method
     *
     * @param int $id expecting record id
     *
     * @return void
     */
    public function delete($id)
    {
        // get the record for the id
        $manager = $this->Manager->findById($id);

        // do not delete primary managers
        if ($manager['Manager']['primary_flag'])
        {
            $this->Session->setFlash('Can not remove primary manager. Select another manager as primary first.');
        }
        else {
            // validate the input and attempt to remove the manager record
            if ($manager && $this->Manager->delete($id))
            {
                $this->Session->setFlash('Manager was removed.');
            }
            else
            {
                $this->Session->setFlash('Manager could not be removed. Try again.');
            }
        }

        /**
         * redirect would use the return param without us passing it
         * but this is more obivious
         */
        $this->redirect($this->params['named']['return']);

    }

    /**
     * primary method
     *
     * Sets a new primary manager for foreign obj
     *
     * @param int $manager_id     expecting manager record id
     * @param int $foreign_key    expecting foreign model recocrd id
     * @param str $foreign_obj    expecting foreign model name
     * @param str $foreign_plugin expecting foreign plugin name
     *
     * @return void
     */
    public function primary($manager_id = null, $foreign_key = null, $foreign_obj = null, $foreign_plugin = null)
    {
        if ($this->Manager->setPrimaryManager($manager_id, $foreign_key, $foreign_obj, $foreign_plugin))
        {
            return true;
        }

        return false;
    }
}
