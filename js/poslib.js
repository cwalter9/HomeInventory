//<script>
/*
 * Position functions
 *
 * This script was designed for use with DHTML Menu 4
 *
 * This script was created by Erik Arvidsson
 * (http://webfx.eae.net/contact.html#erik)
 * for WebFX (http://webfx.eae.net)
 * Copyright 2002
 * 
 * For usage see license at http://webfx.eae.net/license.html	
 *
 * Version: 1.1
 * Created: 2002-05-28
 * Updated: 2002-06-06	Rewrote to use getBoundingClientRect(). This solved
 *						several bugs related to relative and absolute positened
 *						elements
 *
 *
 */

// This only works in IE5 and IE6+ with both CSS1 and Quirk mode

var posLib = {

	getIeBox:		function (el) {
		return this.ie && el.document.compatMode != "CSS1Compat";
	},
	
	// relative client viewport (outer borders of viewport)
	getClientLeft:	function (el) {
		var pos = this.getElementPos(el);
/*		
		var left = 0;
		if (document.getBoxObjectFor) { 
			var bo = document.getBoxObjectFor(el); 
			left = bo.x; 
		} else if (el.getBoundingClientRect) { 
			var rect = el.getBoundingClientRect(); 
			left = rect.left; 
		} 
*/
		return pos.x - this.getBorderLeftWidth(this.getCanvasElement(el));
	},

	getClientTop:	function (el) {
		var pos = this.getElementPos(el);
/*		
		var top = 0;
		if (document.getBoxObjectFor) { 
			var bo = document.getBoxObjectFor(el); 
			top = bo.y; 
		} else if (el.getBoundingClientRect) { 
			var rect = el.getBoundingClientRect(); 
			top = rect.top; 
		} else {
		Dump("xx");
		} 
*/
		return pos.y - this.getBorderTopWidth(this.getCanvasElement(el));
	},

	// relative canvas/document (outer borders of canvas/document,
	// outside borders of element)
	getLeft:	function (el) {
		return this.getClientLeft(el) + this.getCanvasElement(el).scrollLeft;
	},

	getTop:	function (el) {
		return this.getClientTop(el) + this.getCanvasElement(el).scrollTop;
	},

	// relative canvas/document (outer borders of canvas/document,
	// inside borders of element)
	getInnerLeft:	function (el) {
		return this.getLeft(el) + this.getBorderLeftWidth(el);
	},

	getInnerTop:	function (el) {
		return this.getTop(el) + this.getBorderTopWidth(el);
	},

	// width and height (outer, border-box)
	getWidth:	function (el) {
		return el.offsetWidth;
	},

	getHeight:	function (el) {
		return el.offsetHeight;
	},

	getCanvasElement:	function (el) {
		var doc = el.ownerDocument || el.document;	// IE55 bug
		if (doc.compatMode == "CSS1Compat"){
			return x = doc.documentElement;
		} else {
			return x = doc.body;
		}
	},

	getBorderLeftWidth:	function (el) {
		if (el.clientLeft) {
			return el.clientLeft;
		} else {
			return 0;
		}
	},

	getBorderTopWidth:	function (el) {
		if (el.clientTop) {
			return el.clientTop;
		} else {
			return 0;
		}
	},

	getScreenLeft:	function (el) {
		var doc = el.ownerDocument || el.document;	// IE55 bug
		var w = doc.parentWindow;
		return w.screenLeft + this.getBorderLeftWidth(this.getCanvasElement(el)) +
			this.getClientLeft(el);
	},

	getScreenTop:	function (el) {
		var doc = el.ownerDocument || el.document;	// IE55 bug
		var w = doc.parentWindow;
		return w.screenTop + this.getBorderTopWidth(this.getCanvasElement(el)) +
			this.getClientTop(el);
	},

	getElementPos: function (el) {
		var ua = navigator.userAgent.toLowerCase();
		var isOpera = (ua.indexOf('opera') != -1);
		var isIE = (ua.indexOf('msie') != -1 && !isOpera); // not opera spoof
	
		if(el.parentNode === null || el.style.display == 'none') 
		{
			return false;
		}      
	
		var parent = null;
		var pos = [];     
		var box;     
		
		if(el.getBoundingClientRect)    //IE
		{         
			box = el.getBoundingClientRect();
			var scrollTop = Math.max(document.documentElement.scrollTop, document.body.scrollTop);
			var scrollLeft = Math.max(document.documentElement.scrollLeft, document.body.scrollLeft);
			return {x:box.left + scrollLeft, y:box.top + scrollTop};
		}else if(document.getBoxObjectFor) {   // gecko    
			box = document.getBoxObjectFor(el); 
			var borderLeft = (el.style.borderLeftWidth)?parseInt(el.style.borderLeftWidth):0; 
			var borderTop = (el.style.borderTopWidth)?parseInt(el.style.borderTopWidth):0; 
			pos = [box.x - borderLeft, box.y - borderTop];
		} else {   // safari & opera    
			pos = [el.offsetLeft, el.offsetTop];  
			parent = el.offsetParent;     
			if (parent != el) { 
				while (parent) {  
					pos[0] += parent.offsetLeft; 
					pos[1] += parent.offsetTop; 
					parent = parent.offsetParent;
				}  
			}   
			if (ua.indexOf('opera') != -1 || ( ua.indexOf('safari') != -1 && el.style.position == 'absolute' )) { 
				pos[0] -= document.body.offsetLeft;
				pos[1] -= document.body.offsetTop;         
			}    
		}              
	
		if (el.parentNode) { 
			parent = el.parentNode;
		} else {
			parent = null;
		}
/*
		while (parent && parent.tagName != 'BODY' && parent.tagName != 'HTML') { // account for any scrolled ancestors
			pos[0] -= parent.scrollLeft;
			pos[1] -= parent.scrollTop;
			if (parent.parentNode) {
				parent = parent.parentNode;
			} else {
				parent = null;
			}
		}
*/		
		return {x:pos[0], y:pos[1]};
	}
};

posLib.ua =		navigator.userAgent;
posLib.opera =	/opera [56789]|opera\/[56789]/i.test(posLib.ua);
posLib.ie =		(!posLib.opera) && /MSIE/.test(posLib.ua);
posLib.ie6 =	posLib.ie && /MSIE [6789]/.test(posLib.ua);
posLib.moz =	!posLib.opera && /gecko/i.test(posLib.ua);