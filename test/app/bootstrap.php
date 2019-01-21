<?php

use Phalcon\Config;
use Phalcon\Db\Adapter\MongoDB\Client;
use Phalcon\Db\Adapter\MongoDB\Database;
use Phalcon\DiInterface;
use Phalcon\Di\FactoryDefault;
use Phalcon\Di\FactoryDefault\Cli;
use Phalcon\Cli\Dispatcher;
use Phalcon\Config\Adapter\Grouped;
use Phalcon\Security;
use Phalcon\Loader;
use Phalcon\Mvc\View;
use Phalcon\Mvc\Router;
use Phalcon\Mvc\Url;
use Phalcon\Mvc\Collection\Manager;
use Phalcon\Queue\Beanstalk;
use Library\Php;
use Library\Jwt;

/**
 * This class assembles code required for bootstrapping an application
 * with the intention of keeping any front controllers thin.
 * The assembly of these different responsibilities means that in time
 * this class might grow such that some of its responsibilities may
 * want to be dispersed elsewhere.
 */
final class Bootstrap
{
    const PATH_APP = 'app';
    const PATH_CONFIG = 'config';
    const PATH_VENDOR = 'vendor';

    const ENVIRONMENT_PRODUCTION = 'production';
    const DEFAULT_ENVIRONMENT = self::ENVIRONMENT_PRODUCTION;

    const CONTEXT_WEB = 'web';
    const CONTEXT_CLI = 'cli';

    const ERROR_REPORTING = E_ALL;

    const NAMESPACES = [
        'Controller' => 'controllers',
        'Task' => 'tasks',
        'Model' => 'models',
        'Library' => 'libraries'
    ];

    /** @var string */
    private $environment;
    /** @var string */
    private $basePath;
    /** @var string */
    private $context;
    /** @var Phalcon\Config */
    private $config;

    public function __construct(string $basePath, string $environment, string $context)
    {
        // Note: Hidden sanitization, should be documented.
        $this->basePath = realpath($basePath);
        Php::assertIsInArray($environment, [self::ENVIRONMENT_PRODUCTION]);
        $this->environment = $environment;
        Php::assertIsInArray($context, [self::CONTEXT_WEB, self::CONTEXT_CLI]);
        $this->context = $context;

        /**
         * Note: This is not ideal for performance and is probably a bit premature. It's also worth keeping
         * in mind that the grouped class doesn't automatically work out the adapter to used based on file
         * extension but factory can!
         */
        $files = [self::implodePath($basePath, self::PATH_APP, self::PATH_CONFIG, 'base.php')];
        // Note: This might want to be injected into the DI.
        $this->config = new Grouped($files);
    }

    private static function implodePath(string ...$parts): string
    {
        return implode('/', $parts);
    }

    // Note: This looks like it can provide more than one DI such as for testing but won't work as Phalcon uses a singleton by default.
    public function getDi(): DiInterface
    {
        $config = $this->config;

        switch ($this->context) {
            case self::CONTEXT_WEB:
                $di = new FactoryDefault();

                break;
            case self::CONTEXT_CLI:
                $di = new Cli();

                break;
            default:
                Php::assert(false);
        }

        $di->setShared('config', $config);

        switch ($this->context) {
            case self::CONTEXT_WEB:
                $di->set('url', function () use($config): Url {
                    return (new Url())->setBaseUri($config->url->baseUri);
                });
                $di->set('router', function (): Router {
                    $router = (new Router())
                        ->removeExtraSlashes(true)
                        ->setDefaultNamespace('Controller\\')
                        ->setDefaultController('Index')
                        ->setDefaultAction('index');

                    return $router;
                });
                // Note: Very poor astraction for using other authenticators.
                $di->setShared('jwt', function () use($config): Jwt {
                    return new Jwt($config->jwt->secretKey);
                });
                $di->setShared('view', function (): View {
                    return new View();
                });

                break;
            case self::CONTEXT_CLI:
                $di->setShared('dispatcher', function (): Dispatcher {
                    $dispatcher = new Dispatcher();
                    $dispatcher->setDefaultNamespace('Task');

                    return $dispatcher;
                });

                break;
            default:
                Php::assert(false);
        }

        $di->setShared('beanstalk', function () use($config): Beanstalk {
            return new Beanstalk($config->beanstalk->toArray());
        });
        $di->setShared('mongo', function () use($config): Database {
            return (new Client("mongodb://{$config->mongo->host}"))->selectDatabase($config->mongo->database);
        });
        $di->setShared('collectionManager', function (): Manager {
            return new Manager();
        });

        /**
         * Note: This is naive in its reliance on defaults and given that it offers one way hashing
         * it wouldn't normally be appropriate to deliver something like this to production without
         * careful review and consideration.
         */
        $di->setShared('security', function (): Security {
            return new Security();
        });

        $di->setShared('session', function (): Session {
            return new Session();
        });

        return $di;
    }

    private static function initialiseLoader(string $basePath): void
    {
        $namespaces = self::NAMESPACES;

        foreach ($namespaces as $namespace => $directory) {
            $namespaces[$namespace] = self::implodePath($basePath, self::PATH_APP, $directory, '');
        }

        (new Loader())->registerNamespaces($namespaces)->register();

        /**
         * Note: It would be nice to be able to configure the loader above from composer, however
         * preliminary investigation indicates that the two autoloaders aren't entirely compatible.
         * It's technically possible to manually configure the autoloaded and it'll probably work
         * in most cases but if composer is used significantly more then problems may begin to
         * emerge.
         */
        require_once self::implodePath($basePath, self::PATH_VENDOR, 'autoload.php');
    }

    public function initialiseErrorHandling(): void
    {
        error_reporting(self::ERROR_REPORTING);

        set_error_handler(function ($severity, $message, $file, $line): void {
            if (error_reporting() & $severity === 0) {
                return;
            }

            throw new \ErrorException($message, 0, $severity, $file, $line);
        });
    }

    public static function initialise(string $context): self
    {
        self::initialiseErrorHandling();
        $basePath = dirname(__DIR__);
        self::initialiseLoader($basePath);
        return new self($basePath, self::DEFAULT_ENVIRONMENT, $context);
    }
}
