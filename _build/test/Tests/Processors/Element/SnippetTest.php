<?php
/**
* This file is part of the MODX Revolution package.
 *
 * Copyright (c) MODX, LLC
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 *
 * @package modx-test
*/

/**
 * Tests related to element/snippet/ processors
 *
 * @package modx-test
 * @subpackage modx
 * @group Processors
 * @group Element
 * @group Snippet
 * @group SnippetProcessors
 */
class SnippetProcessorsTest extends MODxTestCase {
    const PROCESSOR_LOCATION = 'element/snippet/';

    /**
     * Setup some basic data for this test.
     */
    public function setUp() {
        parent::setUp();
        /** @var modSnippet $snippet */
        $snippet = $this->modx->newObject('modSnippet');
        $snippet->fromArray(array('name' => 'UnitTestSnippet'));
        $snippet->save();
    }

    /**
     * Cleanup data after this test.
     */
    public function tearDown() {
        $snippets = $this->modx->getCollection('modSnippet',array('name:LIKE' => '%UnitTest%'));
        /** @var modSnippet $snippet */
        foreach ($snippets as $snippet) {
            $snippet->remove();
        }
        $this->modx->error->reset();
    }

    /**
     * Tests the element/snippet/create processor, which creates a Snippet
     *
     * @param boolean $shouldPass
     * @param string $snippetPk
     * @dataProvider providerSnippetCreate
     */
    public function testSnippetCreate($shouldPass,$snippetPk) {
        if (empty($snippetPk)) return;
        $result = $this->modx->runProcessor(self::PROCESSOR_LOCATION.'create',array(
            'name' => $snippetPk,
        ));
        if (empty($result)) {
            $this->fail('Could not load '.self::PROCESSOR_LOCATION.'create processor');
        }
        $s = $this->checkForSuccess($result);
        $ct = $this->modx->getCount('modSnippet',array('name' => $snippetPk));
        $passed = $s && $ct > 0;
        $passed = $shouldPass ? $passed : !$passed;
        $this->assertTrue($passed,'Could not create Snippet: `'.$snippetPk.'`: '.$result->getMessage());
    }
    /**
     * Data provider for element/snippet/create processor test.
     * @return array
     */
    public function providerSnippetCreate() {
        return array(
            array(true,'UnitTestSnippet2'),
            array(true,'UnitTestSnippet3'),
            array(false,'UnitTestSnippet'),
        );
    }

    /**
     * Tests the element/snippet/get processor, which gets a Snippet
     * @param boolean $shouldPass
     * @param string $snippetPk
     * @dataProvider providerSnippetGet
     */
    public function testSnippetGet($shouldPass,$snippetPk) {
        if (empty($snippetPk)) return;

        $snippet = $this->modx->getObject('modSnippet',array('name' => $snippetPk));
        if (empty($snippet) && $shouldPass) {
            $this->fail('No Snippet found "'.$snippetPk.'" as specified in test provider.');
            return;
        }

        $result = $this->modx->runProcessor(self::PROCESSOR_LOCATION.'get',array(
            'id' => $snippet ? $snippet->get('id') : $snippetPk,
        ));
        if (empty($result)) {
            $this->fail('Could not load '.self::PROCESSOR_LOCATION.'get processor');
        }
        $passed = $this->checkForSuccess($result);
        $passed = $shouldPass ? $passed : !$passed;
        $this->assertTrue($passed,'Could not get Snippet: `'.$snippetPk.'`: '.$result->getMessage());
    }
    /**
     * Data provider for element/snippet/create processor test.
     * @return array
     */
    public function providerSnippetGet() {
        return array(
            array(true,'UnitTestSnippet'),
            array(false,234),
        );
    }

    /**
     * Attempts to get a list of snippets
     *
     * @param string $sort
     * @param string $dir
     * @param int $limit
     * @param int $start
     * @dataProvider providerSnippetGetList
     */
    public function testSnippetGetList($sort = 'key',$dir = 'ASC',$limit = 10,$start = 0) {
        $result = $this->modx->runProcessor(self::PROCESSOR_LOCATION.'getlist',array(
            'sort' => $sort,
            'dir' => $dir,
            'limit' => $limit,
            'start' => $start,
        ));
        $results = $this->getResults($result);
        $this->assertTrue(!empty($results),'Could not get list of Snippets: '.$result->getMessage());
    }
    /**
     * Data provider for element/snippet/getlist processor test.
     * @return array
     */
    public function providerSnippetGetList() {
        return array(
            array('name','ASC',5,0),
        );
    }

    /**
     * Tests the element/snippet/remove processor, which removes a Snippet
     *
     * @param boolean $shouldPass
     * @param string $snippetPk
     * @dataProvider providerSnippetRemove
     */
    public function testSnippetRemove($shouldPass,$snippetPk) {
        if (empty($snippetPk)) return;

        $snippet = $this->modx->getObject('modSnippet',array('name' => $snippetPk));
        if (empty($snippet) && $shouldPass) {
            $this->fail('No Snippet found "'.$snippetPk.'" as specified in test provider.');
            return;
        }

        $result = $this->modx->runProcessor(self::PROCESSOR_LOCATION.'remove',array(
            'id' => $snippet ? $snippet->get('id') : $snippetPk,
        ));
        if (empty($result)) {
            $this->fail('Could not load '.self::PROCESSOR_LOCATION.'remove processor');
        }
        $passed = $this->checkForSuccess($result);
        $passed = $shouldPass ? $passed : !$passed;
        $this->assertTrue($passed,'Could not remove Snippet: `'.$snippetPk.'`: '.$result->getMessage());
    }
    /**
     * Data provider for element/snippet/remove processor test.
     * @return array
     */
    public function providerSnippetRemove() {
        return array(
            array(true,'UnitTestSnippet'),
            array(false,234),
        );
    }
}
