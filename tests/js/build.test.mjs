// The minified runtime is committed (sites need no Node build), so check it
// is current.
import { test } from 'node:test';
import assert from 'node:assert/strict';
import { readFileSync } from 'node:fs';
import { build } from 'esbuild';

const sourcePath = new URL('../../assets/front.js', import.meta.url);
const minPath = new URL('../../assets/front.min.js', import.meta.url);

test('front.min.js is up to date (run `pnpm build`)', async () => {
	const result = await build({
		entryPoints: [sourcePath.pathname],
		minify: true,
		target: 'es2020',
		legalComments: 'none',
		write: false,
	});

	assert.equal(readFileSync(minPath, 'utf8'), result.outputFiles[0].text);
});
