<?php
class BendElectricityPeriod extends DbObject {
    
    public $d_provider_invoice;
    public $d_provider_period_start;
    public $d_provider_period_end;
    
    public $provider_invoice_total_inc_gst;
    public $provider_total_consumption_kwh;
    public $provider_total_production_kwh;
    
    public $bend_total_consumption_kwh;
    public $bend_total_production_kwh;
    public $bend_total_invoiced;
    
}

