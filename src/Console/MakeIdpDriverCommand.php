<?php

namespace Khbd\LaravelWso2IdentityApiUser\Console;

use Illuminate\Console\GeneratorCommand;

class MakeIdpDriverCommand extends GeneratorCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'make:idpdriver';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new idp driver class for the application';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Idp';

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return __DIR__.'/stubs/gateway.stub';
    }

    /**
     * Get the default namespace for the class.
     *
     * @param string $rootNamespace
     *
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace.'\Idps';
    }
}
