<?php

namespace StructuredNavigation\View;

use OutputPage;
use OOUI\Tag;
use ParserOutput;
use StructuredNavigation\Json\JsonEntityFactory;

/**
 * @license MIT
 */
final class NavigationViewPresenter {

	/** @var JsonEntityFactory */
	private $jsonEntityFactory;

	/** @var NavigationView */
	private $navigationView;

	/**
	 * @param JsonEntityFactory $jsonEntityFactory
	 * @param NavigationView $navigationView
	 */
	public function __construct(
		JsonEntityFactory $jsonEntityFactory,
		NavigationView $navigationView
	) {
		$this->jsonEntityFactory = $jsonEntityFactory;
		$this->navigationView = $navigationView;
	}

	/**
	 * @param ParserOutput|OutputPage $output
	 * @param string $title
	 * @return Tag|false
	 */
	public function getFromTitle( $output, string $title ) {
		$this->doSetup( $output );
		$jsonEntity = $this->jsonEntityFactory->newFromTitle( $title );
		if ( $jsonEntity === false ) {
			return false;
		}

		return $this->navigationView->getView( $jsonEntity );
	}

	/**
	 * @param ParserOutput|OutputPage $output
	 * @param array $content
	 * @return Tag
	 */
	public function getFromSource( $output, array $content ) {
		$this->doSetup( $output );
		return $this->navigationView->getView(
			$this->jsonEntityFactory->newFromSource( $content )
		);
	}

	/**
	 * @param ParserOutput|OutputPage $output
	 * @return void
	 */
	private function doSetup( $output ) : void {
		OutputPage::setupOOUI();
		$output->addModuleStyles( [
			'ext.structurednavigation.ui.structurednavigation.styles',
			'ext.structurednavigation.ui.structurednavigation.separator.styles'
		] );
	}

}