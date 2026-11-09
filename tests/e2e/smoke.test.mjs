import { test } from 'node:test';
import assert from 'node:assert/strict';
import { chromium } from 'playwright';

const base = process.env.MORTAR_EXAMPLE_E2E_URL || 'http://localhost:8888';

test('the front page loads the plugin script', async () => {
	const browser = await chromium.launch();

	try {
		const page = await browser.newPage();
		const response = await page.goto(base, { waitUntil: 'domcontentloaded' });
		assert.equal(response?.ok(), true);

		const src = await page.locator('#mortar-example-js').getAttribute('src');
		assert.match(src ?? '', /assets\/front(\.min)?\.js/);
	} finally {
		await browser.close();
	}
});
