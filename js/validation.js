function isInteger(t)
{
	var s = new String(t.value);
	var re = /^\s*\d*\s*$/;
	
	if (s.match(re))
		return true
	else
		return false
}

function checkIntKey()
{
	if (event.keyCode < 48 || event.keyCode > 57) {
		event.keyCode = 0;
	}
}