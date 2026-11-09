// Editor UI for the dynamic notice block. The saved wording lives in
// Message::text(); this string has to match it because the editor has no PHP.
(function (wp) {
	'use strict';

	const { registerBlockType } = wp.blocks;
	const { useBlockProps } = wp.blockEditor;
	const { createElement: el } = wp.element;
	const { __ } = wp.i18n;

	registerBlockType('mortar-example/notice', {
		edit() {
			return el('p', useBlockProps(), __('Hello from Mortar Example.', 'mortar-example'));
		},
		save: () => null,
	});
})(window.wp);
