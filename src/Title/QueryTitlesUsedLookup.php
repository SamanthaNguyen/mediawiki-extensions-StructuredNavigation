<?php

namespace StructuredNavigation\Title;

use StructuredNavigation\Navigation;
use StructuredNavigation\NavigationFactory;

/**
 * This service retrieves all titles that are being used
 * for a given navigation by title.
 *
 * @license MIT
 */
final class QueryTitlesUsedLookup {
	private NavigationFactory $navigationFactory;

	public function __construct( NavigationFactory $navigationFactory ) {
		$this->navigationFactory = $navigationFactory;
	}

	public function getTitlesUsed( string $navigationTitle ) : array {
		$navigation = $this->getNavigation( $navigationTitle );
		$titlesUsed = [];
		$groups = $navigation->getGroups();

		foreach ( $groups as $group ) {
			foreach ( $group->getLinks() as $link ) {
				$titlesUsed[] = $link->getTitle();
			}
		}

		return array_unique( $titlesUsed );
	}

	private function getNavigation( string $title ) : Navigation {
		return $this->navigationFactory->newFromTitle( $title );
	}
}
