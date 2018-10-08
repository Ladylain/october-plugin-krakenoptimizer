<?php namespace LucasPalomba\KrakenOptimizer\ReportWidgets;

use Backend\Classes\ReportWidgetBase;
use Exception;
use LucasPalomba\KrakenOptimizer\Classes\Kraken as KrakenClass;
use LucasPalomba\KrakenOptimizer\Models\Settings;

class Kraken extends ReportWidgetBase
{
    public function render()
    {
        try {
            $this->loadData();
        }
        catch (Exception $ex) {
            $this->vars['error'] = $ex->getMessage();
        }

        return $this->makePartial('widget');
    }

    public function defineProperties()
    {
        return [
            'title' => [
                'title'             => 'backend::lang.dashboard.widget_title_label',
                'default'           => 'lucaspalomba.krakenoptimizer::lang.widgets.kraken.label',
                'type'              => 'string',
                'validationPattern' => '^.+$',
                'validationMessage' => 'backend::lang.dashboard.widget_title_error'
            ]
        ];
    }

    protected function loadData()
    {
        $api_key = Settings::get('kraken_site_key', false);
        $secret_key = Settings::get('kraken_secret_key', false);
        if($api_key && $secret_key){
            $kraken = new KrakenClass($api_key,$secret_key);

            $data = $kraken->status();
            if($data['success']){

                $this->vars['available']  = round(($data['quota_total']-$data['quota_used'])*100/$data['quota_total']);
                $this->vars['used']  = round($data['quota_used']*100/$data['quota_total']);
            }
                
        }
    }
}
