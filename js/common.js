function Today()
{
	var now = new Date();
	var y = new String(now.getFullYear()).substr(2,2);
	var m = "";
	var d = "";
	if (now.getMonth() + 1 < 10) {
		m = "0" + (now.getMonth() + 1);
	} else {
		m = now.getMonth() + 1;
	}
	if (now.getDate() < 10) {
		d = "0" + (now.getDate());
	} else {
		d = now.getDate();
	}
	return ""+y+m+d;
}

function CheckNumKey(txt)
{
	if (!(event.keyCode >= 48 && event.keyCode <= 57 || event.keyCode == 46 || event.keyCode == 45)) {
		if (!event.altKey && !event.ctrlKey) {
			event.keyCode = 0;
		}
	}
}

