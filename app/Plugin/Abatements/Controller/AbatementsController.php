<?php
/**
 * Abatements controller
 *
 * @package Abatements.Controller
 * @author  Iowa Interactive, LLC.
 */
class AbatementsController extends AppController
{
    /**
     * Controller name
     *
     * @var string
     * @access public
     */
    public $name = 'Abatements';

    /**
     * Autoload models
     *
     * @var array
     * @access public
     */
    public $uses = array(
        'Abatements.Abatement',
        'Abatements.AbatementStatus',
        'Abatements.DwellingType',
        'Accounts.Account',
        'Licenses.License',
        'OutputDocuments.OutputDocumentType',
    );

    /**
     * Autoload components
     *
     * @var array
     * @access public
     */
    public $components = array('ForeignObject');

    /**
     * Default pagination options.
     *
     * @var array
     * @access public
     */
    public $paginate = array(
        'contain' => array(
            'AbatementPhase' => array('order' => array('AbatementPhase.begin_date' => 'ASC')),
            'AbatementStatus',
            'Address',
            'DwellingType',
        ),
        'conditions' => array(
            'Abatement.license_id NOT' => null,
            'Abatement.firm_id NOT' => null,
            'Abatement.abatement_number NOT' => null,
        ),
        'order' => array('Abatement.modified' => 'DESC'),
    );

    /**
     * index method
     *
     * @return void
     * @access public
     */
    public function index()
    {
        // we're using the Searchable plugin index (it's still pretty dope)
        $this->redirect(
            array(
                'plugin'     => 'searchable',
                'controller' => 'searchable',
                'action'     => 'index',
                'fp'         => 'Abatements',
                'fo'         => 'Abatement',
            )
        );
    }

    /**
     * view method
     *
     * @param int $id Abatement ID
     *
     * @return void
     * @access public
     */
    public function view($id = null)
    {
        // get the abatement data
        $this->data = $this->Abatement->details($id);

        $isRental = $this->DwellingType->isRental($this->data['Abatement']['dwelling_type_id']);
        $isComplete = $this->Abatement->isComplete($id);
        $isIncomplete = $this->Abatement->isIncomplete($id);
        $isActive = $this->Abatement->isActive($id);
        $isCancelled = $this->Abatement->isCancelled($id);

        $this->set(
            compact('isRental', 'isComplete', 'isIncomplete', 'isActive', 'isCancelled')
        );

        $triggers = array();
        if ($this->Abatement->isActive($id))
        {
            $triggers[] = 'abatement_initial';
        }
        elseif ($this->Abatement->isComplete($id))
        {
            $triggers[] = 'abatement_reminder';
        }
        if ($this->data['Abatement']['date_submitted'] !== null)
        {
            $triggers[] = $this->Abatement->hasMultiplePhases($id) ? 'abatement_revised_multi_phased' : 'abatement_revised';
        }

        // generate docs links array
        $doc_links = array();
        if (array_key_exists('OutputDocuments.OutputDocument', $this->Abatement->actsAs))
        {
            foreach ($triggers as $trigger)
            {
                // loop trigger and build links array for view
                if ($trigger_docs = Configure::read(sprintf('OutputDocuments.triggers.%s', $trigger)))
                {
                    // loop
                    foreach ($trigger_docs as $doc)
                    {
                        // get the doc type configs
                        $doc_conf = Configure::read(sprintf('OutputDocuments.docs.%s', $doc['type']));

                        // generate the doc links
                        foreach ($doc_conf['types'] as $type => $data)
                        {
                            // set the doc link params
                            $params = array(
                                'fp'         => 'Abatements',
                                'fo'         => 'Abatement',
                                'fk'         => $id,
                                'trigger'    => $trigger,
                                'doc_type'   => $doc['type'],
                                'ext'        => $type
                            );
                            // generate the link and add it to the list
                            $doc_links[$doc['type']][$type] = $this->Abatement->buildDocUrl($params);
                        }
                    }
                }
            }
        }
        $this->set('doc_links', $doc_links);

    }

    /**
     * add method
     *
     * @param int $dwelling_type_id expecting the dwelling type id
     *
     * @return bool
     * @access public
     */
    public function add($dwelling_type_id = null)
    {
        try
        {
            // get the dwelling data
            if (! $dwelling_type = $this->Abatement->DwellingType->details($dwelling_type_id))
            {
                throw new Exception(__('Invalid dwelling type provided.'));
            }

            // is the dwelling type a rental?
            $rental_dwelling = $this->Abatement->DwellingType->isRental($dwelling_type_id);

            // was a searchable id passed back from Searchable plugin?
            if (isset($this->params['named']['searchable']))
            {
                $this->foreign_key = $this->params['named']['searchable'];
            }
            elseif ($this->request->is('post') && isset($this->request->data['Searchable']))
            {
                $this->foreign_key = $this->request->data['Searchable'];
            }

            // do we have a license id to associate this abatement to?
            if (! $this->foreign_key)
            {
                // redirect to searchable to locate a licnese id
                $this->redirect(
                    array(
                        'plugin'     => 'searchable',
                        'controller' => 'searchable',
                        'action'     => 'locator',
                        'fp'         => $this->foreign_plugin,
                        'fo'         => $this->foreign_obj,
                        'return'     => base64_encode($this->here),
                    ),
                    null,
                    true,
                    'skip'
                );
            }

            // create the new abatement notice
            $this->Abatement->addAbatement($this->foreign_key, $dwelling_type_id);

            // redirect to abatement view page
            $this->redirect(
                array(
                    'plugin'     => 'abatements',
                    'controller' => 'abatements',
                    'action'     => 'view',
                    $this->Abatement->getLastInsertId(),
                    'fp'         => $this->foreign_plugin,
                    'fo'         => $this->foreign_obj,
                    'fk'         => $this->foreign_key,
                ),
                null,
                true,
                'skip'
            );
        }
        catch (Exception $e)
        {
            $this->Session->setFlash(
                sprintf(__('Failed to create abatement notice. (%s)'), $e->getMessage())
            );

            $this->redirect(
                array(
                    'controller' => 'abatements',
                    'action'     => 'index',
                ),
                null,
                true,
                'skip'
            );
        }
    }

