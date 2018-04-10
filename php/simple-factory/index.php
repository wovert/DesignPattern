<?php
abstract class Operation {
	protected $numberA = 0;
	protected $numberB = 0;
	protected abstract function getResult();

	public function setNumberA($n) {
		$this->numberA = $n;
	}
	public function setNumberB($n) {
		$this->numberB = $n;
	}
}
class OperationAdd extends Operation {
	public function getResult() {
		return $this->numberA + $this->numberB;
	}
}
class OperationSub extends Operation {
	public function getResult() {
		return $this->numberA - $this->numberB;
	}
}
class OperationMul extends Operation {
	public function getResult() {
		return $this->numberA * $this->numberB;
	}
}
class OperationDiv extends Operation {
	public function getResult() {
		try {
			return $this->numberA / $this->numberB;
		} catch(Exception $e) {
			return $e;
		}
	}
}
abstract class OperationFactory {
	public static function createOperate(){}
}

class OperationAddFactory extends OperationFactory {
	public static function createOperate() {
		return new OperationAdd();
	}
}
class OperationSubFactory extends OperationFactory {
	public static function createOperate() {
		return new OperationSub();
	}
}
class OperationMulFactory extends OperationFactory {
	public static function createOperate() {
		return new OperationMul();
	}
}
class OperationDivFactory extends OperationFactory {
	public static function createOperate() {
		return new OperationDiv();
	}
}

$ope = OperationAddFactory::createOperate();
$ope->setNumberA(100);
$ope->setNumberB(200);
$result = $ope->getResult();
var_dump($result);