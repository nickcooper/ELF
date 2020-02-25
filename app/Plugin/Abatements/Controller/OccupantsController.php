<?php
/**
 * Occupants controller
 *
 * @package Abatements.Controller
 * @author  Iowa Interactive, LLC.
 */
class OccupantsController extends AppController
{
    /**
     * Controller name
     *
     * @var string
     * @access public
     */
    public $name = 'Occupants';

    /**
     * Autoload models
     *
     * @var array
     * @access public
     */
    public $uses = array(
        'Contact',
        'Abatements.Abatement',
    );

    /**
     * Adds an occupant to an abatement.
     *
     * @param int $abatement_id Abatement ID
     *
     * @return void
     * @access public
     */
    public function add($abatement_id)
    {
        if ($this->request->is('post') || $this->request->is('put'))
        {
            try
            {
                $occupant = $this->request->data['Contact'];
                $this->Abatement->addOccupant($abatement_id, $occupant);
                $this->Session->setFlash(__('Property occupant added.'));
                $this->redirect(base64_decode($this->params['named']['return']));
            }
            catch (Exception $e)
            {
                $this->Session->setFlash($e->getMessage());
            }
        }

        $this->set('abatement', $this->Abatement->details($abatement_id));
    }

    /**
     * Edits an occupant from an abatement.
     *
     * @param int $abatement_id Abatement ID
     * @param int $contact_id   Contact ID
     *
     * @return void
     * @access public
     */
    public function edit($abatement_id, $contact_id)
    {
        if ($this->request->is('post') || $this->request->is('put'))
        {
            try
            {
                $occupant = $this->request->data['Contact'];
                $this->Abatement->editOccupant($abatement_id, $contact_id, $occupant);
                $this->redirect(base64_decode($this->params['named']['return']));
            }
            catch (Exception $e)
            {
                $this->Session->setFlash($e->getMessage());
            }
        }

        $this->request->data = $this->Contact->details($contact_id);
        $this->set('abatement', $this->Abatement->details($abatement_id));
    }

    /**
     * Removes an occupant from an abatement.
     *
     * @param int $abatement_id Abatement ID
     * @param int $contact_id   Occupant contact ID
     *
     * @return void
     * @access public
     */
    public function delete($abatement_id, $contact_id)
    {
        try
        {
            if (! $this->Abatement->hasOccupant($abatement_id, $contact_id))
            {
                throw new Exception(__('Invalid occupant'));
            }

            if (! $this->Abatement->deleteOccupant($abatement_id, $contact_id))
            {
                throw new Exception(__('Unable to remove occupant'));
            }

            $this->Session->setFlash(__('Removed occupant.'));
        }
        catch (Exception $e)
        {
            $this->Session->setFlash($e->getMessage());
            $this->redirect(
                array(
                    'controller' => 'abatements',
                    'action'     => 'view',
                    $abatement_id,
                )
            );
        }

        $this->redirect(base64_decode($this->params['named']['return']));
    }
}