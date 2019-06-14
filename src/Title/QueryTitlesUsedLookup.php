<?php

namespace StructuredNavigation\Title;

use StructuredNavigation\Json\JsonEntity;
use StructuredNavigation\Json\JsonEntityFactory;
use Title;

/**
 * This service retrieves all titles that are being used
 * for a given navigation by title.
 *
 * @license MIT
 */
final class QueryTitlesUsedLookup {

	public const EXISTS_OPTION_BOTH = 0;
	public const EXISTS_OPTION_EXISTS = 1;
	public const EXISTS_OPTION_NOT = 2;

	/** @var JsonEntityFactory */
	private $jsonEntityFactory;

	/**
	 * @param JsonEntityFactory $jsonEntityFactory
	 */
	public function __construct( JsonEntityFactory $jsonEntityFactory ) {
		$this->jsonEntityFactory = $jsonEntityFactory;
	}

	/**
	 * @param string $navigationTitle
	 * @return string[]
	 */
	public function getTitlesUsed( string $navigationTitle ) : array {
		$jsonEntity = $this->getJsonEntity( $navigationTitle );
		$titlesUsed = [];
		$allGroups = $jsonEntity->getGroups();

		foreach ( $allGroups as $group ) {
			foreach ( $jsonEntity->getGroupContent( $group ) as $contentItem ) {
				if ( is_array( $contentItem ) ) {
					$titlesUsed[] = $contentItem[0];
				} else {
					$titlesUsed[] = $contentItem;
				}
			}
		}

		return array_unique( $titlesUsed );
	}

	/**
	 * @param int $option
	 * @param string $navigationTitle
	 * @return array
	 */
	public function getTitlesByExistenceDyanmically( int $option, string $navigationTitle ) : array {
		switch ( $option ) {
			case self::EXISTS_OPTION_BOTH:
			default:
				return $this->getTitlesUsed( $navigationTitle );
			case self::EXISTS_OPTION_EXISTS:
				return $this->getExistingTitles( $navigationTitle );
			case self::EXISTS_OPTION_NOT:
				return $this->getNonExistingTitles( $navigationTitle );
		}
	}

	/**
	 * @param string $navigationTitle
	 * @return array
	 */
	public function getExistingTitles( string $navigationTitle ) : array {
		return $this->getTitlesByExistence( $navigationTitle, true );
	}

	/**
	 * @param string $navigationTitle
	 * @return array
	 */
	public function getNonExistingTitles( string $navigationTitle ) : array {
		return $this->getTitlesByExistence( $navigationTitle, false );
	}

	/**
	 * @param string $navigationTitle
	 * @param bool $doesExist
	 * @return array
	 */
	private function getTitlesByExistence( string $navigationTitle, bool $doesExist ) : array {
		$titles = $this->getTitlesUsed( $navigationTitle );
		$allTitles = [];
		foreach ( $titles as $title ) {
			if ( Title::newFromText( $title )->exists() === $doesExist ) {
				$allTitles[] = $title;
			}
		}

		return $allTitles;
	}

	/**
	 * @param string $title
	 * @return JsonEntity
	 */
	private function getJsonEntity( string $title ) : JsonEntity {
		return $this->jsonEntityFactory->newFromTitle( $title );
	}

}
