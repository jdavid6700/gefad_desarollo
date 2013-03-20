// Liquid Effect
// finds the width/height of the browser and reloads the page when resized to create a liquid layout
// 19990328

// Copyright (C) 1999 Dan Steinman
// Distributed under the terms of the GNU Library General Public License
// Available at http://www.dansteinman.com/dynapi/

function findWH() {
	winW = (is.ns)? window.innerWidth : document.body.offsetWidth-20
	winH = (is.ns)? window.innerHeight : document.body.offsetHeight-4
}
function makeLiquid() {
	if ((is.ns && (winW!=window.innerWidth || winH!=window.innerHeight)) || is.ie)
	history.go(0)
}
