<?php

namespace StructuredNavigation\Specials;

use HTMLForm;
use HTMLTitleTextField;
use FormSpecialPage;
use StructuredNavigation\Libs\MediaWiki\NamespacedTitleSearcher;
use StructuredNavigation\Libs\OOUI\Element\UnorderedList;
use StructuredNavigation\Title\QueryTitlesUsedLookup;
use StructuredNavigation\Title\NavigationTitleValue;

/**
 * This special page allows looking up all the titles used for
 * a given navigation by name.
 *
 * @license MIT
 */
final class TitlesUsedInNavigation extends FormSpecialPage {

	private const FIELD_TITLE = 'title';
	private const FIELD_EXISTENCE = 'existence';
	private const MESSAGE_LEGEND = 'specials-titlesusedinnavigation-legend';
	private const MESSAGE_TITLE_LABEL = 'specials-titlesusedinnavigation-field-title-label';
	private const MESSAGE_TITLE_PLACEHOLDER = 'specials-titlesusedinnavigation-field-title-placeholder';
	private const MESSAGE_EXISTS_LABEL = 'specials-titlesusedinnavigation-field-exists-label';
	private const MESSAGE_EXISTS_OPTION_EXISTS = 'specials-titlesusedinnavigation-field-exists-option-exists';
	private const MESSAGE_EXISTS_OPTION_NOT_EXISTS = 'specials-titlesusedinnavigation-field-exists-option-not';
	private const MESSAGE_EXISTS_OPTION_BOTH = 'specials-titlesusedinnavigation-field-exists-option-both';
	private const PAGE_NAME = 'TitlesUsedInNavigation';

	/** @var QueryTitlesUsedLookup */
	private $queryTitlesUsedLookup;

	/** @var NamespacedTitleSearcher */
	private $namespacedTitleSearcher;

	/** @var NavigationTitleValue */
	private $navigationTitleValue;

	/**
	 * @param QueryTitlesUsedLookup $queryTitlesUsedLookup
	 * @param NamespacedTitleSearcher $namespacedTitleSearcher
	 * @param NavigationTitleValue $navigationTitleValue
	 */
	public function __construct(
		QueryTitlesUsedLookup $queryTitlesUsedLookup,
		NamespacedTitleSearcher $namespacedTitleSearcher,
		NavigationTitleValue $navigationTitleValue
	) {
		parent::__construct( self::PAGE_NAME );

		$this->queryTitlesUsedLookup = $queryTitlesUsedLookup;
		$this->namespacedTitleSearcher = $namespacedTitleSearcher;
		$this->navigationTitleValue = $navigationTitleValue;
	}

	/** @inheritDoc */
	protected function getGroupName() {
		return Constants::SPECIAL_PAGE_GROUP;
	}

	/** @inheritDoc */
	public function alterForm( HTMLForm $htmlForm ) {
		$htmlForm
			->setWrapperLegendMsg( $this->msg( self::MESSAGE_LEGEND ) );
	}

	/** @inheritDoc */
	protected function getDisplayFormat() {
		return Constants::HTMLFORM_FORMAT_OOUI;
	}

	/** @inheritDoc */
	public function onSubmit( array $formData, $htmlForm = null ) {
		$htmlForm->setPostText(
			$this->getTitleList( $formData )
		);
	}

	/** @inheritDoc */
	protected function getFormFields() {
		return [
			self::FIELD_TITLE => [
				'class' => HTMLTitleTextField::class,
				'default' => $this->par ?
					$this->navigationTitleValue
						->getTitleValue( $this->par )
						->getText() : '',
				'label-message' => self::MESSAGE_TITLE_LABEL,
				'placeholder-message' => self::MESSAGE_TITLE_PLACEHOLDER,
				'namespace' => NS_NAVIGATION,
				'exists' => true,
				'relative' => true,
				'creatable' => true
			],
			self::FIELD_EXISTENCE => [
				'type' => 'radio',
				'label-message' => self::MESSAGE_EXISTS_LABEL,
				'options-messages' => [
					self::MESSAGE_EXISTS_OPTION_BOTH => QueryTitlesUsedLookup::EXISTS_OPTION_BOTH,
					self::MESSAGE_EXISTS_OPTION_EXISTS => QueryTitlesUsedLookup::EXISTS_OPTION_EXISTS,
					self::MESSAGE_EXISTS_OPTION_NOT_EXISTS => QueryTitlesUsedLookup::EXISTS_OPTION_NOT,
				],
				'default' => 0,
			],
		];
	}

	/** @inheritDoc */
	public function prefixSearchSubpages( $search, $limit, $offset ) {
		return $this->namespacedTitleSearcher
			->getTitlesInNamespace( $search, $limit, $offset, NS_NAVIGATION );
	}

	/**
	 * @param array $formData
	 * @return UnorderedList
	 */
	private function getTitleList( array $formData ) : UnorderedList {
		return new UnorderedList( [
			'items' => $this->queryTitlesUsedLookup->getTitlesByExistenceDyanmically(
				$formData[self::FIELD_EXISTENCE],
				$formData[self::FIELD_TITLE]
			)
		] );
	}
}
