/*______________________________________________________________
 *
 * s@rdalya - s@rm@l Dynamic Framework
 *
 *______________________________________________________________ 
 *
 *
 *    NOTE THAT THIS FILE IS A RATHER SIMPLIFIED VERSION 
 *                      OF S@RDALYA.
 *
 *     YOU CAN HAVE THE MOST UP-TO-DATE VERSION AT
 *
 *           http://www.sarmal.com/sardalya/
 *
 *______________________________________________________________
 *
 * Author      :
 *     Volkan Ozcelik (volkan@sarmal.com)
 *
 * Copyright   :
 *     2003 © sarmal.com
 *
 * About       :
 *     s@rdalya, is a cross-browser compatible system which is
 *     designed to work in all DOM-supporting browsers.
 *     s@rdalya has been tested with internet explorer 6,
 *     netscape navigator 6-7-8beta, Mozilla 1.5-FireFox 1.0.3,
 *     Opera 7.0-7.2-8Beta and more.
 *
 *     At the beginning, s@rdalya started his life as a small API,
 *     mostly driven by the dynamic html programming and scripting
 *     needs of author's projects.
 *
 *     Nevertheless, today, it can stand on its own feet and may
 *     be useful to others. So here it goes.
 *
 *     Version  : 2.2.2.unstable
 *     Platform : linux, windows, freebsd, osx, sun
 *     Size     : 69Kb (compressed)
 *     Price    : Free for uncommercial use -
 *                almost free for commercial use --
 *                see http://www.sarmal.com/sardalya/ for
 *                licensing details
 *
 * Terms of use:
 *     This file (s@rdalya API) is distributed under CC license.
 *
 *     See http://www.sarmal.com/sardalya/Terms.aspx
 *     for terms of use.
 *______________________________________________________________
 *
 * Do you have bug reports, feature requests, wishlists?
 * Or do you simply want to collaborate?
 * Then join us at s@rdalya wiki:
 *
 *             http://sardalya.pbwiki.com/
 *
 * Do you want to see recent buzzes about s@rdalya?
 * Then have a look at s@rdalya blog:
 *
 *             http://www.sarmal.com/sardalya/blog/
 *
 * Do not forget to visit http://www.sarmal.com/sardalya/ for the
 * most up-to-date version of the API.
 * ______________________________________________________________
 *
 * Please do not remove this informative header.
 * If you find s@rdalya useful, why not help others?
 * ______________________________________________________________
 */

/*
 * define external objects for JSLint verification
 * (see http://www.jslint.com/ for details)
 *
 * Thank you Douglas Crockford, a billion times.
 * JSLint has saved hours of debugging and it has revealed
 * tiny little bugs that were around for years but we were not
 * aware of.
 *
 */

/*extern ActiveXObject, Image, XMLHttpRequest */

var _this=null;

/** Sardalya ------------------------------------------------------ **/

var Sardalya=
{
	name:"Sardalya",
	version:"2.2.2.unstable",
	build:"20060603",
	archive:"s@rdalya-s2,2,2.unstable@2006,06,03.zip"
};

/** shortcuts ----------------------------------------------------- **/

var _=
{
	/* AJAX related */
	ajax:function()
	{
		return this.xhr();
	},
	xhr:function()
	{
		return new XHRequest();
	},

	/* DOM related */
	o:function(e)
	{
		return new CBObject(e).getObject();
	},
	id:function(e)
	{
		return new CBObject(e).getID();
	},
	gcn:function(s)
	{
		return DOMManager.getElementsByClassName(s);
	},
	gid:function(s)
	{
		return document.getElementById(s);
	},
	gtn:function(s)
	{
		return document.getElementsByTagName(s);
	},
	elm:function(x)
	{
		return document.createElement(x);
	},
	sweep:function(e)
	{
		return DOMManager.sweep(e);
	},

	/* DOM query */
	child:function(o,p)
	{
		return DOMManager.isChild(o,p);
	},

	/* DOM CSS */
	getclass:function(e)
	{
		return StyleManager.getClass(e);
	},
	setclass:function(e,c)
	{
		StyleManager.setClass(e,c);
	},
	getstyle:function(o,s)
	{
		return StyleManager.getStyle(o,s);
	},
	setstyle:function(o,s,v)
	{
		return StyleManager.setStyle(o,s,v);
	},
	addclass:function(o,a)
	{
		StyleManager.addClass(o,a);
	},
	remclass:function(o,a)
	{
		StyleManager.removeClass(o,a);
	},
	hasclass:function(o,s)
	{
		return StyleManager.hasClassName(o,s);
	},

	/* Custom DOM Objects */
	dl:function(e,c1,c2,b)
	{
		return new DraggableLayer(e,c1,c2,b);
	},
	dyl:function(e)
	{
		return new DynamicLayer(e);
	},
	tip:function(e,i1,i2,l,t)
	{
		return new ToolTip(e,i1,i2,l,t);
	},
	ib:function(e,s1,s2,i)
	{
		return new ImageButton(e,s1,s2,i);
	},
	ir:function(e,s1,s2)
	{
		return new ImageRollover(e,s1,s2);
	},
	wo:function()
	{
		return WindowObject;
	},

	/* Validators */
	integer:function(o)
	{
		return Validator.isInteger(o);
	},
	numeric:function(o)
	{
		return Validator.isNumeric(o);
	},
	positive:function(o)
	{
		return Validator.isPositive(o);
	},
	whitespace:function(o)
	{
		return Validator.isWhiteSpace(o);
	},
	string:function(o)
	{
		return Validator.isString(o);
	},
	defined:function(o)
	{
		return Validator.isDefined(o);
	},
	formelm:function(o)
	{
		return FormManager.isFormElement(o);
	},

	/* Format text */
	safehtml:function(v)
	{
		return TextFormatter.safeHTML(v);
	},
	safews:function(v)
	{
		return TextFormatter.safeWhiteSpace(v);
	},
	qry:function(s)
	{
		return TextFormatter.splitQueryString(s);
	},

	/* All I want is cookies */
	scook:function(t,v,i)
	{
		return CookieManager.set(t,v,i);
	},
	gcook:function(s)
	{
		return CookieManager.get(s);
	},

	/* Event management */
	evto:function(e)
	{
		return new EventObject(e);
	},
	oevt:function(e)
	{
		if(e&&e.getObject)
		{
			return e.getObject();
		}
		else
		{
			return new EventObject(e).getObject();
		}
	},
	stop:function(e)
	{
		if(e&&e.cancelDefaultAction)
		{
			return e.cancelDefaultAction();
		}
		else
		{
			return new EventObject(e).cancelDefaultAction();
		}
	},
	src:function(e,_b)
	{
		if(e&&e.getSource)
		{
			return e.getSource(_b);
		}
		else
		{
			return new EventObject(e).getSource(_b);
		}
	},
	chain:function(a,b,c,d)
	{
		EventHandler.addEventListener(a,b,c,d);
	},
	unchain:function(a,b,c,d)
	{
		EventHandler.removeEventListener(a,b,c,d);
	},

	/* Form related */
	preparefields:function()
	{
		FormManager.prepareFormField();
	},
	field:function(e,s1,s2,s3)
	{
		return new FormField(e,s1,s2,s3);
	},

	/* Dimensional */
	dim:function(x,y)
	{
		return new Dimension(x,y);
	},
	cns:function(x,y,z,t)
	{
		return new Constraint(x,y,z,t);
	},
	coord:function()
	{
		return CursorTracker.getCursorPosition();
	},

	/* Dummy */
	nill:function()
	{
		return Constant.NULL;
	}
};

/** Constant ------------------------------------------------------ **/

var Constant=
{
	Format:
	{
		NUMERIC:1,
		INTEGER:2,
		MOBILE:3,
		EMAIL:4
	},
	Mouse:
	{
		DRAG:0,
		RESIZE:1,
		DOWN:3
	},
	Key:
	{
		ENTER:13,
		BACKSPACE:8,
		TAB:9,
		ESCAPE:27,
		DELETE:46,
		UP:38,
		RIGHT:39,
		DOWN:40,
		LEFT:37,
		ANYKEY:-2,
		CTRL:17,
		ALT:18
	},
	NodeType:
	{
		TEXT:3
	},
	Orientation:
	{
		HORIZONTAL:1,
		VERTICAL:2,
		BOTH:3
	},
	NOT_INITED:-1,
	NULL:function()
	{
	}
};

/** AjaxController ------------------------------------------------ **/

function _AjaxController()
{
	this._intRequestCount=0;
}

_this=_AjaxController.prototype;

_this.incrementCount=function()
{
	this._intRequestCount++;
};

_this.decrementCount=function()
{
	this._intRequestCount--;
};

_this.getActiveThreadCount=function()
{
	return this._intRequestCount;
};

