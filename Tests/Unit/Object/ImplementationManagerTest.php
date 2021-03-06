<?php
declare(strict_types=1);

namespace Tx\Tinyurls\Tests\Unit\Object;

/*                                                                        *
 * This script belongs to the TYPO3 extension "tinyurls".                 *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU General Public License, either version 3 of the   *
 * License, or (at your option) any later version.                        *
 *                                                                        *
 * The TYPO3 project - inspiring people to share!                         *
 *                                                                        */

use PHPUnit\Framework\TestCase;
use Tx\Tinyurls\Domain\Repository\TinyUrlRepository;
use Tx\Tinyurls\Object\ImplementationManager;
use Tx\Tinyurls\UrlKeyGenerator\Base62UrlKeyGenerator;
use Tx\Tinyurls\UrlKeyGenerator\UrlKeyGenerator;

class ImplementationManagerTest extends TestCase
{
    /**
     * @var ImplementationManager
     */
    protected $implementationManager;

    protected function setUp()
    {
        $this->implementationManager = new ImplementationManager();
    }

    public function testResetToDefaultsUsesBase62UrlKeyGenerator()
    {
        $this->implementationManager->restoreDefaults();
        $this->assertEquals(Base62UrlKeyGenerator::class, $this->implementationManager->getUrlKeyGeneratorClass());
    }

    public function testResetToDefaultsUsesDatabaseRepositoryAsFallback()
    {
        $this->implementationManager->restoreDefaults();
        if (!class_exists('TYPO3\\CMS\\Core\\Database\\Query\\QueryBuilder')) {
            /** @noinspection PhpUnnecessaryFullyQualifiedNameInspection */
            $this->assertEquals(
                \Tx\Tinyurls\Domain\Repository\TinyUrlDatabaseRepository::class,
                $this->implementationManager->getTinyUrlRepositoryClass()
            );
        } else {
            $this->markTestSkipped('Doctrine repository is used.');
        }
    }

    public function testResetToDefaultsUsesDoctrineRepositoryIfAvailable()
    {
        $this->implementationManager->restoreDefaults();
        if (class_exists('TYPO3\\CMS\\Core\\Database\\Query\\QueryBuilder')) {
            /** @noinspection PhpUnnecessaryFullyQualifiedNameInspection */
            $this->assertEquals(
                \Tx\Tinyurls\Domain\Repository\TinyUrlDoctrineRepository::class,
                $this->implementationManager->getTinyUrlRepositoryClass()
            );
        } else {
            $this->markTestSkipped('Doctrine DBAL is not available.');
        }
    }

    public function testSetTinyUrlRepositoryClassSetsClassName()
    {
        $this->implementationManager->setTinyUrlRepositoryClass('new class');
        $this->assertEquals('new class', $this->implementationManager->getTinyUrlRepositoryClass());
    }

    public function testSetTinyUrlRepositorySetsTinyUrlRepositoryInstance()
    {
        /** @var TinyUrlRepository $tinyUrlRepository */
        $tinyUrlRepository = $this->createMock(TinyUrlRepository::class);
        $this->implementationManager->setTinyUrlRepository($tinyUrlRepository);
        $this->assertEquals($tinyUrlRepository, $this->implementationManager->getTinyUrlRepository());
    }

    public function testSetUrlKeyGeneratorClassSetsClassName()
    {
        $this->implementationManager->setUrlKeyGeneratorClass('new gen class');
        $this->assertEquals('new gen class', $this->implementationManager->getUrlKeyGeneratorClass());
    }

    public function testSetUrlKeyGeneratorSetsUrlKeyGeneratorInstance()
    {
        /** @var UrlKeyGenerator $urlKeyGenerator */
        $urlKeyGenerator = $this->createMock(UrlKeyGenerator::class);
        $this->implementationManager->setUrlKeyGenerator($urlKeyGenerator);
        $this->assertEquals($urlKeyGenerator, $this->implementationManager->getUrlKeyGenerator());
    }
}
