/**
 * ContentEditable MediaWiki structured navigation node.
 *
 * @class
 * @extends ve.ce.MWBlockExtensionNode
 *
 * @constructor
 * @param {ve.dm.MWStructuredNavigationNode} model Model to observe
 * @param {Object} [config] Configuration options
 */
ve.ce.MWStructuredNavigationNode = function VeCeMWStructuredNavigationNode() {
	// Parent constructor
	ve.ce.MWStructuredNavigationNode.super.apply( this, arguments );

	// DOM changes
	this.$element.addClass( 've-ce-MWStructuredNavigationNode' );
};

/* Inheritance */
OO.inheritClass( ve.ce.MWStructuredNavigationNode, ve.ce.MWBlockExtensionNode );

/* Static properties */
ve.ce.MWStructuredNavigationNode.static.name = 'mwStructuredNavigation';
ve.ce.MWStructuredNavigationNode.static.tagName = 'div';
ve.ce.MWStructuredNavigationNode.static.primaryCommandName = 'structurednavigation';
ve.ce.MWStructuredNavigationNode.static.iconWhenInvisible = 'layout';

/* Registration */
ve.ce.nodeFactory.register( ve.ce.MWStructuredNavigationNode );