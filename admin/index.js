// Settings → Mortar Example. Built by `pnpm build` into assets/admin/.
import { createRoot } from '@wordpress/element';
import { __ } from '@wordpress/i18n';

const root = document.getElementById('mortar-example-admin');
const message = window.mortarExampleAdmin?.message || __('Hello from Mortar Example.', 'mortar-example');

if (root) {
	createRoot(root).render(<p className="mortar-example-admin">{message}</p>);
}
