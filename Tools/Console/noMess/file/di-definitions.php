<?php


return [


    /*
    ====================================== INTERNAL SERVICE ======================================
	*/
	
	NoMess\Web\WebRouter::class 				=> DI\autowire(),
	NoMess\Service\Tools::class 				=> DI\autowire(),
	NoMess\Service\DataCenter::class 			=> DI\autowire(),
	NoMess\Manager\EntityManager::class 		=> DI\autowire(),
	NoMess\HttpSession\HttpSession::class 		=> DI\autowire(),
	NoMess\HttpResponse\HttpResponse::class		=> DI\autowire(),
	NoMess\HttpRequest\HttpRequest::class 		=> DI\autowire(),
	NoMess\DataManager\DataManager::class 		=> DI\autowire(),
	NoMess\Database\PDOFactory::class 			=> DI\autowire(),
	NoMess\Database\Database::class 			=> DI\autowire(),
    NoMess\Database\IPDOFactory::class 			=> DI\autowire(NoMess\Database\PDOFactory::class),
	NoMess\Web\ObserverInterface::class 		=> DI\autowire(NoMess\Web\WebRouter::class),
	NoMess\HttpResponse\SubjectInterface::class => DI\autowire(NoMess\HttpResponse\HttpResponse::class)

];
