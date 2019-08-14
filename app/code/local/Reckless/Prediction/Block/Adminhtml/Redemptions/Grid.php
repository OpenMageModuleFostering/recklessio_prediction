<?php
class Reckless_Prediction_Block_Adminhtml_Redemptions_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('reckless_redemptionsGrid');
        $this->setDefaultSort('entity_id');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
    }

    /**
     * Add custom column to the grid
     *
     */
    //public function setCollection($collection)
    //{
    //	$collection->getSelect()->joinLeft(array('coupon_agg'=> 'coupon_aggregated'), 'coupon_agg.coupon_code = main_table.code', array('coupon_agg.coupon_uses'));
    //  parent::setCollection($collection);
    //}
    public function setCollection($collection)
    {
        $collection->getSelect()->joinLeft(
            array('orders'=> 'sales_flat_order'),
            'orders.coupon_code = main_table.coupon_code',
            array('orders.base_grand_total', 'orders.increment_id', 'orders.created_at', 'orders.base_discount_amount', 'orders.customer_is_guest')
        );
        parent::setCollection($collection);
    }

    protected function _prepareCollection()
    {
        //		$collection = Mage::getModel('salesrule/coupon')
        //					->getCollection()
        //					->addFieldToFilter('code',array('like'=>'CEO%'));
        $collection = Mage::getModel('prediction/promotions')->getCollection();
        $collection->addFieldToSelect(array('checkout_intent', 'coupon_code'));

        //Prevent from collection failure if the same coupon has been used more than once
        $collection->getSelect()->group('main_table.entity_id');
        $this->setCollection($collection);
        return parent::_prepareCollection();

    }

    protected function _prepareColumns()
    {
        $this->addColumn(
            'created_at',
            array(
                'header' => 'Period',
                'align' =>'right',
                'index' => 'created_at',
                'type' => 'datetime',
                'filter_index' => 'orders.created_at',
            )
        );
        $this->addColumn(
            'coupon_code',
            array(
                'header' => 'Coupon Code',
                'align' =>'right',
                'index' => 'coupon_code',
                'filter_index' => 'main_table.coupon_code',
            )
        );
        $this->addColumn(
            'checkout_intent',
            array(
                'header' => 'Checkout Intent',
                'align' =>'right',
                'index' => 'checkout_intent',
            )
        );
        $this->addColumn(
            'base_discount_amount',
            array(
                'header' => 'Discount Amount',
                'align' =>'right',
                'index' => 'base_discount_amount',
            )
        );
        $this->addColumn(
            'customer_is_guest',
            array(
                'header' => 'Guest User',
                'align' =>'right',
                'index' => 'customer_is_guest',
            )
        );
        $this->addColumn(
            'increment_id',
            array(
                'header' => 'Order Id',
                'align' =>'right',
                'index' => 'increment_id',
                'type' => 'number',
            )
        );
        $this->addColumn(
            'base_grand_total',
            array(
                'header' => 'Grand Total',
                'align' =>'right',
                'index' => 'base_grand_total',
            )
        );
        /*
        $this->addColumn('coupon_uses',
               array(
                    'header' => 'Uses',
                    'align' =>'right',
                    'index' => 'coupon_uses',
              ));
         $this->addColumn('coupon_uses',
               array(
                    'header' => 'Uses',
                    'align' =>'right',
                    'index' => 'coupon_uses',
              ));
         $this->addColumn('coupon_uses',
               array(
                    'header' => 'Uses',
                    'align' =>'right',
                    'index' => 'coupon_uses',
              ));

         $this->addColumn('coupon_uses',
               array(
                    'header' => 'Uses',
                    'align' =>'right',
                    'index' => 'coupon_uses',
              ));

         $this->addColumn('coupon_uses',
               array(
                    'header' => 'Uses',
                    'align' =>'right',
                    'index' => 'coupon_uses',
              ));
        */
        $this->addExportType('*/*/exportRedemptionsCsv', 'CSV');
        $this->addExportType('*/*/exportRedemptionsExcel', 'Excel');
        return parent::_prepareColumns();
    }
    //    public function getRowUrl($row)
    //    {
    //         return $this->getUrl('*/*/edit', array('id' => $row->getId()));
    //    }
}
