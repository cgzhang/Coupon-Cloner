<?php
class Rayfox_CouponCloner_Helper_Data extends Mage_Core_Helper_Abstract
{
	const DEFAULT_CODE_LENGTH = 6;
	
	/* get code length from configuration, min length is 2, default length is 6 */
	public function getCodeLength()
	{
		$codeLength = intval(Mage::getStoreConfig('coupon_cloner_options/general/coupon_code_length'));
		$codeLength = $codeLength>1?$codeLength:self::DEFAULT_CODE_LENGTH;	

		return $codeLength;
	}
	
	public function getRandomCouponCode($codePrefix = NULL)
	{
		$codeGenerator = $this->getCodeGenerator($this->getCodeLength());
		
		$code =  $codeGenerator->generateCode();
		
		if($codePrefix){
			$code = $codePrefix . $code;
		}

		/*max attempt times:9*/
		$attemptTimes = 1;
		while(!$this->isCouponCodeUnique($code) && $attemptTimes<=9){
			$code =  $codeGenerator->generateCode();
			if($codePrefix){
				$code = $codePrefix . $code;
			}
			
			$attemptTimes++;
		}
		return $code;
	}
	
	/* check if coupon code already exits or not */
	public function isCouponCodeUnique($code)
	{
		$codes = Mage::getModel('salesrule/coupon')->getCollection()
			->addFieldToFilter('code', $code)
			;
		
		return !(bool)($codes->getSize());		
	}
	
	public function cloneCoupon($sourceSalesrule, $codePrefix, $qty)
	{
		$couponCode = $this->getRandomCouponCode($codePrefix);
		
		$newRule = Mage::getModel('salesrule/rule')->setData($sourceSalesrule->getData())
			->setId(null)
			->setConditions($sourceSalesrule->getConditions())
			->setActions($sourceSalesrule->getActions())
			->setCouponCode($couponCode)
			->setName('copy of ' . $sourceSalesrule->getName() . Mage::getModel('core/date')->timestamp());
			;
			
		try{
			$newRule->save();
		}
		catch(Exception $e){
			Mage::logException($e);
			throw new Exception($e->getMessage());
		}
	}
	
	public function getCodeGenerator($codeLength)
	{	
		$registryKey = '_singleton/salesrule/coupon_codegenerator/' . $codeLength;
        if (!Mage::registry($registryKey)) {
            Mage::register($registryKey, Mage::getModel('salesrule/coupon_codegenerator', array('length' => $codeLength)));
        }
        return Mage::registry($registryKey);
	}
}