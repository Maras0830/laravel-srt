<?php
namespace Maras0830\LaravelSRT\Commands;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputOption;

class TransFormerCommandGenerator extends GeneratorCommand
{
    protected $name = 'make:transformer';
    protected $description = 'Create a new transformer class';
    protected $type = 'Transformer';
    protected $files;

    /**
     * Create a new controller creator command instance.
     *
     * @param  \Illuminate\Filesystem\Filesystem  $files
     * @return void
     */
    public function __construct(Filesystem $files)
    {
        parent::__construct($files);

        $this->files = $files;
    }

    protected function getModelNameSpace()
    {
        return config('laravelSRT.model.namespace', 'App\\');
    }

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return __DIR__ . '/../Stubs/transformer.laravelsrt.stub';
    }

    /**
     * @param string $name
     * @return string
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    protected function buildClass($name)
    {
        $stub = $this->files->get($this->getStub());

        return $this->replaceNamespace($stub, $name)
            ->replaceModel($stub, $name)
            ->replaceClass($stub, $name);
    }

    /**
     * Replace the class name for the given stub.
     *
     * @param string $stub
     * @param string $name
     * @return TransFormerCommandGenerator
     */
    protected function replaceModel(&$stub, $name)
    {
        $model = str_replace('Transformer', '', $this->getNameInput());

        $model_namespace = $this->getModelNameSpace() . $model;

        if (class_exists($model_namespace)) {
            $stub = str_replace('DummyModelNamespace', $model_namespace, $stub);

            $stub = str_replace('DummyModelParameter', strtolower($model), $stub);

            foreach (app($model_namespace)->getFillable() as $key => $value) {
                $model_fillable[$value] = '$' . strtolower($model) . '->' . $value;
            }

            $stub = str_replace('// DummyModelFillable', var_export($model_fillable, true), $stub);

            $stub = str_replace('=> \'', '=> ', $stub);
            $stub = str_replace('\',', ',', $stub);

            $stub = str_replace('DummyModel', $model, $stub);
        } else {
            $stub = str_replace('use DummyModelNamespace;', '', $stub);
            $stub = str_replace('DummyModel $DummyModelParameter', '', $stub);
            $stub = str_replace('/**
     * @param DummyModel
     * @return array
     */', '', $stub);
            $stub = str_replace('return // DummyModelFillable;', '', $stub);
        }

        return $this;
    }

    /**
     * Get the default namespace for the class.
     *
     * @param  string  $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace.'\Transformers';
    }

    /**
     * Get the desired class name from the input.
     *
     * @return string
     */
    protected function getNameInput()
    {
        if (strpos(trim($this->argument('name')), 'Transformer') !== false) {

            return trim($this->argument('name'));
        } else {

            return trim($this->argument('name'));
        }
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['path', 'p', InputOption::VALUE_OPTIONAL, 'Generate a file in the path.'],
        ];
    }
}
