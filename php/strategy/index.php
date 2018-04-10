<?php

/**
 * 抽象算法类
 */
abstract class CashSuper {
	public abstract function acceptCash($money);
}

/**
 * 正常收费
 */
class CashNormal extends CashSuper {
	public function acceptCash($money) {
		return "正常收费".$money;
	}
}

/**
 * 满 300 返 100
 */
class CashRebate extends CashSuper {
	public function acceptCash($money) {
		return "满 300 返 100".$money;
	}
}
/**
 * 打 8 折
 */
class CashReturn extends CashSuper {
	public function acceptCash($money) {
		return "打 8 折".$money;
	}
}


class CashFactory {
	public static function createCash($cash) {
		$className = 'Cash'.ucwords(strtolower($cash));
		try {
			$cash = new $className;
		} catch(Exception $e) {
			throw $e;
		}
		return $cash;
	}
}

class CashContext {
	private $cash;

	public function __construct($cash) {
		$this->cash = CashFactory::createCash($cash);
	}
	public function getResult($money) {
		return $this->cash->acceptCash($money);
	}
}

$money = 2000;
$cashCont = new CashContext('normal');
$result = $cashCont->getResult($money);
echo($result);
