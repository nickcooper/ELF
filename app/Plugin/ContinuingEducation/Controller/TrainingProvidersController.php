<?php
/**
 * TrainingProviders Controller
 *
 * @package ContinuingEducation.Controller
 * @author  Iowa Interactive, LLC.
 */
class TrainingProvidersController extends ContinuingEducationAppController
{
    /**
     * Controller name
     *
     * @var string
     * @access public
     */
    public $name = 'TrainingProviders';

    /**
     * Autoload models
     *
     * @var array
     * @access public
     */
    public $uses = array(
        'ContinuingEducation.TrainingProvider',
        'Licenses.License'
    );

    /**
     * index method
     *
     * @return void
     * @access public
     */
    public function index()
    {
        // We're using the Searchble plugin index
        $this->redirect(
            array(
                'plugin'     => 'searchable',
                'controller' => 'searchable',
                'action'     => 'index',
                'fp'         => 'ContinuingEducation',
                'fo'         => 'TrainingProvider'
            ),
            null,
            true,
            'skip'
        );
    }

    /**
     * view method
     *
     * @param int $id the TP id
     *
     * @return void
     * @access public
     */
    public function view($id = null)
    {
        // using the dynamic entity view page
        $this->redirect(
            array(
                'plugin' => 'licenses',
                'controller' => 'licenses',
                'action' => 'entity',
                'fp' => 'ContinuingEducation',
                'fo' => 'TrainingProvider',
                'fk' => $id
            ),
            null,
            true,
            'forward'
        );
    }

    /**
     * Add new training provider
     *
     * @param int $license_id Training provider license id
     *
     * @return void
     */
    public function add($license_id)
    {
        if (empty($license_id))
        {
            $this->Session->setFlash('Invalid License');
            $this->redirect(
                array(
                    'plugin'     => 'licenses',
                    'controller' => 'licenses',
                    'action'     => 'index',
                )
            );
        }

        if ($this->request->is('post'))
        {
            // set license id in training provider data
            $this->request->data['TrainingProvider']['license_id'] = $license_id;

            if ($this->TrainingProvider->add($this->request->data))
            {
                $this->Session->setFlash(__('The training provider has been saved'));
                $this->redirect(
                    array(
                        'plugin'     => 'licenses',
                        'controller' => 'licenses',
                        'action'     => 'view',
                        $license_id,
                    )
                );
            }
            else
            {
                $this->Session->setFlash(__('The training provider could not be saved. Please, try again.'));
            }
        }

        $license = $this->License->getLicenseById($license_id);
        $this->set('license', $license);
    }

    /**
     * Edit an existing training provider
     *
     * @param string $id training provider record id
     *
     * @return void
     */
    public function edit($id = null)
    {
        $this->TrainingProvider->id = $id;

        if (!$this->TrainingProvider->exists())
        {
            $this->Session->setFlash(__('Invalid training provider'));
            $this->redirect(
                array(
                    'plugin'     => 'licenses',
                    'controller' => 'licenses',
                    'action'     => 'index',
                )
            );
        }

        if ($this->request->is('post') || $this->request->is('put'))
        {
            //Grab the old data first so we can compare on successful save and possibly set the licnese to be pending
            $old = $this->TrainingProvider->details($id, array('PrimaryAddress'));

            if ($this->TrainingProvider->edit($this->request->data))
            {
                // probably need to define a function for this. In License or TrainingProviders?
                $license_id = $this->TrainingProvider->License->field(
                    'id',
                    array(
                        'License.foreign_obj' => 'TrainingProvider',
                        'License.foreign_key' => $id,
                    )
                );

                $this->Session->setFlash(__('The training provider has been saved'));
                $this->redirect(
                    array(
                        'plugin'     => 'licenses',
                        'controller' => 'licenses',
                        'action'     => 'view',
                        $license_id,
                    )
                );
            }
            else
            {
                $this->Session->setFlash(__('The training provider could not be saved. Please, try again.'));
            }
        }
        else
        {
            $this->request->data = $this->TrainingProvider->details($id, array('PrimaryAddress'));
        }

        $license = $this->License->getForeignObjLicense($id, 'TrainingProvider', 'ContinuingEducation');
        $this->set('license', $license);
    }
}