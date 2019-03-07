<?php

declare(strict_types=1);

/**
 * This file is part of Laravel Zero.
 *
 * (c) Nuno Maduro <enunomaduro@gmail.com>
 *
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

namespace LaravelZero\Framework\Bootstrap;

use Dotenv\Dotenv;
use LaravelZero\Framework\Application;
use Dotenv\Exception\InvalidFileException;
use Dotenv\Exception\InvalidPathException;
use LaravelZero\Framework\Providers\Build\Build;
use LaravelZero\Framework\Contracts\BoostrapperContract;

/**
 * @internal
 */
final class BuildLoadEnvironmentVariables implements BoostrapperContract
{
    /**
     * @var \LaravelZero\Framework\Providers\Build\Build
     */
    private $build;

    /**
     * BuildLoadEnvironmentVariables constructor.
     *
     * @param \LaravelZero\Framework\Providers\Build\Build $build
     */
    public function __construct(Build $build)
    {
        $this->build = $build;
    }

    /**
     * {@inheritdoc}
     */
    public function bootstrap(Application $app): void
    {
        /*
         * Override environment variables with the environment file along side the Phar file.
         */
        if ($this->build->canUseEnvironmentFile()) {
            try {
                (
                new Dotenv(
                    $this->build->getPath(),
                    $this->build->environmentFilename()
                )
                )->overload();
            } catch (InvalidPathException $e) {
                echo 'The path is invalid: '.$e->getMessage();
                die(1);
            } catch (InvalidFileException $e) {
                echo 'The environment file is invalid: '.$e->getMessage();
                die(1);
            }
        }
    }
}
