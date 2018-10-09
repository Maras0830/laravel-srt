<?php
namespace Maras0830\LaravelSRT\Commands;

use Illuminate\Console\GeneratorCommand;

class RepositoryCommandGenerator extends GeneratorCommand
{
    protected $name = 'make:repository';
    protected $description = 'Create a new repository class';
    protected $type = 'Repository';

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return __DIR__ . '/../Stubs/repository.laravelsrt.sub';
    }

    /**
     * Get the default namespace for the class.
     *
     * @param  string  $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace.'\Repositories';
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
     * Get the desired class name from the input.
     *
     * @return string
     */
    protected function getNameInput()
    {
        if (strpos(trim($this->argument('name')), 'Repository') !== false) {

            return trim($this->argument('name'));
        } else {

            return trim($this->argument('name'));
        }
    }

    private function replaceModel(&$stub, $name)
    {
        $model = str_replace('Repository', '', $this->getNameInput());

        $model_namespace = $this->getModelNamespace() . $model;

        $stub = str_replace('DummyModelNamespace', $model_namespace, $stub);

        $stub = str_replace('DummyModelParameter', camel_case($model), $stub);

        $stub = str_replace('DummyModel',  $model, $stub);

        return $this;
    }

    private function getModelNamespace()
    {
        return config('laravelSRT.model.namespace', 'App\\');
    }
}