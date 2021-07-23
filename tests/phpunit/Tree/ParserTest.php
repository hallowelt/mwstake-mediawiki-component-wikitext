<?php

namespace MWStake\MediaWiki\Component\Wikitext\Tests\Tree;

use MediaWikiIntegrationTestCase;
use MWStake\MediaWiki\Component\Wikitext\Tree\Parser;
use MWStake\MediaWiki\Component\Wikitext\Tree\Options;

class ParserTest extends MediaWikiIntegrationTestCase {

	/**
	 * @covers MWStake\MediaWiki\Component\Wikitext\Tree\Parser::getNodes
	 */
	public function testGetTargetMatches() {
		$wikitext = $this->provideWikitextData();
		$parser = new Parser( $wikitext, new Options );
		error_log(var_export($parser->getNodes(),1));
	}

	/**
	 *
	 * @return string
	 */
	protected function provideWikitextData() {
		return <<<HERE
* Test
** SubTest
** SubTest2
* SecondTest
** SecondSubTest
*** SecondSubSubTest
*** SecondSubSubTest2
** SecondSubTest2
* LastTest
HERE;
	}

}