var AjaxController=new _AjaxController();

/** TextFormatter ------------------------------------------------- **/

function _TextFormatter()
{
}

_this=_TextFormatter.prototype;

_this.escape=function(strText)
{
	return encodeURIComponent(strText);
};

_this.safeHTML=function(strSource)
{
    var div = document.createElement('div');
    div.appendChild(document.createTextNode(strSource));
    return div.innerHTML;
};

_this.safeWhiteSpace=function(strSource)
{
	return strSource.trim();
};

_this.splitQueryString=function(strURL)
{
	var strQuery=strURL.match(/^\??(.*)$/)[1];
	var tokens=strQuery.split("&");
	var len=tokens.length;
	var i=0;
	var parts=null;
	var result=[];

	for(i=0;i<len;i++)
	{
		parts=tokens[i].split("=");
		result[parts[0]]=parts[1];
	}

	return result;
};

var TextFormatter=new _TextFormatter();

/** XHRequest ----------------------------------------------------- **/

function XHRequest()
{
	this._fields=[];
	this._values=[];
	this.init();
}

_this=XHRequest.prototype;

_this.removeAllFields=function()
{
	this._fields.length=0;
	this._values.length=0;
};

_this.addField=function(strField,strValue)
{
	this._fields.push(strField);
	this._values.push(TextFormatter.escape(strValue));
};

_this.post=function(strURL,_blnSync)
{
	if(!this._xhr)
	{
		return;
	}

	if(!_blnSync)
	{
		_blnSync=false;
	}

	var uq=this._generateURL(strURL);

	this._xhr.open("POST",uq.url,!_blnSync);
	this._setRequestHeaders();
	this._xhr.setRequestHeader("Content-Type",
		"application/x-www-form-urlencoded");
	this._xhr.send(""+uq.query/*null -- Opera does not like null.*/);

	this._postProcess(_blnSync);
};

_this.postSynchronized=function(strURL)
{
	this.post(strURL,true);
};

_this.get=function(strURL,_blnSync)
{
	if(!this._xhr)
	{
		return null;
	}

	if(!_blnSync)
	{
		_blnSync=false;
	}

	var uq=this._generateURL(strURL);

	this._xhr.open("GET",uq.url+"&"+uq.query,!_blnSync);

	this._setRequestHeaders();
	this._xhr.send("");

	this._postProcess(_blnSync);
};

_this._postProcess=function(blnSync)
{
	if(blnSync)
	{
		/*
		 * note that 99.99% of the time you'll make async requests
		 * so this method will not be executed at all.
		 */
		this._processAsyncRequest();
	}
	else
	{
		AjaxController.incrementCount();
	}
};

_this._processAsyncRequest=function()
{
	var obj=this._xhr;

	if(obj.status==200||obj.status==304)
	{
		this.oncomplete(obj.responseText,obj.responseXML);
	}
	else
	{
		this.onerror(obj.status,obj.statusText);
	}

	this._cleanup();
};

_this.getSynchronized=function(strURL)
{
	this.get(strURL,true);
};

_this.getObject=function()
{
	var request=null;
	var arProgID=null;

	if(this._xhr)
	{
		request=this._xhr;
	}
	else if(window.XMLHttpRequest)
	{
		request=new XMLHttpRequest();
	}
	else if(window.ActiveXObject)
	{
		try
		{
			request=new ActiveXObject("Msxml2.XMLHTTP");
		}
		catch(othermicrosoft)
		{
			try
			{
				request=new ActiveXObject("Microsoft.XMLHTTP");
			}
			catch (failed)
			{
				arProgID=[
					"Msxml2.XMLHTTP.7.0",
					"Msxml2.XMLHTTP.6.0",
					"Msxml2.XMLHTTP.5.0",
					"Msxml2.XMLHTTP.4.0",
					"MSXML2.XMLHTTP.3.0"];

				for(var i=0;i<arProgID.length;i++)
				{
					try
					{
						request=new ActiveXObject(arProgID[i]);
						break;
					}
					catch(ignore)
					{
					}
				}
			}
		}
	}

	return request;
};

_this.finalize=function()
{
	if(!this._xhr)
	{
		return;
	}

	this._cleanup();
	this._xhr=null;
};

_this.init=function()
{
	var sourceElement=this;
	var obj=null;

	this._xhr=this.getObject();

	if(!this._xhr)
	{
		return;
	}

	this._xhr.onreadystatechange=function()
	{
		obj=sourceElement.getObject();

		if(obj.readyState==4)
		{
			/*now that the request is completed*/
			AjaxController.decrementCount();

			/*
			 * 404: file not found.
			 * 200: OK.
			 * 304: reading from cache.
			 */
			if(obj.status==200||obj.status==304)
			{
				sourceElement.oncomplete(obj.responseText,obj.responseXML);
			}
			else
			{
				sourceElement.onerror(obj.status,obj.statusText);
			}

			/*to prevent IE memory leak due to circular COM referencing.*/
			sourceElement._cleanup();
		}
	};
};

_this._setRequestHeaders=function()
{
	this._xhr.setRequestHeader("X-Requested-With","XMLHttpRequest");
	this._xhr.setRequestHeader("X-Sardalya-Version",Sardalya.version);
	this._xhr.setRequestHeader("X-Requested-With","XMLHttpRequest");
	this._xhr.setRequestHeader("Accept",
		"text/javascript, text/html, application/xml, text/xml, */*");
};

_this.abort=function()
{
	/*now that the request is completed*/
	if(AjaxController.getActiveThreadCount()>0)
	{
		AjaxController.decrementCount();
	}

	if(this._xhr)
	{
		this._xhr.abort();
	}

	this._cleanup();
};

_this.oncomplete=function(strResponseText,objResponseXML)
{
	alert("Completed everything.");
};

_this.onerror=function(intStatus,strStatusText)
{
	alert("An error occured but my owner is too lazy to handle it.");
};

_this._cleanup=function()
{
	/* release circular reference to solve the memory leak proble in IE. */
	this._xhr.onreadystatechange=_.nill();
};

_this._generateURL=function(strURL)
{
	var len=this._fields.length;
	var append="";
	if(len>0)
	{
		for(var i=0;i<len;i++)
		{
			append+="&"+this._fields[i]+"="+this._values[i];
		}
	}
	
	if(append==="")
	{
		append="&";
	}

	return {url:(strURL+"?rnd="+Math.random()),query:append.substring(1)};
};

/** Dimension ----------------------------------------------------- **/

function Dimension(x,y)
{
	this._x=x;
	this._y=y;
}

_this=Dimension.prototype;

_this.getX=function()
{
	return this._x;
};

_this.setX=function(x)
{
	this._x=x;
};

_this.getY=function()
{
	return this._y;
};

_this.setY=function(y)
{
	this._y=y;
};

_this.setAll=function(objDimension)
{
	this.setX(objDimension.getX());
	this.setY(objDimension.getY());
};

_this.equals=function(anotherDimension)
{
	return this.getX()==anotherDimension.getX()&&
		this.getY()==anotherDimension.getY();
};

_this.toString=function()
{
	return "("+this.getX()+","+this.getY()+")";
};

/** Constraint ---------------------------------------------------- **/

function Constraint(intMinLower,intMinUpper,intMaxLower,intMaxUpper)
{
	this._lowerBound=_.dim(intMinLower,intMinUpper);
	this._upperBound=_.dim(intMaxLower,intMaxUpper);
}

_this=Constraint.prototype;

_this.getUpperBound=function()
{
	return this._upperBound;
};

_this.getLowerBound=function()
{
	return this._lowerBound;
};

/** Validator ----------------------------------------------------- **/

function _Validator()
{
}

_this=_Validator.prototype;

_this.isDefined=function(x)
{
	return typeof(x)!="undefined";
};

_this.isNull=function(x)
{
	if(!this.isDefined(x))
	{
		return false;
	}

	return x===null;
};

_this.isEmpty=function(x)
{
	if(!this.isDefined(x)||this.isNumeric(x))
	{
		return false;
	}

	return x==="";
};

_this.isEmail=function(x)
{
	if(!this.isDefined(x))
	{
		return false;
	}

	return (/^[A-Za-z0-9]+([_\.-][A-Za-z0-9]+)*@[A-Za-z0-9]+([_\.-][A-Za-z0-9]+)*\.([A-Za-z]){2,4}$/i).test(x);
};

