<?php namespace LucasPalomba\Krakenoptimizer;

use System\Classes\PluginBase;
use System\Classes\SettingsManager;
use Backend,Config, Flash, Request;
use LucasPalomba\KrakenOptimizer\Classes\Kraken;
use LucasPalomba\KrakenOptimizer\Models\Settings;
use October\Rain\Database\Attach\File;

class Plugin extends PluginBase
{   

    public function pluginDetails()
    {
        return [
            'name' => 'Kraken Optimize Plugin',
            'description' => 'Optimize and compress images via kraken.io API',
            'author' => 'Lucas Palomba',
            'icon' => 'icon-file-image-o'
        ];
    }
    public function boot(){
        $SiteK = Settings::get('kraken_site_key', false);
        $SecretK = Settings::get('kraken_secret_key', false);

        if($SiteK && $SecretK){
            Backend\Widgets\MediaManager::extend(function($widget) {
                $widget->bindEvent('file.upload', function ($filePath, $uploadedFile) {
                    $path = Request::get('path');
                    if($path != "/")
                    {
                        $path = $path . DIRECTORY_SEPARATOR;
                    }
                    $array_types = ['image/jpeg','image/gif','image/png','image/webp'];
                    
                    if(in_array($uploadedFile->getMimeType(),$array_types)) {
                        $api_key = Settings::get('kraken_site_key', false);
                        $secret_key = Settings::get('kraken_secret_key', false);
                        $baseUrl = url(Config::get('cms.storage.media.path'));
                        
                        $kraken = new Kraken($api_key,$secret_key);
                        
                        $params = array(
                            "url" => $baseUrl.$filePath,
                            "wait" => true,
                            "lossy" => true
                        );
                        $data = $kraken->url($params);
                        $dir = storage_path('app/media');
                        if ($data["success"]) {
                            is_dir($dir) || @mkdir($dir) || die("Can't Create folder");
                            copy($data['kraked_url'], $dir. $path . $data['file_name']);
                        } else {
                            Flash::error(e(trans('lucaspalomba.krakenoptimizer::lang.errors.optimization.alert_errors')));
                        }
                    }
                
                });
            });

            File::extend(function ($model) {
                $model->bindEvent('model.beforeCreate', function () use ($model) {
                    if($model->isImage()){
                         /**
                         * Prepare
                         */
                        $filePath = $model->getPath();
                        $api_key = Settings::get('kraken_site_key', false);
                        $secret_key = Settings::get('kraken_secret_key', false);
                        
                        $kraken = new Kraken($api_key,$secret_key);
                        
                        $params = array(
                            "url" => $filePath,
                            "wait" => true,
                            "lossy" => true
                        );
                        $data = $kraken->url($params);
                        $dir = storage_path('app');
                        if ($data["success"]) {
                            is_dir($dir) || @mkdir($dir) || die("Can't Create folder");
                            if(!@copy($data['kraked_url'], $model->getLocalPath())){
                                $errors= error_get_last();
                                dd($errors);
                            }
                            clearstatcache();
                            $model->file_size = filesize($model->getLocalPath());
                            Flash::success(e(trans('lucaspalomba.krakenoptimizer::lang.success.optimization.alert_success')));
                        } else {
                            Flash::error(e(trans('lucaspalomba.krakenoptimizer::lang.errors.optimization.alert_errors')));
                        }

                        
                    }
                });
            });
            
        }
        
    }

    public function registerComponents()
    {
    }

    public function registerPermissions() {
        return [
            'lucaspalomba.krakenoptimizer.access_settings' => ['tab' => 'lucaspalomba.krakenoptimizer::lang.permissions.tab', 'label' => 'lucaspalomba.krakenoptimizer::lang.permissions.access_settings'],
        ];
    }

    public function registerSettings()
    {
        return [
            'kraken' => [
                'label'       => 'Kraken Optimizer',
                'description' => 'Manage Kraken Account for Image Optimization',
                'category'    => SettingsManager::CATEGORY_CMS,
                'icon'        => 'icon-file-image-o',
                'class'       => 'Lucaspalomba\KrakenOptimizer\Models\Settings',
                'permissions' => ['lucaspalomba.krakenoptimizer.access_settings'],
                'order'       => 500
            ]
        ];
    }

    public function registerReportWidgets()
    {
        return [
            'LucasPalomba\KrakenOptimizer\ReportWidgets\Kraken' => [
                'label'   => 'lucaspalomba.krakenoptimizer::lang.widgets.kraken.title',
                'context' => 'dashboard'
            ]
        ];
    }
}
