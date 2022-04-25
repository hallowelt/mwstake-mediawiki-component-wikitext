<?php

namespace MWStake\MediaWiki\Component\Wikitext;

use MediaWiki\Revision\RevisionRecord;

interface IParser {
	/**
	 * @return RevisionRecord
	 */
	public function getRevision(): RevisionRecord;
}
