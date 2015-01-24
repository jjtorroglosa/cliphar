# Cliphar

[![Build Status](https://travis-ci.org/jjtorroglosa/cliphar.svg?branch=master)](https://travis-ci.org/jjtorroglosa/cliphar)

A PHP framework to instantly create CLI apps

## 1. Features

- Container Interop interfaces for IoC. Laravel's container implementation adapter provided.
- Logger implementation satisfying PSR-3 LoggerInterface
- ServiceProvider interface to register services into the application

## 2. Usage

Just extend the BaseApplication class and implement the abstract methods:

    <?php

    namespace Acme;

    use Cliphar\BaseApplication;

    /**
     * Class Application
     */
    class Application extends BaseApplication
    {
        /**
         * @return string
         */
        protected function getVersion()
        {
            return "1.0";
        }

        /**
         * @return string
         */
        protected function getName()
        {
            return "Example application";
        }

        /**
         * @return string[]
         */
        protected function getCommands()
        {
            return array(
                'Acme\Command\ExampleCommand'
            );
        }

        /**
         * @return string[]
         */
        protected function getProviders()
        {
            return array(
                'Cliphar\ServiceProvider\LoggerProvider'
            );
        }
    }

You can register commands appending to the array its FQCN or the instances directly. If you provide the FQCN,
the framework will use the container to resolve the class.  The same apply to the service providers.

Commands must extend `Symfony\Component\Console\Command\Command`.

Now, you can create a bootstrap.php that executes your app:

    <?php

    use Acme\Application;

    require __DIR__ . "/vendor/autoload.php";

    $application = new Application();
    $application->run();

That's it!.

Here you have a project template to create your apps: https://github.com/jjtorroglosa/cliphar-skeleton

## 3. Generating a phar

You can use https://github.com/box-project/box2 to create a self contained phar with your application.
In the cliphar-skeleton project you can find a template box.json file.

## 4. Autowiring

The Laravel's container will autowire the dependencies declared in the constructor of your classes.

In the above example application, we are attaching The LoggerProvider ServiceProvider, which basically
binds the `Psr\Log\LoggerInterface` interface to a concrete implementation. So, in our
`Acme\Command\ExampleCommand`, if we declare a type hinted `Psr\Log\LoggerInterface $loggerInterface`
in its constructor we'll receive the registered implementation. That's it.

You can avoid create a ServiceProvider to register ExampleCommand in the container. If that class exists,
and all its dependencies are resolvable by the container, we don't need to do further configuration,
Laravel Container will be able to create the object.

## 5. Registering new ServiceProviders

If you need custom injection, you can create a `Cliphar\ServiceProvider` interface implementation
and append it to the `getProviders()` function. In the constructor of the service provider you
can inject `Interop\Container\ContainerInterface` and `Cliphar\Binder` to bind your services.

## 6. More info

- [Symfony Console Documentation](http://symfony.com/doc/current/components/console/introduction.html)
