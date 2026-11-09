// Front-end runtime. `pnpm build` writes assets/front.min.js, which is what
// ships unless SCRIPT_DEBUG is on. The inline script printed before this
// file sets window.mortarExample.message.
(function () {
	var api = window.mortarExample || {};
	api.ready = true;
	window.mortarExample = api;
})();
