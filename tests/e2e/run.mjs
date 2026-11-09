#!/usr/bin/env node
// End-to-end smoke test. By default it runs against wp-env (start it first):
//
//   pnpm exec wp-env start && pnpm test:e2e
//
// or against a local DDEV site that loads the plugin as an MU-plugin:
//
//   MORTAR_EXAMPLE_E2E_ENV=ddev MORTAR_EXAMPLE_E2E_SITE=../path/to/site pnpm test:e2e
import { spawnSync } from 'node:child_process';
import { dirname, resolve } from 'node:path';
import { fileURLToPath } from 'node:url';
import { env, wp } from './env.mjs';

const here = dirname(fileURLToPath(import.meta.url));

if (env.name === 'wp-env') {
	wp(['plugin', 'activate', 'mortar-example']);
}

const result = spawnSync(process.execPath, ['--test', '--test-reporter=spec', resolve(here, 'smoke.test.mjs')], {
	stdio: 'inherit',
	env: { ...process.env, MORTAR_EXAMPLE_E2E_URL: env.url },
});

process.exit(result.status ?? 1);
