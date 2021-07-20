<?php

namespace App\Infrastructure\Rapport;

use Doctrine\ORM\EntityManagerInterface;

use App\Domain\ActionListener\Entity\ActionListener;
use App\Infrastructure\SkelirTelegram\SkelirTelegramInterface;

/**
 * 
 */
class RapportService 
{
	private $entityManager;
	private $dayRapportTelegram;
	
	public function __construct(EntityManagerInterface $entityManager,
								SkelirTelegramInterface $dayRapportTelegram)
	{
		$this->entityManager = $entityManager;
		$this->dayRapportTelegram = $dayRapportTelegram;
	
	}

	private function generateText($actionListeners) : String
	{
		$separate = "------------------------------------\n";
		$separate = str_replace("-","\-",$separate);
        $yesterday = new \Datetime('-1 day');
		$text = "Rapport depuis le " . $yesterday->format('d/m/Y') . " :\n";
		$text = $text . "Nom \| Informations\n" . $separate;

		foreach ($actionListeners as $actionListener) {
			$data = $actionListener->getInformations();
			$data = str_replace("=","\=",$data);
			$data = str_replace(".","\.",$data);
			$text = $text . $actionListener->getName() . " \| " . $data . " \n " .$separate;
		}

		return $text;
	}

	public function generate()
	{
		$em = $this->entityManager;

        $actionListeners = $em->getRepository(ActionListener::class)
            ->findRapportOneDay();
        $text = $this->generateText($actionListeners);

        $informations = ["message" => $text];

        $this->dayRapportTelegram->send($informations);

	}
}