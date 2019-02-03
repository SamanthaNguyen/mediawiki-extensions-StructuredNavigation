/**
 * MediaWiki UserInterface structured navigation tool.
 *
 * @class
 * @extends ve.ui.FragmentInspectorTool
 * @constructor
 * @param {OO.ui.ToolGroup} toolGroup
 * @param {Object} [config] Configuration options
 */
ve.ui.MWStructuredNavigationInspectorTool = function VeUiMWStructuredNavigationInspectorTool() {
	// Parent constructor
	ve.ui.MWStructuredNavigationInspectorTool.super.apply( this, arguments );
};

/** Inheritance */
OO.inheritClass( ve.ui.MWStructuredNavigationInspectorTool, ve.ui.FragmentInspectorTool );

/** Static properties */
ve.ui.MWStructuredNavigationInspectorTool.static.name = 'struturednavigation';
ve.ui.MWStructuredNavigationInspectorTool.static.group = 'object';
ve.ui.MWStructuredNavigationInspectorTool.static.icon = 'layout'; // @TODO: Replace with custom icon
ve.ui.MWStructuredNavigationInspectorTool.static.title = OO.ui.deferMsg(
	'structurednavigation-visualeditor-mwstructurednavigationinspector-title'
);
ve.ui.MWStructuredNavigationInspectorTool.static.modelClasses = [ ve.dm.MWStructuredNavigationNode ];
ve.ui.MWStructuredNavigationInspectorTool.static.commandName = 'structurednavigation';

/* Registration */
ve.ui.toolFactory.register( ve.ui.MWStructuredNavigationInspectorTool );

ve.ui.commandRegistry.register(
	new ve.ui.Command(
		'structurednavigation', 'window', 'open',
		{
			args: [ 'structurednavigation' ],
			supportedSelections: [ 'linear' ]
		}
	)
);

ve.ui.sequenceRegistry.register(
	new ve.ui.Sequence(
		'wikitextStructuredNavigation',
		'structurednavigation',
		'<mw-navigation',
		15
	)
);

ve.ui.commandHelpRegistry.register(
	'insert',
	'structurednavigation',
	{
		sequences: [ 'wikitextStructuredNavigation' ],
		label: OO.ui.deferMsg(
			'structurednavigation-visualeditor-mwstructurednavigationinspector-title'
		)
	}
);