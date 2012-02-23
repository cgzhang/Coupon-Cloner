<?php
class Rayfox_CouponCloner_Block_Adminhtml_Promo_Quote_Grid extends Mage_Adminhtml_Block_Promo_Quote_Grid
{
	protected function _prepareMassaction()
	{
		$this->setMassactionIdField('rule_id');
        $this->getMassactionBlock()->setFormFieldName('salesrule');

        $this->getMassactionBlock()->addItem('delete', array(
             'label'    => Mage::helper('salesrule')->__('Delete'),
             'url'      => $this->getUrl('*/salesrule/massDelete'),
             'confirm'  => Mage::helper('salesrule')->__('Are you sure?')
        ));

        $this->getMassactionBlock()->addItem('clone', array(
             'label'    => Mage::helper('salesrule')->__('Clone'),
             'url'      => $this->getUrl('*/salesrule/massClone'),
        	 'additional'   => array(
        		'prefix'   	   =>array(
        			'name'  => 'code_prefix',
        			'type'  => 'text',
        			'label' => Mage::helper('salesrule')->__('Prefix')
        		 ),			
                'clone_qty'    => array(
                     'name'     => 'clone_qty',
                     'type'     => 'text',
                     'class'    => 'required-entry',
                     'label'    => Mage::helper('salesrule')->__('Qty'),
                 )
            )
        ));

        return $this;
	}
}