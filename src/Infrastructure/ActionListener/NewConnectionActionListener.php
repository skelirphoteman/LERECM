<?php


namespace App\Infrastructure\ActionListener;

use App\Domain\ActionListener\Entity\ActionListener;

use App\Infrastructure\ActionListener\ActionListenerInterface;
use App\Infrastructure\ActionListener\ActionListenerService;
 
class NewConnectionActionListener extends ActionListenerService implements ActionListenerInterface
{


    public function create($object): ?string
    {
    	$actionListener = new ActionListener();
    	$actionListener->setCreatedAt(new \DateTime('now'));
    	$actionListener->setName("New connection");
    	$actionListener->setInformations("id=" . $object->getId() . "&mail=" . $object->getEmail());

        $this->insertActionListener($actionListener);

        return null;
    }
}