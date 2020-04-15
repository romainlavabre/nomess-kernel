<?php
namespace NoMess\Tools;


class Tree{

	private $tabPath = array();

	public function addClass(string $class): void {
		$tabValue = explode('\\', $class);

		$length = count($tabValue) - 1;
		$onlyClass = $tabValue[$length];
		$i = 0;

		foreach($tabValue as $value){
			if($value === 'Controllers'){
				$this->tabPath[] = 'Controller - ' . $onlyClass;
				break;
			}else if($value === 'Modules'){
				if($i + 2 === $length){
					$this->tabPath[] = 'ServiceManager - ' . $onlyClass;
					break;
				}
			}else if($value === 'Service'){
				$this->tabPath[] = 'Service - ' . $onlyClass;
				break;
			}else if($value === 'Tables'){
				$this->tabPath[] = 'Table - ' . $onlyClass;
				break;
			}else if($value === "Entity"){
				$this->tabPath[] = 'Entity - ' . $onlyClass;
				break;
			}

			$i++;
		}
	}

	public function getTree(): ?array {
		return $this->tabPath;
	}
}