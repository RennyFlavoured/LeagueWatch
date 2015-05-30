<?php

class Application_Form_Summonersearch extends Zend_Form
{

    public function init()
    {

        $this->setMethod('post');

        $this->addElement('text', 'summonerName', array(
            'required'   => true,
            'placeholder' => 'Summoner Name',
            'filters'    => array('StringTrim'),
        ));
 
        // Add the submit button
        $this->addElement('submit', 'submit', array(
            'ignore'   => true,
            'label'    => 'Find',
        ));
    }


}

