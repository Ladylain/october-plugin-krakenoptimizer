<?php namespace LucasPalomba\KrakenOptimizer\Models;

use Model;

class Settings extends Model
{
    public $implement = ['System.Behaviors.SettingsModel'];

    // A unique code
    public $settingsCode = 'lucaspalomba_krakenoptimizer_settings';

    // Reference to field configuration
    public $settingsFields = 'fields.yaml';
}