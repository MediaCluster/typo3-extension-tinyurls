<?php
declare(strict_types = 1);
namespace Tx\Tinyurls\Tests\Unit\Configuration;

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
use Tx\Tinyurls\Configuration\ExtensionConfiguration;

/**
 * @backupGlobals enabled
 */
class ExtensionConfigurationTest extends TestCase
{
    /**
     * @var ExtensionConfiguration
     */
    protected $extensionConfiguration;

    protected function setUp()
    {
        $this->extensionConfiguration = new ExtensionConfiguration();
    }

    public function testAppendPidQueryAppendsAndStatementForNonEmptyQuery()
    {
        $this->assertEquals('a=1 AND pid=0', $this->extensionConfiguration->appendPidQuery('a=1'));
    }

    public function testAppendPidQueryAppendsConfiguredPid()
    {
        $this->assertEquals('pid=0', $this->extensionConfiguration->appendPidQuery(''));
    }

    public function testAppendPidQueryAppendsDefaultPid()
    {
        $GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['tinyurls'] = serialize(['urlRecordStoragePID' => '999']);
        $this->assertEquals('pid=999', $this->extensionConfiguration->appendPidQuery(''));
    }

    public function testAreSpeakingUrlsEnabledReturnsFalseByDefault()
    {
        $this->assertFalse($this->extensionConfiguration->areSpeakingUrlsEnabled());
    }


    public function testAreSpeakingUrlsEnabledReturnsFalseIfConfigured()
    {
        $GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['tinyurls'] = serialize(['createSpeakingURLs' => '0']);
        $this->assertFalse($this->extensionConfiguration->areSpeakingUrlsEnabled());
    }

    public function testAreSpeakingUrlsEnabledReturnsTrueIfConfigured()
    {
        $GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['tinyurls'] = serialize(['createSpeakingURLs' => '1']);
        $this->assertTrue($this->extensionConfiguration->areSpeakingUrlsEnabled());
    }

    public function testGetBase62DictionaryReturnsConfiguredValue()
    {
        $GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['tinyurls'] = serialize(['base62Dictionary' => 'asfduew']);
        $this->assertEquals('asfduew', $this->extensionConfiguration->getBase62Dictionary());
    }

    public function testGetBase62DictionaryReturnsDefault()
    {
        $this->assertEquals(
            'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789',
            $this->extensionConfiguration->getBase62Dictionary()
        );
    }

    public function testGetMinimalRandomKeyLengthReturnsConfiguredValue()
    {
        $GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['tinyurls'] = serialize(['base62Dictionary' => 'asdf']);
        $this->assertEquals('asdf', $this->extensionConfiguration->getBase62Dictionary());
    }

    public function testGetMinimalRandomKeyLengthReturnsDefault()
    {
        $this->assertEquals(2, $this->extensionConfiguration->getMinimalRandomKeyLength());
    }

    public function testGetMinimalTinyurlKeyLengthReturnsConfiguredValue()
    {
        $GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['tinyurls'] = serialize(['minimalRandomKeyLength' => '31']);
        $this->assertEquals(31, $this->extensionConfiguration->getMinimalRandomKeyLength());
    }

    public function testGetMinimalTinyurlKeyLengthReturnsDefault()
    {
        $this->assertEquals(2, $this->extensionConfiguration->getMinimalRandomKeyLength());
    }

    public function testGetSpeakingUrlTemplateReturnsConfiguredValue()
    {
        $GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['tinyurls'] = serialize(['speakingUrlTemplate' => 'koaidp']);
        $this->assertEquals('koaidp', $this->extensionConfiguration->getSpeakingUrlTemplate());
    }

    public function testGetSpeakingUrlTemplateReturnsDefault()
    {
        $this->assertEquals(
            '###TYPO3_SITE_URL###tinyurl/###TINY_URL_KEY###',
            $this->extensionConfiguration->getSpeakingUrlTemplate()
        );
    }
}