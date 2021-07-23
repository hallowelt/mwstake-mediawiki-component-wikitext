<?php

namespace MWStake\MediaWiki\Component\Wikitext\Tree;

use MWStake\MediaWiki\Component\Wikitext\ILineReader;
use MWStake\MediaWiki\Component\Wikitext\INodeList;
use MWStake\MediaWiki\Component\Wikitext\IParser;
use MWStake\MediaWiki\Component\Wikitext\Options;

class Parser implements IParser {
	public const OPTION_SEPERATOR = 'seperator';
	public const OPTION_INDICATOR = 'indicator';

	/**
	 *
	 * @var string
	 */
	protected $source = '';
	/**
	 *
	 * @var ILineReader
	 */
	protected $lineReader = null;
	/**
	 *
	 * @var IOptions
	 */
	protected $options = null;
	/**
	 *
	 * @var INodeList
	 */
	protected $nodeList = null;

	/**
	 *
	 * @param string $source
	 * @param ILineReader $lineReader
	 * @param IOptions $options
	 */
	public function __construct( $source, ILineReader $lineReader, Options $options ) {
		$this->source = $source;
		$this->lineReader = $lineReader;
		$this->options = $options;
	}

	/**
	 *
	 * @return INodeList
	 */
	public function getNodeList() :INodeList {
		if ( $this->nodeList ) {
			return $this->nodeList;
		}
		$lines = $this->extractLines();
		$this->nodeList = new NestedNodeList( $this->parseLines( $lines ) );
		return $this->nodeList;
	}

	/**
	 *
	 * @return array
	 */
	protected function extractLines() {
		return explode(
			$this->options->get( static::OPTION_SEPERATOR, "\n" ),
			trim( $this->source )
		);
	}

	/**
	 *
	 * @param string $line
	 * @return INode|false
	 */
	protected function parseLine( $line ) {
		return $this->lineReader->getNode();
		return [
			'text' => trim( $line ),
			'children' => [],
		];
	}

	/**
	 *
	 * @param string $line
	 * @return string
	 */
	protected function preProccessLine( $line ) {
		return trim( $line );
	}

	public function parseLines( $lines ) {
		$lastNode = null;
		$nodes = [];
		$children = [];
		for ( $i = 0; $i < count( $lines ); $i++ ) {
			$cleanLine = $this->preProccessLine( $lines[$i] );
			$indicator = $this->options->get(  static::OPTION_INDICATOR, '*' );
			if( strpos( $cleanLine, $indicator ) !== 0 ) {
				continue;
			}
			if( strpos( $cleanLine, "$indicator$indicator" ) === 0 ) {
				$children[] = substr( $cleanLine, 1 );
				continue;
			}
			if ( isset( $nodes[$lastNode] ) ) {
				$nodes[$lastNode]['children'] = $this->parseLines( $children );
			}
			$children = [];
			$lastNode = $i;
			$nodes[$i] = $this->parseLine( substr( $cleanLine, 1 ) );
		}
		return $nodes;
	}

}
