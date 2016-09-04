function Dump(s)
{
	var d = document.getElementById("DebugDiv");
	if (d == undefined) {
		d = document.createElement("DIV");
		d.id = "DebugDiv";
		document.body.appendChild(d);
	}
	d.innerHTML += s + "<br>";
}