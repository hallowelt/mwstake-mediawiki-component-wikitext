<?php

namespace MWStake\MediaWiki\Component\Wikitext;

use MediaWiki\Revision\RevisionRecord;

interface IParser {
	public function getRevision(): RevisionRecord;
}
