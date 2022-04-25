<?php

namespace MWStake\MediaWiki\Component\Wikitext\Tests\Node;

use MWStake\MediaWiki\Component\Wikitext\Node\Transclusion;
use PHPUnit\Framework\TestCase;

class TransclusionTest extends TestCase {

	/**
	 * @param array $input
	 * @param array $mutate
	 * @param string $expected
	 * @dataProvider provideData
	 * @covers \MWStake\MediaWiki\Component\Wikitext\Node\Transclusion::setParam
	 * @covers \MWStake\MediaWiki\Component\Wikitext\Node\Transclusion::getCurrentWikitext
	 */
	public function testPropertyMutation( $input, $mutate, $expected ) {
		$node = new Transclusion( ...array_values( $input ) );

		foreach ( $mutate as $index => $value ) {
			$node->setParam( $index, $value );
		}

		$this->assertSame( $expected, $node->getCurrentWikitext() );
	}

	public function provideData() {
		return [
			'unnamed-params' => [
				'input' => [
					'target' => 'DummyTemplate',
					'params' => [ 1 => 'param1', 2 => 'param2' ],
					'wikitext' => "{{DummyTemplate\n|param1|param2}}"
				],
				'mutate' => [
					2 => 'mutated'
				],
				'expected' => "{{DummyTemplate\n|param1|mutated}}"
			],
			'named-params' => [
				'input' => [
					'target' => 'DummyTemplate',
					'params' => [ 'param1' => 'value1', 'param2' => 'value2' ],
					'wikitext' => "{{DummyTemplate\n|param1=value1\n|param2=value2}}"
				],
				'mutate' => [
					'param1' => 'mutated'
				],
				'expected' => "{{DummyTemplate\n|param1=mutated\n|param2=value2}}"
			],
			// Nested structures are currently not supported in sense of editing,
			// but it should not break when editing other params
			'nested-template' => [
				'input' => [
					'target' => 'DummyTemplate',
					'params' => [ 1 => 'param1', 2 => '{{Nested template|param1}}' ],
					'wikitext' => "{{DummyTemplate\n|param1|{{Nested template|param1}}}}"
				],
				'mutate' => [
					1 => 'dummy'
				],
				'expected' => "{{DummyTemplate\n|dummy|{{Nested template|param1}}}}"
			],
		];
	}
}
