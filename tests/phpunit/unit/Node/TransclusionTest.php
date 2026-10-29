<?php

namespace MWStake\MediaWiki\Component\Wikitext\Tests\Unit\Node;

use MediaWikiUnitTestCase;
use MWStake\MediaWiki\Component\Wikitext\Node\Transclusion;

class TransclusionTest extends MediaWikiUnitTestCase {

	/**
	 * @param array $input
	 * @param array $mutate
	 * @param string $expected
	 * @param bool $allowNew
	 * @dataProvider provideData
	 * @covers \MWStake\MediaWiki\Component\Wikitext\Node\Transclusion::setParam
	 * @covers \MWStake\MediaWiki\Component\Wikitext\Node\Transclusion::getCurrentData
	 */
	public function testPropertyMutation( $input, $mutate, $expected, $allowNew = false ) {
		$node = new Transclusion( ...array_values( $input ) );

		foreach ( $mutate as $index => $value ) {
			$node->setParam( $index, $value, $allowNew );
		}

		$this->assertSame( $expected, $node->getCurrentData() );
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
			'new-param-unnamed' => [
				'input' => [
					'target' => 'DummyTemplate',
					'params' => [ 1 => 'param1', 2 => 'param2' ],
					'wikitext' => "{{DummyTemplate\n|param1|param2}}"
				],
				'mutate' => [
					3 => 'dummy'
				],
				'expected' => "{{DummyTemplate\n|param1|param2|dummy}}",
				'allowNew' => true
			],
			'new-param-named' => [
				'input' => [
					'target' => 'DummyTemplate',
					'params' => [ 'param1' => 'value1', 'param2' => 'value2' ],
					'wikitext' => "{{DummyTemplate\n|param1=value1|param2=value2\n}}"
				],
				'mutate' => [
					'param3' => 'dummy'
				],
				'expected' => "{{DummyTemplate\n|param1=value1|param2=value2\n|param3=dummy}}",
				'allowNew' => true
			],
			'named-param-with-slash' => [
				'input' => [
					'target' => 'DummyTemplate',
					'params' => [ 'role' => 'Role/Management' ],
					'wikitext' => '{{DummyTemplate|role=Role/Management}}'
				],
				'mutate' => [
					'role' => 'Role/Operations'
				],
				'expected' => '{{DummyTemplate|role=Role/Operations}}'
			],
			'named-param-with-regex-characters' => [
				'input' => [
					'target' => 'DummyTemplate',
					'params' => [ 'text' => '{{FULLPAGENAMEE}}' ],
					'wikitext' => '{{DummyTemplate|text={{FULLPAGENAMEE}}}}'
				],
				'mutate' => [
					'text' => 'changed'
				],
				'expected' => '{{DummyTemplate|text=changed}}'
			],
			'new-param-after-nested-template' => [
				'input' => [
					'target' => 'DummyTemplate',
					'params' => [ 'text' => '{{FULLPAGENAMEE}}' ],
					'wikitext' => '{{DummyTemplate|text={{FULLPAGENAMEE}}}}'
				],
				'mutate' => [
					'new' => 'value'
				],
				'expected' => '{{DummyTemplate|text={{FULLPAGENAMEE}}|new=value}}',
				'allowNew' => true
			],
			'replacement-with-dollar' => [
				'input' => [
					'target' => 'DummyTemplate',
					'params' => [ 'text' => 'old' ],
					'wikitext' => '{{DummyTemplate|text=old}}'
				],
				'mutate' => [
					'text' => '$1'
				],
				'expected' => '{{DummyTemplate|text=$1}}'
			],
			'replacement-with-backslash' => [
				'input' => [
					'target' => 'DummyTemplate',
					'params' => [ 'text' => 'old' ],
					'wikitext' => '{{DummyTemplate|text=old}}'
				],
				'mutate' => [
					'text' => 'C:\\new\\path'
				],
				'expected' => '{{DummyTemplate|text=C:\\new\\path}}'
			],
			'duplicate-values-are-all-replaced' => [
				'input' => [
					'target' => 'DummyTemplate',
					'params' => [ 'a' => 'old', 'b' => 'old' ],
					'wikitext' => '{{DummyTemplate|a=old|b=old}}'
				],
				'mutate' => [
					'a' => 'new'
				],
				'expected' => '{{DummyTemplate|a=new|b=old}}'
			],
		];
	}
}
