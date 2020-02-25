<?php
/**
 * PhasesController
 *
 * @package Abatements.Controller
 * @author  Iowa Interactive, LLC.
 */
class PhasesController extends AppController
{
    /**
     * Controller name
     *
     * @var string
     * @access public
     */
    public $name = 'Phases';

    /**
     * Autoload models
     *
     * @var array
     * @access public
     */
    public $uses = array(
        'Abatements.Abatement',
        'Abatements.AbatementPhase',
    );

    /**
     * Adds an phase to an abatement.
     *
     * @param int $abatement_id Abatement ID
     *
     * @return void
     * @access public
     */
    public function add($abatement_id)
    {
        try
        {
            if ($this->request->is('post') || $this->request->is('put'))
            {
                $phase = $this->request->data['AbatementPhase'];
                $this->Abatement->addPhase($abatement_id, $phase);
                $this->Session->setFlash(__('Abatement phase added.'));
                $this->redirect(base64_decode($this->params['named']['return']));
            }
        }
        catch (Exception $e)
        {
            $this->Session->setFlash($e->getMessage());
        }

        $this->set('abatement', $this->Abatement->details($abatement_id));
    }

    /**
     * Edits an existing abatement phase date.
     *
     * @param int $abatement_id       Abatement ID
     * @param int $abatement_phase_id Abatement phase ID
     *
     * @return void
     * @access public
     */
    public function edit($abatement_id, $abatement_phase_id)
    {
        try
        {
            if ($this->request->is('post') || $this->request->is('put'))
            {
                $phase = $this->request->data['AbatementPhase'];
                $this->Abatement->editPhase($abatement_id, $abatement_phase_id, $phase);
                $this->Session->setFlash(__('Abatement phase modified.'));
                $this->redirect(base64_decode($this->params['named']['return']));
            }
        }
        catch (Exception $e)
        {
            $this->Session->setFlash($e->getMessage());
        }

        $abatement = $this->Abatement->details($abatement_id);
        $this->request->data = $this->AbatementPhase->details($abatement_phase_id);
        $this->set('abatement', $abatement);
    }

    /**
     * Deletes an abatement phase date.
     *
     * @param int $abatement_id       Abatement ID
     * @param int $abatement_phase_id Abatement phase ID
     *
     * @return void
     * @access public
     */
    public function delete($abatement_id, $abatement_phase_id)
    {
        try
        {
            if (! $this->Abatement->deletePhase($abatement_id, $abatement_phase_id))
            {
                throw new Exception(__('Unable to delete abatement phase.'));
            }

            $this->Session->setFlash(__('Deleted abatement phase.'));
        }
        catch (Exception $e)
        {
            $this->Session->setFlash($e->getMessage());
        }

        $this->redirect(base64_decode($this->params['named']['return']));
    }
}