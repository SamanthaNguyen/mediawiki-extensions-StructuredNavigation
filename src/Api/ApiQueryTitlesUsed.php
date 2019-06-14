<?php

namespace StructuredNavigation\Api;

use ApiBase;
use ApiQuery;
use ApiQueryBase;
use StructuredNavigation\Title\QueryTitlesUsedLookup;

/**
 * This API module allows finding out all the titles used
 * for a given navigation by title.
 *
 * @license MIT
 */
final class ApiQueryTitlesUsed extends ApiQueryBase {

	private const PARAM_TITLE = 'title';
	private const PARAM_EXISTENCE = 'existence';
	private const PREFIX = 'snqtu';

	/** @var QueryTitlesUsedLookup */
	private $queryTitlesUsedLookup;

	/**
	 * @param ApiQuery $apiQuery
	 * @param string $moduleName
	 * @param QueryTitlesUsedLookup $queryTitlesUsedLookup
	 */
	public function __construct(
		ApiQuery $apiQuery,
		string $moduleName,
		QueryTitlesUsedLookup $queryTitlesUsedLookup
	) {
		parent::__construct( $apiQuery, $moduleName, self::PREFIX );
		$this->queryTitlesUsedLookup = $queryTitlesUsedLookup;
	}

	/** @inheritDoc */
	public function execute() {
		$params = $this->extractRequestParams();
		$title = $params[self::PARAM_TITLE];
		$existence = (int)$params[self::PARAM_EXISTENCE];

		$this->getResult()->addValue(
			'query',
			$this->getModuleName(),
			[ $title => $this->queryTitlesUsedLookup->getTitlesByExistenceDyanmically( $existence, $title ) ]
		);
	}

	/** @inheritDoc */
	public function getAllowedParams() {
		return [
			self::PARAM_TITLE => [
				ApiBase::PARAM_TYPE => 'string',
				ApiBase::PARAM_REQUIRED => true,
			],
			self::PARAM_EXISTENCE => [
				ApiBase::PARAM_TYPE => 'integer',
				ApiBase::PARAM_REQUIRED => false,
			]
		];
	}

	/** @inheritDoc */
	public function isInternal() {
		return true;
	}

	/** @inheritDoc */
	public function getExamplesMessages() {
		return [
			"action=query&prop={$this->getModuleName()}&snqtutitle=Dontnod_Entertainment"
				=> 'apihelp-query+structurednavigationtitlesused-example',
		];
	}

}