_this.isValidURL=function(x)
{
	if(!this.isDefined(x))
	{
		return false;
	}

	return (/^(https?|ftp|file):\/\/[-A-Z0-9+&@#\/%?=~_|!:,.;]*[-A-Z0-9+&@#\/%=~_|]$/i).test(x);
};


_this.isWhiteSpace=function(x)
{
	if(!this.isDefined(x))
	{
		return false;
	}

	return (/^\s*$/).test(x);
};

_this.isInteger=function(x)
{
	if(!this.isDefined(x))
	{
		return false;
	}

	x=(""+x).replace(/\.0*$/g,"");

	var intNumber=parseInt(x,10);

	if(!isNaN(intNumber)&&(x=x.remove(/^0*/))==="")
	{
		x=0;
	}

	intNumber=parseInt(x,10);

	if((!isNaN(intNumber))&&((""+intNumber)==x))
	{
		return true;
	}

	return false;
};

_this.isFloat=function(x)
{
	if(!this.isDefined(x))
	{
		return false;
	}

	x=(""+x).replace(/,/g,".");

	return x!==""&&!isNaN(x);
};

_this.isNumeric=function(x)
{
	return this.isFloat(x);
};

_this.isString=function(x)
{
	if(!this.isDefined(x))
	{
		return false;
	}

	return typeof(x)=="string";
};

_this.isDate=function(intYear,intMonth,intDay)
{
	var arMonth=[31,28,31,30,31,30,31,31,30,31,30,31];
	var intMaxDay=0;

	if(!this.isDefined(intYear)||!this.isDefined(intMonth)||
		!this.isDefined(intDay)||this.isEmpty(intYear)||
		this.isEmpty(intMonth)||this.isEmpty(intDay))
	{
		return false;
	}

	if((parseInt(intYear,10)%4===0&&parseInt(intYear,10)%100!==0)||
		parseInt(intYear,10)%400===0)
	{
		arMonth[1]=29;
	}
	else
	{
		arMonth[1]=28;
	}

	intMaxDay=arMonth[parseInt(intMonth,10)-1];

	if(parseInt(intDay,10)>intMaxDay)
	{
		return false;
	}

	return true;
};

_this.isPositive=function(x)
{
	if(!this.isNumeric(x))
	{
		return false;
	}

	return parseFloat(x)>=0;
};

_this.isPositiveStrict=function(x)
{
	if(!this.isNumeric(x))
	{
		return false;
	}

	return parseFloat(x)>0;
};

_this.isNegative=function(x)
{
	if(!this.isNumeric(x))
	{
		return false;
	}

	return parseFloat(x)<=0;
};

_this.isNegativeStrict=function(x)
{
	if(!this.isNumeric(x))
	{
		return false;
	}

	return parseFloat(x)<0;
};

_this.validateMarkup=function(strLocation)
{
	if(!strLocation)
	{
		strLocation=window.location.href;
	}

	window.open("http://validator.w3.org/check?uri="+
		TextFormatter.escape(strLocation));
};

_this.validateCSS=function(strLocation)
{
	if(!strLocation)
	{
		strLocation=window.location.href;
	}

	window.open("http://jigsaw.w3.org/css-validator/validator?uri="+
		TextFormatter.escape(strLocation));
};

_this.isDOMEnabled=function()
{
	if(document.getElementById&&document.getElementsByTagName)
	{
		return true;
	}

	return false;
};

_this.isAjaxEnabled=function()
{
	var ajax=_.ajax();

	if(ajax.getObject())
	{
		ajax.abort();
		return true;
	}

	return false;
};

var Validator=new _Validator();

/** Array --------------------------------------------------------- **/

_this=Array.prototype;

_this.indexOf=function(elm)
{
	for(var i=0;i<this.length;i++)
	{
		if(elm==this[i])
		{
			return i;
		}
	}

	return -1;
};

_this.contains=function(elm)
{
	return this.indexOf(elm)!=-1;
};

_this.copy=function()
{
	var theCopy = [];
	var index=this.length;

	while(index--)
	{
		theCopy[index]=(_.defined(this[index].copy))?
			this[index].copy():
			this[index];
	}

	return theCopy;
};

_this.clear=function()
{
    this.length=0;
    return this;
};

_this.first=function()
{
    return this[0];
};

_this.last=function()
{
    return this[this.length - 1];
};

_this.compact=function()
{
	var result=[];
	var len=this.length;
	var i=0;
	var obj=null;
	
	for(i=0;i<len;i++)
	{
		obj=this[i];
		
		if(obj!==null&&typeof(obj)!="undefined")
		{
			if(obj.compact)
			{
				obj.compact();
			}

			result.push(obj);
		}
	}
	
	this.clear();
	
	len=result.length;
	
	for(i=0;i<len;i++)
	{
		this.push(result[i]);
	}
};

/** String -------------------------------------------------------- **/

_this=String.prototype;

_this.trim=function(blnIgnoreCarriage,blnIgnoreInnerWhiteSpace)
{
	var temp=this.replace(/^\s*/,"");
	temp=temp.replace(/\s*$/,"");

	blnIgnoreCarriage=blnIgnoreCarriage?true:false;
	blnIgnoreInnerWhiteSpace=blnIgnoreInnerWhiteSpace?true:false;

	if(blnIgnoreCarriage&&!blnIgnoreInnerWhiteSpace)
	{
		temp=temp.replace(/\t+/g," ");
		temp=temp.replace(/ +/g," ");
	}
	else if(!blnIgnoreCarriage&&blnIgnoreInnerWhiteSpace)
	{
		/* conside unix formats as well */
		temp=temp.replace(/(\r\n|\n\r|\n|\r)+/g,"");
	}
	else if(!blnIgnoreCarriage&&!blnIgnoreInnerWhiteSpace)
	{
		temp=temp.replace(/\s+/g," ");
	}

	/* if only one single whitespace left remove it. */
	if(temp==" ")
	{
		temp="";
	}

	return temp;
};

_this.removeExcess=function(intLen,strPostFix)
{
	if(!_.defined(intLen)||!intLen)
	{
		intLen=50;
	}

	if(!_.defined(strPostFix)||!strPostFix)
	{
		strPostFix="...";
	}

	if(this.length>=intLen)
	{
		return this.substring(0,intLen)+strPostFix;
	}

	return this;
};

_this.remove=function(varRegExp,strOption)
{
	var regEx;

	if(_.string(varRegExp))
	{
		regEx=new RegExp(varRegExp,strOption?strOption:"g");
	}
	else
	{
		regEx=varRegExp;
	}

	return this.replace(regEx,"");
};

_this.removeTags=function()
{
	return this.replace(/<[\/]?([a-zA-Z0-9]+)[^>^<]*>/ig,"");
};

_this.toCamelCase=function()
{
	var arList = this.split("-");
	var strResult="";
	var len=0;
	var i=0;

	if(arList.length==1)
	{
		return arList[0];
	}

	if(this.indexOf("-")===0)
	{
		strResult=arList[0].charAt(0).toUpperCase()+arList[0].substring(1);
	}
	else
	{
		strResult=arList[0];
	}

	len=arList.length;

	for (i=1;i<len;i++)
	{
		strResult+=arList[i].charAt(0).toUpperCase()+arList[i].substring(1);
	}

	return strResult;
};

/** CBObject ------------------------------------------------------ **/

function CBObject(elmID)
{
	this._obj=this._getObject(elmID);
}

_this=CBObject.prototype;

_this.exists=function()
{
	return this.getObject()!==null;
};

_this.getObject=function()
{
	return this._obj;
};

_this.getID=function()
{
	if(this.exists())
	{
		return this.getObject().id;
	}
	else
	{
		return null;
	}
};

_this._getObject=function(elmID)
{
	if(!_.string(elmID))
	{
		return elmID;
	}

	return _.gid(elmID);
};

/** DOMManager ---------------------------------------------------- **/

function _DOMManager()
{
}

_this=_DOMManager.prototype;

_this.getOffset=function(elm)
{
	var ol=0;
	var ot=0;

	if(!(elm=_.o(elm)))
	{
		return _.dim(-1,-1);
	}

	while(true)
	{
		ol+=elm.offsetLeft;
		ot+=elm.offsetTop;
		elm=elm.offsetParent;
		if(!elm)
		{
			break;
		}
	}

	return _.dim(ol,ot);
};

_this.isChild=function(elmTestNode,elmParentNode)
{
	var objTestNode=_.o(elmTestNode);
	var objParentNode=_.o(elmParentNode);
	var theNode=objTestNode;

	if(objTestNode==objParentNode)
	{
		return false;
	}

	while(theNode.nodeName.toLowerCase()!="body")
	{
		if(theNode==objParentNode)
		{
			return true;
		}

		if(theNode.parentNode)
		{
			theNode=theNode.parentNode;
		}
		else
		{
			return false;
		}


	}

	return false;
};

_this.sweep=function(obj)
{
	if(!obj)
	{
		obj=document;
	}

	var children=obj.childNodes;
	var arRemove=[];
	var len=children.length;
	var i=0;

	for(i=0;i<len;i++)
	{
		if(children[i].nodeType==Constant.NodeType.TEXT&&
			_.whitespace(children[i].nodeValue))
		{
			arRemove.push(children[i]);
		}
	}

	len=arRemove.length;

	for(i=0;i<len;i++)
	{
		arRemove[i].parentNode.removeChild(arRemove[i]);
	}

	len=children.length;

	for(i=0;i<len;i++)
	{
		this.sweep(children[i]);
	}
};

_this.getElementsByClassName=function(strClassName,elmParentNode) {
	var children=null;
	var i=0;
	var result=[];

	if(elmParentNode)
	{
		elmParentNode=_.o(elmParentNode);
	}
	else
	{
		elmParentNode=document.body;
	}

	children=elmParentNode.getElementsByTagName("*");

	for(i=0;i<children.length;i++)
	{
		if(children[i].className.match(new RegExp("(^|\\s)"+
			strClassName+"(\\s|$)")))
		{
			result.push(children[i]);
		}
	}

	return result;
};

_this.removeNode=function(elm)
{
	elm=_.o(elm);
	elm.parentNode.removeChild(elm);
};

_this.registerExternalLink=function()
{
	var arLink=_.gtn("a");

	for(var i=0;i<arLink.length;i++)
	{
		if(arLink[i].className.indexOf("newwin")>-1)
		{
			_.chain(arLink[i],"click",this._ExternalLink_click,true);
		}
	}
};

_this.unregisterExternalLink=function()
{
	for(var i=0;i<arguments.length;i++)
	{
		_.unchain(_.o(arguments[i]),"click",this._ExternalLink_click);
	}
};

_this._ExternalLink_click=function(evt)
{
	var e=_.evto(evt);
	var obj=_.src(e);

	if(obj.tagName.toLowerCase()=="img")
	{
		obj=obj.parentNode;
	}

	window.open(obj.href);

	return _.stop(e);
};

var DOMManager=new _DOMManager();

/** WindowObject -------------------------------------------------- **/

function _WindowObject()
{
}

_this=_WindowObject.prototype;

_this.getInnerDimension=function()
{
	var width=-1;
	var height=-1;

	if (self.innerHeight)
	{/*all except ie*/
		width=self.innerWidth;
		height=self.innerHeight;
	}
	else if(document.documentElement&&document.documentElement.clientHeight)
	{/*ie strict*/
		width=document.documentElement.clientWidth;
		height=document.documentElement.clientHeight;
	}
	else if(document.body)
	{/*last trial*/
		width=document.body.clientWidth;
		height=document.body.clientHeight;
	}

	return _.dim(width,height);
};

_this.getScrollOffset=function()
{
	var leftOffset=0;
	var topOffset=0;

	if(document.documentElement)
	{
		/*
		 * in some rarely exceptional cases document.body may happen
		 * to be null: ie6 win.
		 * to test; ctrl+r while moving the cursor.
		 * you should be using some combination of
		 * tooltip + draggablelayer etc.
		 */
		if((document.body)&&_.defined(document.body.scrollLeft))
		{
			leftOffset=Math.max(document.body.scrollLeft,
				document.documentElement.scrollLeft);
			topOffset=Math.max(document.body.scrollTop,
				document.documentElement.scrollTop);
		}
		else
		{
			leftOffset=document.documentElement.scrollLeft;
			topOffset=document.documentElement.scrollTop;
		}
	}
	else
	{/*ie quirksmode*/
		leftOffset=document.body.scrollLeft;
		topOffset=document.body.scrollTop;
	}

	return _.dim(leftOffset,topOffset);
};

_this.scrollTo=function(varX,varY)
{
	var elm=_.o(varX);
	var dim=null;

	if(_.defined(varY))
	{
		dim=_.dim(varX,varY);
	}
	else
	{
		dim=DOMManager.getOffset(elm);
	}

	window.scrollTo(dim.getX(),dim.getY());
};

var WindowObject=new _WindowObject();

/** StyleManager -------------------------------------------------- **/

function _StyleManager()
{
}

_this=_StyleManager.prototype;

_this.getStyle=function(elmID,cssPropertyExtended)
{
	var obj=_.o(elmID);
	var cssProperty=cssPropertyExtended.toCamelCase();

	if(!this.sanityCheck(obj,cssProperty))
	{
		return null;
	}
	else if(obj.currentStyle)
	{
		return obj.currentStyle[cssProperty];
	}
	else if(window.getComputedStyle)
	{
		return window.getComputedStyle(obj,"").getPropertyValue(
			cssPropertyExtended);
	}
	else if(_.defined(obj.style))
	{
		return obj.style[cssProperty];
	}

	return null;
};

_this.setStyle=function(elmID,cssProperty,value)
{
	var obj=_.o(elmID);

	cssProperty=cssProperty.toCamelCase();

	if(!this.sanityCheck(obj,cssProperty))
	{
		return;
	}

	obj.style[cssProperty]=value;
};

_this.setClass=function(elmID,cssClass)
{
	_.o(elmID).className=cssClass;
};

_this.getClass=function(elmID)
{
	return _.o(elmID).className;
};

_this.addClass=function(obj,arClassName)
{
	var strClassName="";
	var i=0;
	obj=_.o(obj);

	for(i=0;i<arClassName.length;i++)
	{
		strClassName=arClassName[i];

		if(!this.hasClassName(obj,strClassName))
		{
			if(obj.className==="")
			{
				obj.className=strClassName;
			}
			else
			{
				obj.className+=" "+strClassName;
			}
		}
	}
};

_this.removeClass=function(obj,arClassName)
{
	var strClassName="";
	var i=0;
	var regClassName=null;
	obj=_.o(obj);

	for(i=0;i<arClassName.length;i++)
	{
		strClassName=arClassName[i];
		regClassName=new RegExp("(^|\\s)"+strClassName+"(\\s|$)");

		if(this.hasClassName(obj,strClassName))
		{
			obj.className=obj.className.replace(regClassName," ");
		}
	}
};

_this.hasClassName=function(obj,strClassName)
{
	obj=_.o(obj);
	return (new RegExp("(^|\\s)"+strClassName+"(\\s|$)")).test(obj.className);
};

_this.activateAlternateStyleSheet=function(strTitle)
{
	var objLink=null;

	for(var i=0;true;i++)
	{
		if(!_.gtn("link")[i])
		{
			break;
		}

		objLink=_.gtn("link")[i];

		if(objLink.getAttribute("rel").indexOf("style")!=-1&&
			objLink.getAttribute("title"))
		{
			objLink.disabled=true;
		}

		if(objLink.getAttribute("title")==strTitle)
		{
			objLink.disabled=false;
		}
	}
};

_this.remember=function(strTitle,intTimeout)
{
	if(!intTimeout)
	{
		intTimeout=14;
	}

	_.scook("alternateCSS",strTitle,intTimeout);
};

_this.recall=function()
{
	var strTitle=_.gcook("alternateCSS");
	if(strTitle)
	{
		this.activateAlternateStyleSheet(strTitle);
	}
};

_this.sanityCheck=function(obj,cssProperty)
{
	obj=_.o(obj);

	if(!obj)
	{
		return false;
	}

	return typeof(obj.style[cssProperty])!="undefined";
};

var StyleManager=new _StyleManager();

/** DraggableLayerController -------------------------------------- **/

function _DraggableLayerController()
{
	this._currentAction=Constant.NOT_INITED;
	this._currentKeyState=Constant.NOT_INITED;
	this._currentMouseState=Constant.NOT_INITED;
	this._activeLayer=null;
	this._beginCoord=_.dim(-1,-1);
	this._LT=_.dim(-1,-1);
	this._WH=_.dim(-1,-1);
	this._arDragLayer=[];
	this._arIgnoreLayer=[];
	this._arDragConstraint=[];
	this._arResizeConstraint=[];
	this._arDragAction=[];
	this._blnEventAttached=false;
	this._dragIndexes=[];
	this._ignoreIndexes=[];
	this._cancelRegisterCount=0;
}

_this=_DraggableLayerController.prototype;

_this.incrementCancelRegisterCount=function()
{
	this._cancelRegisterCount++;
};

_this.decrementCancelRegisterCount=function()
{
	if(this._cancelRegisterCount>0)
	{
		this._cancelRegisterCount--;
	}
};

_this.getCancelRegisterCount=function()
{
	return this._cancelRegisterCount;
};

_this.getDragIndexes=function()
{
	return this._dragIndexes.copy();
};

_this.getIgnoreIndexes=function()
{
	return this._ignoreIndexes.copy();
};

_this.isEventAttached=function()
{
	return this._blnEventAttached;
};

_this.setEventAttached=function(blnValue)
{
	this._blnEventAttached=blnValue;
};

_this.setCurrentAction=function(intState)
{
	this._currentAction=intState;
};

_this.setCurrentKeyState=function(intState)
{
	this._currentKeyState=intState;
};

_this.setCurrentMouseState=function(intState)
{
	this._currentMouseState=intState;
};

_this.setActiveLayer=function(obj)
{
	this._activeLayer=obj;
};

_this.setBeginCoord=function(x,y)
{
	this._beginCoord.setX(x);
	this._beginCoord.setY(y);
};

_this.setLT=function(left,top)
{
	this._LT.setX(left);
	this._LT.setY(top);
};

_this.setWH=function(width,height)
{
	this._WH.setX(width);
	this._WH.setY(height);
};

_this.registerDragLayer=function(obj)
{
	var strID=obj.getID();
	this._arDragLayer[strID]=obj;
	this._dragIndexes.push(strID);
};

_this.registerIgnoreLayer=function(obj)
{
	var strID=obj.getID();
	this._arIgnoreLayer[strID]=obj;
	this._ignoreIndexes.push(strID);
};

_this.registerDragConstraint=function(strID,obj)
{
	this._arDragConstraint[strID]=obj;
};

_this.registerResizeConstraint=function(strID,obj)
{
	this._arResizeConstraint[strID]=obj;
};

_this.registerDragAction=function(strID,intAction)
{
	this._arDragAction[strID]=intAction;
};

_this.getCurrentAction=function()
{
	return this._currentAction;
};

_this.getCurrentKeyState=function()
{
	return this._currentKeyState;
};

_this.getCurrentMouseState=function()
{
	return this._currentMouseState;
};

_this.getActiveLayer=function()
{
	return this._activeLayer;
};

_this.getBeginCoord=function()
{
	return this._beginCoord;
};

_this.getLT=function(left,top)
{
	return this._LT;
};

_this.getWH=function(width,height)
{
	return this._WH;
};

_this.getDragLayer=function(strID)
{
	return this._arDragLayer[strID];
};

_this.getIgnoreLayer=function(strID)
{
	return this._arIgnoreLayer[strID];
};

_this.getDragConstraint=function(strID)
{
	return this._arDragConstraint[strID];
};

_this.getResizeConstraint=function(strID)
{
	return this._arResizeConstraint[strID];
};

_this.getDragAction=function(strID)
{
	return this._arDragAction[strID];
};

var DraggableLayerController=new _DraggableLayerController();

/** EventObject --------------------------------------------------- **/

function EventObject(evt)
{
	if(typeof(evt)=="undefined")
	{
		this._evt=window.event;
	}
	else
	{
		this._evt=evt;
	}
}

_this=EventObject.prototype;

_this.cancelDefaultAction=function()
{
	var e=this._evt;

	if(e.preventDefault)
	{
		e.preventDefault();
	}
	else
	{
		e.returnValue=false;
	}

	return false;
};

_this.getSource=function(_blnDragLayer)
{
	var e=this._evt;	
	var src=e.srcElement?e.srcElement:(e.target?e.target:null);
	var arIgnore=DraggableLayerController.getIgnoreIndexes();
	var i=0;

	if(_blnDragLayer&&src.nodeName)
	{
		/* no drag operation for form elements */
		if(_.formelm(src))
		{
			return null;
		}

		while(true)
		{
			if(src.id)
			{
				for(i=0;i<arIgnore.length;i++)
				{
					if(src.id==arIgnore[i])
					{
						return null;
					}
				}

				if(DraggableLayerController.getDragLayer(src.id))
				{
					return src;
				}
			}

			if(src.parentNode)
			{
				src=src.parentNode;
			}
			else
			{
				return null;
			}

			if(!src.nodeName)
			{
				return null;
			}

			if(!_.defined(src.nodeName)||src.nodeName==="")
			{
				break;
			}
		}
	}

	return src;
};

_this.getObject=function()
{
	return this._evt;
};

/** EventRegistry ------------------------------------------------- **/

function _EventRegistry()
{
	this._arEvent=[];
}

_this=_EventRegistry.prototype;

_this.add=function(objEventModel)
{
	this._arEvent.push(objEventModel);
};

_this.removeAll=function()
{
	while(this._arEvent.length>0)
	{
		this._arEvent.pop().unregister();
	}
};

_this.remove=function(/*varargin*/)
{
	for(var i=0;i<arguments.length;i++)
	{
		var obj=_.o(arguments[i]);
		var arEventModel=this._arEvent;
		
		for(var j=0;j<arEventModel.length;j++)
		{
			if(arEventModel[j].getID()==obj.id)
			{
				arEventModel[j].unregister();
				arEventModel[j]=null;
			}
		}	
	}
	
	arEventModel.compact();
};

var EventRegistry=new _EventRegistry();

/** EventModel ---------------------------------------------------- **/

function EventModel(elmID,strEventType,fncEventListener)
{
	this._elmID=elmID;
	this._strEventType=strEventType;
	this._fncEventListener=fncEventListener;
}

_this=EventModel.prototype;

_this.getID=function()
{
	return _.id(this._elmID);
};

_this.unregister=function()
{
	_.unchain(this._elmID,this._strEventType,this._fncEventListener);
};

_this.register=function()
{
	_.chain(this._elmID,this._strEventType,this._fncEventListener);
};

/** EventHandler -------------------------------------------------- **/

function _EventHandler()
{
	this.addEventListener(window,"unload",this._unregister,true);
}

_this=_EventHandler.prototype;

_this.addEventListener=function(elmID,strEventType,fncEventListener,
blnBypassRegistry)
{
	var	obj;
	var	arObj=[];
	var	arID=null;
	var	arEventType=null;
	var i=0;
	var j=0;

	if(!_.string(elmID))
	{
		arObj.push(elmID);
	}
	else
	{
		arID=elmID.split(/,/);
		for(i=0;i<arID.length;i++)
		{
			arObj.push(_.o(arID[i]));
		}
	}

	for(j=0;j<arObj.length;j++)
	{
		if(!(obj=arObj[j]))
		{
			continue;
		}

		arEventType=strEventType.toLowerCase().split(/,/);

		for(i=0;i<arEventType.length;i++)
		{
			if(_.defined(obj.addEventListener))
			{
				obj.addEventListener(arEventType[i],fncEventListener,false);
			}
			else if(_.defined(obj.attachEvent))
			{
				obj.attachEvent("on"+arEventType[i],fncEventListener);
			}
			else
			{
				alert("ERROR: unknown event handler: " + arEventType[i]);
			}

			if(!blnBypassRegistry)
			{
				EventRegistry.add(new EventModel(elmID,arEventType[i],
					fncEventListener));
			}
		}

	}
};

_this.removeEventListener=function(elmID,strEventType,fncEventListener)
{
	var obj=_.o(elmID);

	if(!obj)
	{
		return;
	}

	strEventType=strEventType.toLowerCase();

	if(obj.removeEventListener)
	{
		obj.removeEventListener(strEventType,fncEventListener,false);
	}
	else if(obj.detachEvent)
	{
		obj.detachEvent("on"+strEventType,fncEventListener);
	}
	else
	{
		alert("ERROR: cannot remove event handler!");
	}
};

_this._unregister=function()
{
	EventRegistry.removeAll();
};

var EventHandler=new _EventHandler();



/** CursorTracker ------------------------------------------------- **/

function _CursorTracker()
{
	this._cursorCoordinates=_.dim(-1,-1);
	this._construct();
}

_this=_CursorTracker.prototype;
var CursorTracker = null;

_this.withinElement=function(objElement,enumOrientation)
{
	var pos=this.getCursorPosition();
	var offset=DOMManager.getOffset(objElement);
	var lyr=new DynamicLayer(objElement);
	var width=lyr.getWidth();
	var height=lyr.getHeight();
	var x=pos.getX();
	var y=pos.getY();
	var ox=offset.getX();
	var oy=offset.getY();
	var blnHorizontalInRange=(x>ox&&x<(ox+width));
	var blnVerticalInRange=(y>oy&&y<(oy+height));

	if(!enumOrientation)
	{
		enumOrientation=Constant.Orientation.BOTH;
	}

	if(enumOrientation==Constant.Orientation.HORIZONTAL)
	{
		return blnHorizontalInRange;
	}
	else if(enumOrientation==Constant.Orientation.VERTICAL)
	{
		return blnVerticalInRange;
	}

	return blnHorizontalInRange&&blnVerticalInRange;
};

_this.getCursorPosition=function()
{
	return this._cursorCoordinates;
};

_this.toString=function()
{
	return this._cursorCoordinates;
};

_this._construct=function()
{
	_.chain(document,"mousemove",this._getCursorPosition,true);
};

_this._getCursorPosition=function(evt)
{
	var posx=0;
	var posy=0;
	var e=_.oevt(evt);

	if(e.pageX||e.pageY)
	{
		posx=e.pageX;
		posy=e.pageY;
	}
	else if(e.clientX||e.clientY)
	{
		posx=e.clientX+WindowObject.getScrollOffset().getX();
		posy=e.clientY+WindowObject.getScrollOffset().getY();
	}

	CursorTracker._cursorCoordinates.setX(posx);
	CursorTracker._cursorCoordinates.setY(posy);
};

CursorTracker=new _CursorTracker();

/** LayerObject --------------------------------------------------- **/

function LayerObject(elmID)
{
	this._obj=_.o(elmID);
}

LayerObject.prototype=new CBObject();
_this=LayerObject.prototype;

_this.setStyle=function(strSelector,strValue)
{
	var obj=null;

	if(!(obj=this.getObject()))
	{
		return;
	}

	_.setstyle(obj,strSelector,strValue);
};

_this.getStyle=function(strSelectorExtended)
{
	var obj=null;

	if(!(obj=this.getObject()))
	{
		return null;
	}

	return _.getstyle(obj,strSelectorExtended);
};

_this.setClass=function(strClass)
{
	_.setclass(this.getObject(),strClass);
};

_this.getClass=function()
{
	return _.getclass(this.getObject());
};

_this.isVisible=function()
{
	return this.getStyle("visibility")!="hidden"&&
		this.getStyle("display")!="none";
};

_this.show=function()
{
	this.setStyle("visibility","visible");
};

_this.hide=function()
{
	this.setStyle("visibility","hidden");
};

_this.collapse=function()
{
	this.setStyle("display","none");
};

_this.expandInline=function()
{
	this.setStyle("display","inline");
};

_this.expand=function()
{
	this.setStyle("display","block");
};

_this.changeContent=function(strNewHTML)
{
	var obj=null;

	if(!(obj=this.getObject()))
	{
		return;
	}

	if(typeof(obj.innerHTML)!="undefined")
	{
		obj.innerHTML=strNewHTML;
	}
};

_this.addContentBefore=function(strHTML)
{
	var obj=null;

	if(!(obj=this.getObject()))
	{
		return;
	}

	if(obj.innerHTML)
	{
		this.changeContent(strHTML+obj.innerHTML);
	}
};

_this.addContentAfter=function(strHTML)
{
	var obj=null;

	if(!(obj=this.getObject()))
	{
		return;
	}

	if(obj.innerHTML)
	{
		this.changeContent(obj.innerHTML+strHTML);
	}
};

/** DynamicLayer -------------------------------------------------- **/

function DynamicLayer(elmID)
{
	this._obj=_.o(elmID);
}

DynamicLayer.prototype=new LayerObject();
_this=DynamicLayer.prototype;

_this.moveTo=function(objLeft,intTop)
{
	var left=0;
	var top=0;

	if(arguments.length==1)
	{
		left=objLeft.getX();
		top=objLeft.getY();
	}
	else
	{
		left=objLeft;
		top=intTop;
	}

	this.setLeft(left);
	this.setTop(top);
};

_this.moveToDeadCentre=function()
{
	this.moveTo(
		Math.floor((WindowObject.getInnerDimension().getX()-
			this.getWidth()+2*WindowObject.getScrollOffset().getX())/2),
		Math.floor((WindowObject.getInnerDimension().getY()-
			this.getHeight()+2*WindowObject.getScrollOffset().getY())/2));
};

_this.resizeTo=function(objWidth,intHeight)
{
	var width=0;
	var height=0;

	if(arguments.length==1)
	{
		width=objWidth.getX();
		height=objWidth.getY();
	}
	else
	{
		width=objWidth;
		height=intHeight;
	}

	this.setWidth(width);
	this.setHeight(height);
};

_this.getLeft=function()
{
	var strLeft=this.getStyle("left");
	var intLeft=parseInt(strLeft,10);

	if(!_.numeric(intLeft)||strLeft.indexOf("%")>-1)
	{
		if(this.getObject().offsetLeft)
		{
			intLeft=this.getObject().offsetLeft;
		}
	}

	return _.numeric(intLeft)?intLeft:0;
};

_this.setLeft=function(intLeft)
{
	this.setStyle("left",intLeft+"px");
};

_this.getTop=function()
{
	var strTop=this.getStyle("top");
	var intTop=parseInt(strTop,10);

	if(!_.numeric(intTop)||strTop.indexOf("%")>-1)
	{
		if(this.getObject().offsetTop)
		{
			intTop=this.getObject().offsetTop;
		}
	}

	return _.numeric(intTop)?intTop:0;
};

_this.setTop=function(intTop)
{
	this.setStyle("top",intTop+"px");
};

_this.getHeight=function()
{
	var obj=null;

	if(!(obj=this.getObject()))
	{
		return 0;
	}

	if(_.defined(obj.offsetHeight))
	{
		return obj.offsetHeight;
	}
	return 0;
};

_this.setHeight=function(intHeight)
{
	var obj=null;
	var intDifference=0;
	var intCssHeight=0;

	if(!(obj=this.getObject()))
	{
		return;
	}

	if(_.defined(obj.offsetHeight))
	{
		this.setStyle("height",intHeight+"px");
		intDifference=(obj.offsetHeight-intHeight);
	}

	if(!_.numeric(intDifference))
	{
		intDifference=0;
	}

	intCssHeight=intHeight-intDifference;

	if(!_.positive(intCssHeight))
	{
		return;
	}

	this.setStyle("height",intCssHeight+"px");
};

_this.getWidth=function()
{
	var obj=null;

	if(!(obj=this.getObject()))
	{
		return 0;
	}

	if(_.defined(obj.offsetWidth))
	{
		return obj.offsetWidth;
	}

	return 0;
};

_this.setWidth=function(intWidth)
{
	var obj=null;
	var intDifference=0;
	var intCssWidth=0;

	if(!(obj=this.getObject()))
	{
		return;
	}

	if(_.defined(obj.offsetWidth))
	{
		this.setStyle("width",intWidth+"px");
		intDifference=(obj.offsetWidth-intWidth);
	}

	if(!_.numeric(intDifference))
	{
		intDifference=0;
	}

	intCssWidth=intWidth-intDifference;

	if(!_.positive(intCssWidth))
	{
		return;
	}

	this.setStyle("width",intCssWidth+"px");
};

_this.getOpacity=function()
{
	var opacity=null;
	var obj=this.getObject();
	var regOp=/alpha\(opacity=(.*)\)/;

	if(!obj)
	{
		return null;
	}

	if((opacity=_.getstyle(obj,"opacity")))
	{
		return parseFloat(opacity);
	}
	else if((opacity=_.getstyle(obj,"filter")))
	{
		opacity=opacity.match(regOp);

		if(opacity[1])
		{
			return parseFloat(opacity[1])/100;
		}
	}

	return 1;
};

_this.setOpacity=function(dblValue)
{
	var obj=this.getObject();

	if(!obj)
	{
		return null;
	}

	/* Look ma, no browser sniffing! */

	if(dblValue==1)
	{
		dblValue=0.99999;
	}
	else if(dblValue<0.00001)
	{
		dblValue=0;
	}

	_.setstyle(obj,"filter","alpha(opacity="+(dblValue*100)+")");
	_.setstyle(obj,"opacity",dblValue);
};

/** DraggableLayer ------------------------------------------------ **/

function DraggableLayer(elmID,constraintMove,constraintResize,blnNoResize)
{
	this._obj=_.o(elmID);
	var id=this.getID();
	var width=this.getWidth();
	var height=this.getHeight();

	DraggableLayerController.registerDragAction(id,Constant.NOT_INITED);

	if(constraintMove)
	{
		DraggableLayerController.registerDragConstraint(id,constraintMove);
	}

	if(constraintResize)
	{
		DraggableLayerController.registerResizeConstraint(id,constraintResize);
	}

	if(blnNoResize)
	{
		DraggableLayerController.registerResizeConstraint(id,
			_.cns(width,height,width,height));
	}

	this._construct();
}

DraggableLayer.prototype=new DynamicLayer();
_this=DraggableLayer.prototype;

_this.ignoreLayer=function(/*varargin*/)
{
	var len=arguments.length;
	for(var i=0;i<len;i++)
	{
		DraggableLayerController.registerIgnoreLayer(
			new DynamicLayer(arguments[i]));
	}
};

_this.fixToDragMode=function()
{
	this._changeAction(Constant.Mouse.DRAG);
};

_this.fixToResizeMode=function()
{
	this._changeAction(Constant.Mouse.RESIZE);
};

_this.releaseFixes=function()
{
	this._changeAction(Constant.NOT_INITED);
};

_this._onKeyDown=function(evt)
{
	var e=_.oevt(evt);
	var intCode=0;
	var lyr=DraggableLayerController.getActiveLayer();

	if(!lyr)
	{
		return;
	}

	if(e)
	{
		intCode=_.defined(e.keyCode)?e.keyCode:e.which;
		/*ctrl, alt, tab*/
		if(Constant.Key.CTRL||Constant.Key.ALT||Constant.Key.TAB)
		{
			return;
		}
	}

	if(DraggableLayerController.getCurrentKeyState()!=Constant.Key.ANYKEY)
	{
		if(lyr.exists())
		{
			if(_.coord())
			{
				DraggableLayerController.setBeginCoord(
					_.coord().getX(),_.coord().getY());
				DraggableLayerController.setLT(lyr.getLeft(),lyr.getTop());
				DraggableLayerController.setWH(lyr.getWidth(),lyr.getHeight());
			}
		}
	}

	DraggableLayerController.setCurrentKeyState(Constant.Key.ANYKEY);
};

_this._onMouseDown=function(evt)
{
	var src=_.src(evt,true);
	var intMaxIndex=0;
	var objLayer=null;/*sýralama için: o anki katman.*/
	var objMaxLayer=null;/*en üstteki katman.*/
	var lyr=new DynamicLayer(src);
	var zIndex=0;
	var arDragID=DraggableLayerController.getDragIndexes();
	var i=0;

	/*
	 * The cursor style may be different in Opera
	 * Cursor changes but the Opera does not render it unless
	 * it hovers on another node
	 * ---
	 * Boþuna kasma.
	 */
	DraggableLayerController.setCurrentMouseState(Constant.Mouse.DOWN);

	if(src)
	{
		DraggableLayerController.setActiveLayer(lyr);

		if(!(src.id))
		{
			alert("ERROR: ID is a required "+
				"propery of Draggablelayer [DraggableLayer.onMouseDown]");
		}
		else
		{
			if(lyr.getStyle("z-index")!==null)
			{
				/*sort layers*/
				for(i=0;i<arDragID.length;i++)
				{
					objLayer=DraggableLayerController.getDragLayer(arDragID[i]);

					zIndex=objLayer.getStyle("z-index");

					if(!_.integer(zIndex)||parseInt(zIndex,10)<1)
					{
						objLayer.setStyle("zIndex",intMaxIndex+1);
						intMaxIndex+=1;
					}
					else
					{
						intMaxIndex=Math.max(intMaxIndex,
							objLayer.getStyle("z-index"));
					}
				}

				for(i=0;i<arDragID.length;i++)
				{
					objLayer=DraggableLayerController.getDragLayer(arDragID[i]);

					if(objLayer.getStyle("z-index")==intMaxIndex)
					{
						objMaxLayer=objLayer;
					}
				}

				if(parseInt(lyr.getStyle("z-index"),10)<intMaxIndex)
				{
					lyr.setStyle("zIndex",intMaxIndex);
					objMaxLayer.setStyle("zIndex",intMaxIndex-1);
				}
			}

			DraggableLayerController.setBeginCoord(_.coord().getX(),
				_.coord().getY());
			DraggableLayerController.setLT(lyr.getLeft(),lyr.getTop());
			DraggableLayerController.setWH(lyr.getWidth(),lyr.getHeight());
		}
	}
};

_this._onMouseUp=function(evt)
{
	var lyr=DraggableLayerController.getActiveLayer();

	if(!lyr)
	{
		return;
	}

	lyr.setStyle("cursor","default");

	DraggableLayerController.setCurrentMouseState(Constant.NOT_INITED);
	DraggableLayerController.setCurrentAction(Constant.NOT_INITED);
	DraggableLayerController.setCurrentKeyState(Constant.NOT_INITED);
	DraggableLayerController.setBeginCoord(-1,-1);
	DraggableLayerController.setLT(-1,-1);
	DraggableLayerController.setWH(-1,-1);
	DraggableLayerController.setActiveLayer(null);
};

_this._onMouseMove=function(evt)
{
	var lyr=DraggableLayerController.getActiveLayer();

	if(!lyr)
	{
		return;
	}

	if(!lyr.exists()||_.formelm(_.src(evt)))
	{
		return;
	}

	var id=lyr.getID();
	var registeredAction=DraggableLayerController.getDragAction(id);
	var cons=null; /* bu layer'a ait constraint nesnesi */
	var intMinTop;  /* constraint'ten gelen */
	var intMinLeft; /* top ve left          */
	var intMaxTop;  /* koordinat            */
	var intMaxLeft; /* aralýklarý           */
	var intMaxWidth;  /* constraint'ten gelen */
	var intMaxHeight; /* width ve height      */
	var intMinWidth;  /* deðer                */
	var intMinHeight; /* aralýklarý           */
	var intCurrentLeft;   /* þu anki         */
	var intCurrentTop;    /* left, top,      */
	var intCurrentWidth;  /* width ve height */
	var intCurrentHeight; /* deðerleri       */
	var left;   /* hesaplanacak    */
	var top;    /* left, top,      */
	var width;  /* width ve height */
	var height; /* deðerleri       */
	var blnInHorizontalRange; /* boyutlarýn doðru aralýkta */
	var blnInVerticalRange;   /* olup olmadýðýný belirten  */
	var blnWidthInRange;      /* kontrol                   */
	var blnHeightRange;       /* flaglarý                  */
	var currentAction=null;
	var dragLT=DraggableLayerController.getLT();
	var dragBeginCoord=DraggableLayerController.getBeginCoord();
	var dragWH=DraggableLayerController.getWH();
	var arIgnore=DraggableLayerController.getIgnoreIndexes();

	if(registeredAction!=Constant.NOT_INITED)
	{
		DraggableLayerController.setCurrentAction(registeredAction);
	}
	else
	{
		if(DraggableLayerController.getCurrentKeyState()==Constant.Key.ANYKEY)
		{
			DraggableLayerController.setCurrentAction(Constant.Mouse.RESIZE);
		}
		else
		{
			DraggableLayerController.setCurrentAction(Constant.Mouse.DRAG);
		}
	}

	currentAction=DraggableLayerController.getCurrentAction();

	if(currentAction==Constant.Mouse.DRAG)
	{
		lyr.setStyle("cursor","move");
	}
	else if(currentAction==Constant.Mouse.RESIZE)
	{
		lyr.setStyle("cursor","se-resize");
	}
	else
	{
		lyr.setStyle("cursor","default");
	}

	for(var i=0;i<arIgnore.length;i++)
	{
		if(arIgnore[i]==id)
		{
			return;
		}
	}

	if(currentAction==Constant.Mouse.DRAG)
	{
		left=dragLT.getX()+_.coord().getX()-dragBeginCoord.getX();
		top=dragLT.getY()+_.coord().getY()-dragBeginCoord.getY();

		if((cons=DraggableLayerController.getDragConstraint(id)))
		{
			intMinTop=cons.getLowerBound().getX();
			intMinLeft=cons.getLowerBound().getY();
			intMaxTop=cons.getUpperBound().getX();
			intMaxLeft=cons.getUpperBound().getY();

			/*kaldýrma; gerekiyor. constraint varken sapýtýyor yoksa.*/
			lyr.setLeft(left);
			lyr.setTop(top);

			intCurrentLeft=lyr.getLeft();
			intCurrentTop=lyr.getTop();

			blnInHorizontalRange=(intCurrentLeft>=intMinLeft&&
				intCurrentLeft<=intMaxLeft);
			blnInVerticalRange=(intCurrentTop>=intMinTop&&
				intCurrentTop<=intMaxTop);

			if(blnInHorizontalRange&&!blnInVerticalRange)
			{
				if(intCurrentTop<=intMinTop)
				{
					top=intMinTop;
				}
				else if(intCurrentTop>=intMaxTop)
				{
					top=intMaxTop;
				}

				lyr.setLeft(left);
				lyr.setTop(top);

				return;
			}
			else if(!blnInHorizontalRange&&blnInVerticalRange)
			{
				if(intCurrentLeft<=intMinLeft)
				{
					left=intMinLeft;
				}
				else if(intCurrentLeft>=intMaxLeft)
				{
					left=intMaxLeft;
				}

				lyr.setLeft(left);
				lyr.setTop(top);

				return;
			}
			else if(!blnInHorizontalRange&&!blnInVerticalRange)
			{
				if(intCurrentTop<=intMinTop)
				{
					top=intMinTop;
				}
				else if(intCurrentTop>=intMaxTop)
				{
					top=intMaxTop;
				}

				if(intCurrentLeft<=intMinLeft)
				{
					left=intMinLeft;
				}
				else if(intCurrentLeft>=intMaxLeft)
				{
					left=intMaxLeft;
				}

				lyr.setLeft(left);
				lyr.setTop(top);

				return;
			}
		}

		lyr.setLeft(left);
		lyr.setTop(top);
	}/*end if drag mode*/
	else
	{
		width=dragWH.getX()+_.coord().getX()-dragBeginCoord.getX();
		height=dragWH.getY()+_.coord().getY()-dragBeginCoord.getY();

		if((cons=DraggableLayerController.getResizeConstraint(id)))
		{
			intMaxWidth=cons.getUpperBound().getX();
			intMaxHeight=cons.getUpperBound().getY();
			intMinWidth=cons.getLowerBound().getX();
			intMinHeight=cons.getLowerBound().getY();

			/*kaldýrma; gerekiyor. constraint varken sapýtýyor yoksa.*/
			lyr.setWidth(width<0?10:width);
			lyr.setHeight(height<0?10:height);

			intCurrentWidth=lyr.getWidth();
			intCurrentHeight=lyr.getHeight();

			blnWidthInRange=(intCurrentWidth>=intMinWidth&&
				intCurrentWidth<=intMaxWidth);
			blnHeightRange=(intCurrentHeight>=intMinHeight&&
				intCurrentHeight<=intMaxHeight);

			if(blnWidthInRange&&!blnHeightRange)
			{
				if(intCurrentHeight<=intMinHeight)
				{
					height=intMinHeight;
				}
				else if(intCurrentHeight>=intMaxHeight)
				{
					height=intMaxHeight;
				}
			}
			else if(!blnWidthInRange && blnHeightRange)
			{
				if(intCurrentWidth<=intMinWidth)
				{
					width=intMinWidth;
				}
				else if(intCurrentWidth>=intMaxWidth)
				{
					width=intMaxWidth;
				}
			}
			else if(!blnWidthInRange&&!blnHeightRange)
			{
				if(intCurrentHeight<=intMinHeight)
				{
					height=intMinHeight;
				}
				else if(intCurrentHeight>=intMaxHeight)
				{
					height=intMaxHeight;
				}

				if(intCurrentWidth<=intMinWidth)
				{
					width=intMinWidth;
				}
				else if(intCurrentWidth>=intMaxWidth)
				{
					width=intMaxWidth;
				}
			}
		}

		lyr.setWidth((width<=10)?10:width);
		lyr.setHeight((height<=10)?10:height);
	}
};

_this._changeAction=function(strAction)
{
	DraggableLayerController.registerDragAction(this.getID(),strAction);
};

_this._construct=function()
{
	var objSelf=this.getObject();
	var objEvent=null;
	var strNode="";
	var cancelEvent=null;

	if(this.exists()&&this.getID())
	{
		DraggableLayerController.registerDragLayer(this);

		cancelEvent=function(evt)
		{
			objEvent=_.evto(evt);
			strNode=_.src(objEvent).nodeName.toLowerCase();

			if(strNode=="input"||strNode=="select"||
				strNode=="option"||strNode=="textarea")
			{
				return;
			}
			else
			{
				_.stop(objEvent);
			}
		};

		_.chain(objSelf,"mousedown",
			function(evt)
			{
				DraggableLayerController.incrementCancelRegisterCount();
				_.chain(document,"mousemove",cancelEvent,true);
				cancelEvent(evt);
			}
		);

		_.chain(objSelf,"mouseup",
			function(evt)
			{
				while(DraggableLayerController.getCancelRegisterCount()>0)
				{
					_.unchain(document,"mousemove",cancelEvent,true);
					DraggableLayerController.decrementCancelRegisterCount();
				}
			}
		);

		if(!DraggableLayerController.isEventAttached())
		{
			/* 
			 * note that we're explicitly bypassing the EventRegistry.
			 * Since there is no closure, no need to register the Event
			 * to the EventRegistry.
			 */
			_.chain(document,"keydown",this._onKeyDown,true);
			_.chain(document,"mousedown",this._onMouseDown,true);
			_.chain(document,"mouseup",this._onMouseUp,true);
			_.chain(document,"mousemove",this._onMouseMove,true);
			DraggableLayerController.setEventAttached(true);
		}
	}
	else
	{
		alert("ERROR: object reference "+
			"not found or ID missing : DraggableLayer:_construct");
		return;
	}
};

/** ModalDialog --------------------------------------------------- **/

function ModalDialog(elmBgLayer,elmDragLayer,elmContentLayer,elmCloseBtn)
{
	var self=null;

	this._bgLayer=new DynamicLayer(elmBgLayer);
	this._dragLayer=new DraggableLayer(elmDragLayer);
	this._dragLayer.fixToDragMode();
	this._obj=this._dragLayer.getObject();
	this.moveToDeadCentre();

	this._contentLayer=new DynamicLayer(elmContentLayer);
	this._blnDisableClose=false;

	/*for opera*/
	this._bgLayer.collapse();

	if(_.defined(elmCloseBtn))
	{
		self=this;

		_.chain(elmCloseBtn,"click",
			function(evt)
			{
				if(!self.isCloseDisabled())
				{
					self._contentLayer.changeContent("");
					self.hide();
				}
			}
		);
	}
}

ModalDialog.prototype=new DynamicLayer();
_this=ModalDialog.prototype;

_this.disableClose=function()
{
	this._blnDisableClose=true;
};

_this.enableClose=function()
{
	this._blnDisableClose=false;
};

_this.isCloseDisabled=function()
{
	return this._blnDisableClose;
};

_this.show=function(strHTML)
{

	if(typeof(strHTML)!="undefined")
	{
		this._contentLayer.changeContent(strHTML);
	}

	this._bgLayer.expand();
	this.expand();
	this.moveToDeadCentre();
	
	this._replaceCombos();

};

_this._replaceCombos=function(blnReplaceBack)
{
	var arSelect = document.getElementsByTagName("select");
	var len=arSelect.length;
	var objSel=null;
	var strNodeValue="";
	var objSpan=null;
	var o=null;

	if(!blnReplaceBack)
	{
		blnReplaceBack=false;
	}
	
	for(var i=0;i<len;i++)
	{
		objSel=arSelect[i];
		strNodeValue=objSel.childNodes[objSel.selectedIndex].childNodes[0].nodeValue;

		objSpan=new CBObject(objSel.id+"_ModalWrap");
		if(objSpan.exists())
		{
			o=objSpan.getObject();
			o.parentNode.removeChild(o);
		}

		objSpan=document.createElement("span");
		objSpan.id=objSel.id+"_ModalWrap";
		objSpan.appendChild(document.createTextNode(strNodeValue));
		objSpan.className="modalWrap";
		objSel.parentNode.insertBefore(objSpan,objSel);

		
		if(blnReplaceBack)
		{
			new DynamicLayer(objSpan).collapse();
			new DynamicLayer(objSel).expandInline();
		}
		else
		{
			new DynamicLayer(objSpan).expandInline();
			new DynamicLayer(objSel).collapse();
		}
	}
};

_this.hide=function()
{
	this._bgLayer.collapse();
	this.collapse();
	this._replaceCombos(true);
	this._blnDisableClose=false;
};

/** FormManager --------------------------------------------------- **/

function _FormManager()
{
}

_this=_FormManager.prototype;

_this.prepareFormField=function()
{
	var obj=null;
	for(var i=0;i<arguments.length;i++)
	{
		obj=_.o(arguments[i]);
		if(obj.nodeName.toLowerCase()=="textarea")
		{
			if(/\r\n/i.test(obj.value))
			{/*windows line break*/
				obj.value=obj.value.replace(/\r\n/ig,"[br /]");
				obj.value=_.safehtml(obj.value);
				obj.value=_.safews(obj.value);
				obj.value=obj.value.replace(/\[br \/\]/ig,"\r\n");
			}

			if(/\r/i.test(obj.value))
			{/*unix line break*/
				obj.value=obj.value.replace(/\r/ig,"[br /]");
				obj.value=_.safehtml(obj.value);
				obj.value=_.safews(obj.value);
				obj.value=obj.value.replace(/\[br \/\]/ig,"\r");
			}
		}
		else
		{
			obj.value=_.safehtml(obj.value);
			obj.value=_.safews(obj.value);
		}

	}
};

_this.repaintElement=function(objSrc,blnFocused)
{
	var strDefaultClass=objSrc._defaultClass;
	var strFocusClass=objSrc._focusClass;
	var strErrorClass=objSrc._errorClass;

	_.remclass(objSrc,[strErrorClass,strFocusClass,strDefaultClass]);

	if(objSrc._hasError)
	{
		_.addclass(objSrc,[strErrorClass]);
	}
	else
	{
		if(blnFocused)
		{
			_.addclass(objSrc,[strFocusClass]);
		}
		else
		{
			_.addclass(objSrc,[strDefaultClass]);
		}
	}
};

_this.isFormElement=function(elm)
{
	var theNodeName="";
	elm=_.o(elm);

	if(!elm)
	{
		return false;
	}

	theNodeName=elm.nodeName.toLowerCase();

	return theNodeName=="input"||theNodeName=="textarea"||
		theNodeName=="select"||theNodeName=="option";
};

var FormManager=new _FormManager();