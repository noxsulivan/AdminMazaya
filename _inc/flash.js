/* Flash Tag Write Object v1.1b - by Lucas Fererira - www.lucasferreira.com */

if(Browser == undefined){
	var Browser = {
		isIE: function(){ return (window.ActiveXObject && document.all && navigator.userAgent.toLowerCase().indexOf("msie") > -1  && navigator.userAgent.toLowerCase().indexOf("opera") == -1) ? true : false; }
	}
}

var Flash = function(movie, id, width, height){

	this.html = "";
	this.attributes = Flash.attributes;
	this.params = Flash.params;
	this.variables = [];
	
	if(movie) {
		this.addAttribute("data", movie);
		this.addParameter("movie", movie);
	}
	
	if(id && id != null) this.addAttribute("id", id);
	if(width) this.addAttribute("width", width);
	if(height) this.addAttribute("height", height);
	
}
Flash.version = "1.1b";
Flash.nl = String.fromCharCode(13);
Flash.attributes = {
	"classid": "clsid:D27CDB6E-AE6D-11cf-96B8-444553540000",
	"codebase": "http://fpdownload.macromedia.com/get/flashplayer/current/swflash.cab#version=8,0,22,0",
	"type": "application/x-shockwave-flash"
}
Flash.params = { "pluginurl": "http://www.macromedia.com/go/getflashplayer_br" }
Flash.getObjectByExceptions = function(obj, excep){
	var tempObj = {};
	for(var i in obj){
		var inclui = true;
		for(var j=0; j<excep.length; j++) {
			if(excep[j] == i.toString()) {
				inclui = false;
				break;
			}
		}
		if(inclui) tempObj[i] = obj[i];
	}
	return tempObj;
}
Flash.prototype.addAttribute = function(prop, val){ this.attributes[prop] = val; }
Flash.prototype.addParameter = function(prop, val){ this.params[prop] = val; }
Flash.prototype.addVariable = function(prop, val){ this.variables.push([prop, val]); }
Flash.prototype.getFlashVars = function(){
	var tempString = [];
	for(var i=0; i<this.variables.length; i++){
		tempString.push(this.variables[i].join("="));
	}
	return tempString.join("&");
}
Flash.prototype.toString = function(){
	
	this.params.flashVars = this.getFlashVars();
	if(Browser.isIE()){
		//IE
		this.html = "<ob" + "ject";
		var attr = Flash.getObjectByExceptions(this.attributes, ["type", "data"]);
		for(var i in attr) if(i.toString() != "extend") this.html += " " + i.toString() + " = \"" + attr[i] + "\"";
		this.html += "> ";
		var params = Flash.getObjectByExceptions(this.params, ["pluginurl", "extend"]);
		for(var i in params) if(i.toString() != "extend") this.html += "<param name=\"" + i.toString() + "\" value=\"" + params[i] + "\" /> ";
		this.html += " </obj" + "ect>";
	} else {
		//non-IE
		this.html = "<!--[if !IE]> <--> <obj" + "ect";
		var attr = Flash.getObjectByExceptions(this.attributes, ["classid", "codebase"]);
		for(var i in attr) if(i.toString() != "extend") this.html += " " + i.toString() + " = \"" + attr[i] + "\"";
		this.html += "> ";
		var params = Flash.getObjectByExceptions(this.params, ["extend"]);
		for(var i in params) if(i.toString() != "extend") this.html += "<param name=\"" + i.toString() + "\" value=\"" + params[i] + "\" /> ";
		this.html += " </obj" + "ect> <!--> <![endif]-->";
	}

	return this.html;
	
}
Flash.prototype.write = Flash.prototype.outIn = function(w){
	if(typeof w == "string") var w = document.getElementById(w);
	if( w != undefined && w ) w.innerHTML = this.toString();
	else document.write( this.toString() );
}