<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Database\Eloquent\Model;

class SearchRefresh extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'search:refresh {model?*}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Refreshes search index';

    public function isSearchable($model)
    {
        if ($model instanceof Model && method_exists($model, 'shouldBeSearchable')) {
            return $model;
        }
    }

    public function parseArgs()
    {

        $args = $this->arguments()['model'];
        if (!$args) {
            $path = app_path().'/Models';

            return array_filter(array_map(function($arg) {
                $className = "App\\Models\\$arg";
                $class = new $className;
                if ($this->isSearchable($class)) {
                    return $class;
                }
            }, getModels($path)));
        }

        return array_filter(array_map(function($arg) {
            if (preg_match('/^(?:\\{1,2}\w+|\w+\\{1,2})(?:\w+\\{0,2}\w+)+$/', $arg)) {
                $class = new $arg;
                if ($this->isSearchable($class)) {
                    return $class;
                }
            }

            $className = "App\\Models\\".ucfirst($arg);
            $class = new $className;

            if ($this->isSearchable($class)) {
                return $class;
            }
        }, $args));
    }

    /**
     * Execute the console command.
     *
     * @param \Illuminate\Contracts\Events\Dispatcher $events
     *
     * @return mixed
     */
    public function handle(Dispatcher $events)
    {
        $models = $this->parseArgs();
        foreach ($models as $model) {
            $model::removeAllFromSearch();
            $model::makeAllSearchable();
            $events->listen(ModelsImported::class, function($event) use ($model) {
                $key = $event->models->last()
                                     ->getKey();
                $this->line('<comment>Imported ['.get_class($model).'] models up to ID:</comment> '.$key);
            });
            $this->info('Index for ['.get_class($model).'] have been refreshed.');
        }
    }
}