    /**
     * delete method.
     *
     * @param int $id Abatement ID
     *
     * @return void
     * @access public
     */
    public function delete($id = null)
    {
        if (! $this->Abatement->exists($id))
        {
            throw new NotFoundException(__('Invalid abatement notice'));
        }

        try
        {
            $message = $this->Abatement->deleteAbatement($id)
                ? __('Abatement notice deleted')
                : __('Abatement notice was not deleted');
            $this->Session->setFlash($message);
        }
        catch (Exception $e)
        {
            $this->Session->setFlash($e->getMessage());
        }

        $this->redirect(array('action' => 'index'));
    }

    /**
     * Submit an abatement. Sets the abatement status to 'Active' and
     * queues output document.
     *
     * @param int $id Abatement ID
     *
     * @return void
     * @access public
     */
    public function submit($id = null)
    {
        try
        {
            if ($this->Abatement->canSubmit($id))
            {
                $this->Abatement->submit($id);

                $abatement = $this->Abatement->findById($id);
                $abatementNumber = $abatement['Abatement']['abatement_number'];

                $message = $abatement['Abatement']['date_submitted'] !== null
                    ? sprintf(__('Abatement notice (%s) submitted.'), $abatementNumber)
                    : sprintf(__('Revised abatement notice (%s) submitted.'), $abatementNumber);
                $this->Session->setFlash($message);
            }
        }
        catch (Exception $e)
        {
            $this->Session->setFlash(sprintf(__('Unable to submit abatement notice: %s'), $e->getMessage()));
        }

        $this->redirect(array('action' => 'index'));
    }

    /**
     * Abatement abatement information edit method.
     *
     * @param int $id Abatement ID
     *
     * @return void
     * @access public
     */
    public function abatement_information($id = null)
    {
        if (! $this->Abatement->exists($id))
        {
            throw new NotFoundException(__('Invalid abatement notice'));
        }

        if ($this->request->is('post') || $this->request->is('put'))
        {
            try
            {
                $this->request->data['Abatement']['id'] = $id;
                if (! $this->Abatement->editAbatement($this->request->data))
                {
                    throw new Exception(__('Unable to update abatement information.'));
                }

                $this->Session->setFlash(__('Abatement information updated successfully.'));
                $this->redirect(base64_decode($this->params['named']['return']));
            }
            catch (Exception $e)
            {
                $this->Session->setFlash($e->getMessage());
            }
        }

        $abatement = $this->Abatement->details($id);

        $this->request->data = $abatement;
        $this->set('abatement', $abatement);
    }

    /**
     * Abatement property add/edit method.
     *
     * @return void
     * @access public
     */
    public function property_info()
    {
        try
        {
            // process form submit
            if ($this->request->data)
            {
                if (! $this->Abatement->edit($this->request->data))
                {
                    throw new Exception('Failed to save record.');
                }

                // redirect back to abatement page
                $this->Session->setFlash('Property information saved.');
                $this->redirect($this->params['named']['return']);
            }

            // pull the abatement record
            $this->data = $this->Abatement->details($this->foreign_key);
        }
        catch(Exception $e)
        {
            // failed
            $this->Session->setFlash(sprintf('Property information could not be updated. (%s)', $e->getMessage()));
        }
    }

    /**
     * associate_firm method
     *
     * Choose from a list of firms associated to license.
     *
     * @param int $abatement_id Abatement ID
     * @param int $license_id License ID
     *
     * @return void
     * @access public
     */
    public function associate_firm($abatement_id = null, $license_id = null)
    {
        try
        {
            // get the license data
            $this->Abatement->License->includeForeignData = false;
            $license_data = $this->Abatement->License->find(
                'first',
                array(
                    'conditions' => array(
                        'License.id' => $license_id,
                    ),
                    'contain' => array('ParentLicense'),
                )
            );

            if (!$license_data)
            {
                throw new Exception(__('Failed to get license data.'));
            }

            // create a list of the firms for selection
            $firm_list = array();
            foreach ($license_data['ParentLicense'] as $parent_license)
            {
                $firm_list[$parent_license['foreign_key']] = $parent_license['label'];
            }
            $this->set('firm_list', $firm_list);

            // process form submit
            if ($this->request->data)
            {
                $this->request->data['Abatement']['id'] = $abatement_id;

                if (! $this->Abatement->edit($this->request->data))
                {
                    throw new Exception(__('Associate firm save failed.'));
                }

                // redirect
                $this->redirect($this->referer());
            }
        }
        catch (Exception $e)
        {
            $this->Session->setFlash(sprintf(__('Failed to add firm information. (%s)'), $e->getMessage()));
            $this->redirect($this->referer());
        }
    }
}