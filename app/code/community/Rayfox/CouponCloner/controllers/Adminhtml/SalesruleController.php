<?php
class Rayfox_CouponCloner_Adminhtml_SalesruleController extends Mage_Adminhtml_Controller_Action
{
    public function massCloneAction()
  	{
  		$ids = $this->getRequest()->getParam('salesrule');
  		$cloneQty = $this->getRequest()->getParam('clone_qty');
  		$codePrefix = $this->getRequest()->getParam('code_prefix');
  		
  		foreach($ids as $id){
  			//clone one by one
  			$ruleForClone = Mage::getModel('salesrule/rule')->load($id);
  			if($ruleForClone->getCouponType() != Mage_SalesRule_Model_Rule::COUPON_TYPE_SPECIFIC){
  				$this->_getSession()->addError($this->__('Salesrule with id: '. $id. ' is not supported, only rules with coupon code can be cloned.'));
  				continue;
  			}
  			if(!empty($ruleForClone) && $ruleForClone->getId()){
  				for($i=0; $i<$cloneQty;$i++){
  					Mage::helper('couponcloner')->cloneCoupon($ruleForClone, $codePrefix, $cloneQty);
  				}
  			}
  			
  			$this->_getSession()->addSuccess($this->__('Total of %d record(s) have been cloned for rule: ' . $id . '.', $cloneQty));
  		}
  		
  		$this->_redirect('*/promo_quote/');
  	}
  	
    public function massDeleteAction()
  	{
  		$ids = $this->getRequest()->getParam('salesrule');
  		
  		foreach($ids as $id){
  			try{
  				$model = Mage::getModel('salesrule/rule');
  				$model->setId($id)->delete();
  			}
  			catch (Exception $e) {
  				$response->setError(1);
  				$response->setMessage($e->getMessage());
  			}
  		}
  		
  		$this->_getSession()->addSuccess($this->__('Total of %d record(s) have been deleted.', count($ids)));
  		
  		$this->_redirect('*/promo_quote/');
  	}
}