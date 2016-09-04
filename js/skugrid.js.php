<? include("../config.inc.php"); ProtectJS(); ?>
/*
  Items[]
    |-itemid
    |-itemname
    |- ...
    |-Colors[i]
    |-Sizes[i]
    |-SKUIdx['size']['color']=idx
    |-SKUs[i]
       |-SKU{Qty,Id}
    
*/
if (!this.SKUGrid) {
	var SKUGrid = {
		TotalAmount:0,
		TotalQty:0,
		Position:0,
		Items:new Array(),
		FirstSKUInputID:"",
		Initilized:false,
		Cells:null,
		Row:-1,
		Col:-1,
		ShowPrice:true,

		// Event
		OnChanged:null,
		OnInit:null,
		OnRowChanged:null,

		AddItem: function (item)
		{
			this.Items.push(item);

			this.RefreshItem();
			this.MoveLast();
		},
		
		RemoveItem: function ()
		{
			var tmp = new Array();
			for (var i=0; i<this.ItemCount(); i++) {
				if (this.Position != i) {
					tmp.push(this.Items[i]);
				}
			}
			this.Items = tmp;

			if (this.Position > this.ItemCount()-1) {
				this.ShowSKU(this.ItemCount() - 1);
			}
			
			this.RefreshItem();
		},
		
		RefreshItem: function ()
		{
			var div = document.getElementById("rightbox");
			if (div) {
				div.innerHTML = this.GenItemTable();
			}
		},
		
		MoveLast: function () 
		{
			if (this.ItemCount() < 1) return;

			this.ShowSKU(this.ItemCount() - 1);
			if (this.OnRowChanged) {
				this.OnRowChanged();
			}
		},
		
		MoveNext: function () 
		{
			if (this.ItemCount() < 1) return;
			if (this.Position >= this.ItemCount()-1) return "";
			
			this.ShowSKU(this.Position + 1);
			if (this.OnRowChanged) {
				this.OnRowChanged();
			}
		},
		
		MovePrevious: function () 
		{
			if (this.ItemCount() < 1) return;
			if (this.Position < 1) return "";
			
			this.ShowSKU(this.Position - 1);

			if (this.OnRowChanged) {
				this.OnRowChanged();
			}
		},
		
		SetCurrentCell: function (box, s, c)
		{
			if (box) box.select();
			this.Row = c;
			this.Col = s;
		},
		
		FocusLeftCell: function ()
		{
			if (!this.Cells) return;
			if (!this.Cells[this.Row]) return;

			var c = this.Col;			
			
			do {
				c--;				
			} while (c >= 0 && !this.Cells[this.Row][c]);
			
			if (c >= 0) this.Col = c;
			
			this.FocusCell();
		}, 
		
		FocusRightCell: function ()
		{
			if (!this.Cells) return;
			if (!this.Cells[this.Row]) return;

			var c = this.Col;			

			do {
				c++;
			} while (c < this.Cells[this.Row].length && !this.Cells[this.Row][c]);
			
			if (c < this.Cells[this.Row].length) this.Col = c;
			
			this.FocusCell();
		}, 
		
		FocusUpCell: function ()
		{
			if (!this.Cells) return;

			var r = this.Row;			
			
			do {
				r--;				
			} while (r >= 0 && !this.Cells[r][this.Col]);
			
			if (r >= 0) this.Row = r;
			
			this.FocusCell();
		}, 
		
		FocusDownCell: function ()
		{
			if (!this.Cells) return;

			var r = this.Row;
			
			do {
				r++;
			} while (r < this.Cells.length && !this.Cells[r][this.Col]);
			
			if (r < this.Cells.length) this.Row = r;
			
			this.FocusCell();
		}, 
		
		FocusCell: function ()
		{
			if (!this.Cells) return;
			if (!this.Cells[this.Row]) return;
			var id = this.Cells[this.Row][this.Col];

			if (id) {
				var box = document.getElementById(id);
				if (box) box.focus();
			}
		},
		
		ItemClick : function (t, i)
		{
			if (i<0 || i>this.ItemCount-1) return;
			this.ShowSKU(i);
		},
		
		MouseOver: function (t, i)
		{
			t.className = "summarytable_over";
		},
		
		MouseOut: function (t, i)
		{
			if (i == this.Position) {
				t.className = "summarytable_current";
			} else {
				t.className = "summarytable";
			}
		}, 
		
		CurrentUnitPrice: function ()
		{
			var item = this.Items[this.Position];
			if (item) {
				return item.unitprice;
			} else {
				return 0;
			}
		},
		
		Init: function ()
		{
			this.CalcTotal();
			
			if (this.OnInit) {
				this.OnInit();
			}

			this.Initilized = true;
		},
		
		CalcTotal: function ()
		{
			var amount = 0;
			var qty = 0;
			var changed = false;
			
			if (!this.Items) return;
			
			for (var i=0; i<this.Items.length; i++) {
				amount += parseFloat(this.Items[i].amount);
				qty += parseFloat(this.Items[i].qty);
			}
			
			if (this.TotalAmount != amount || this.TotalQty != qty) {
				changed = true;
			}
			
			this.TotalAmount = amount;
			this.TotalQty = qty;

			if (this.Initilized && changed) {
				if (this.OnChanged) {
					this.OnChanged();
				}
			}
		},
		
		SetPrice: function (price)
		{
			var UnitPriceLabel = document.getElementById("unitprice"+this.Position);
			var AmountLabel = document.getElementById("amount"+this.Position);
			var item = this.Items[this.Position];
			var amount = 0;
			
			if (item) {
				item.unitprice = price;
				amount = parseInt(item.qty) * price;
				item.amount = amount;
			}

			UnitPriceLabel.innerHTML = price;
			AmountLabel.innerHTML = amount;

			this.CalcTotal();
		},
		
		OnQtyChanged: function (txt, idx)
		{
			if (!this.Items[this.Position]) return;
			var SKUs = this.Items[this.Position].SKUs;
			var itemQty = 0;
			
			if (!SKUs) return;
			
			if (!SKUs[idx]) {
				SKUs[idx] = new Array();
			} 
			SKUs[idx].qty = txt.value;
			for (var i=0; i<SKUs.length; i++) {
				if (SKUs[i] && SKUs[i].qty) {
					itemQty += parseInt(SKUs[i].qty);
				}
			}
			this.Items[this.Position].qty = itemQty;
			this.Items[this.Position].amount = this.Items[this.Position].unitprice * itemQty;
			
			var QtyLabel = document.getElementById("qty" + this.Position);
			var AmountLabel = document.getElementById("amount" + this.Position);
			if (QtyLabel) {
				QtyLabel.innerHTML = itemQty;
			}
			if (AmountLabel) {
				AmountLabel.innerHTML = this.Items[this.Position].amount;
			}

			this.CalcTotal();
		},
		
		ItemCount: function ()
		{
			if (!this.Items) return 0;
			return this.Items.length;
		},

		GetJSONString: function ()
		{
			return JSON.stringify(this.Items);
		},

		ShowSKU: function (pos)
		{
			var it = document.getElementById("itemtable" + this.Position);
			if (it) {
				it.className = "summarytable";
			}
			
			this.Position = pos;
			
			var it = document.getElementById("itemtable" + pos);
			if (it) {
				it.className = "summarytable_current";
			}
			it.scrollIntoView();
			
			var div = document.getElementById("leftbox");
			if (div) {
				div.innerHTML = this.GenSKUTable();
			}

			if (this.FirstSKUInputID != "") {
				var box = document.getElementById(this.FirstSKUInputID);
				if (box) {
					box.focus();
					box.select();
				}
			}
		},
		
		GenSKUTable: function ()
		{
			var html = "";
			
			var colors = null;
			var sizes = null;
			var SKUIdx = null;
			var SKUs = null;
			
			if (this.ItemCount() < 1) return "";
			if (this.Position > this.ItemCount()-1) return "";
			if (this.Position < 0) return "";

			colors = this.Items[this.Position].Colors;
			sizes = this.Items[this.Position].Sizes;
			SKUIdx = this.Items[this.Position].SKUIdx;
			SKUs = this.Items[this.Position].SKUs;
			 	
			if (this.Cells) {
				for (var i=0; i<this.Cells.length; i++) {
					this.Cells[i] = null;
				}
			}
			this.Cells = new Array();
			
			// SKU Table
			html += "		<table width='100%' cellspacing='0' border='0' class='skutable'>";
			html += "   <thead>";
			html += "		<tr>";
			html += "			<td width='80'>&nbsp;</td>";
			if (sizes && sizes.length > 1) {
				html += "			<td colspan='"+sizes.length+"'>Size</td>";
			} else {
				html += "			<td>Size</td>";
			}
			html += "		</tr>";
			html += "		<tr>";
			html += "			<td>Color</td>";
			if (sizes && sizes.length > 1) {
				for (var i=0; i<sizes.length; i++) {
					html += "			<td>"+sizes[i].substr(1)+"</td>";
				}
			} else {
				html += "			<td>&nbsp;</td>";
			}
			html += "		</tr>";
			html += "   </thead>";

			if (colors && SKUIdx) {
				var name = "";
				var sku = null;
				var idx = ""; qty = "";
				this.FirstSKUInputID = "";
				
				for (var c=0; c<colors.length; c++) { 
					html += "		<tr>";
					html += "			<td class='skutable_size'>"+colors[c].substr(1)+"</td>";
	
					this.Cells[c] = new Array();
					for (var s=0; s<sizes.length; s++) { 
						if (SKUIdx[sizes[s]] && SKUIdx[sizes[s]][colors[c]] != undefined) {
							name = "sku" + s + c;
							idx = SKUIdx[sizes[s]][colors[c]];
							if (SKUs && SKUs[idx] && SKUs[idx].qty) {
								qty = SKUs[idx].qty;
							} else {
								qty = "";
							}
							html += "			<td><input type='textbox' name='"+name+"' value='"+qty+"' onkeypress='CheckNumKey(this)' onkeyup='SKUGrid.OnQtyChanged(this, "+idx+")' onfocus='SKUGrid.SetCurrentCell(this,"+s+","+c+")'></td>";
							
							if (this.FirstSKUInputID == "") {
								this.Row = c;
								this.Col = s;
								this.FirstSKUInputID = name;
							}
							
						} else {
							html += "			<td class='skugrid_disabled_cell'>&nbsp;</td>";
							name = "";
						}
						this.Cells[c][s] = name;
					}
					html += "		</tr>";
				}
			}
			html += "   </table>";
			
			return html;
		},

		GenItemTable: function ()
		{
			var html = "";
			var css = "";
			
			// Item Table
			html += "		<table width='100%' cellspacing='0' border='0' class='summarytable_head' height='30'>";
			html += "		<tr>";
			html += "			<td align='right' >Shift + ";
			html += "				<a href='javascript:SKUGrid.MovePrevious()'><img src='images/up.png' border='0' align='absmiddle'></a>";
			html += "				<a href='javascript:SKUGrid.MoveNext()'><img src='images/down.png' border='0' align='absmiddle'></a>";
			html += "			</td>";
			html += "		</tr>";
			html += "		</table>";

			for (var i=0; i<this.Items.length; i++) {
				css = (this.Position == i)?"_current":"";
				html += "		<table width='100%' cellspacing='0' border='0' class='summarytable"+css+"' ";
				html += "      onmouseover='SKUGrid.MouseOver(this, "+i+")' ";
				html += "      onmouseout='SKUGrid.MouseOut(this, "+i+")' ";
				html += "      onclick='SKUGrid.ItemClick(this, "+i+")' ";
				html += "      id='itemtable"+i+"'>";
				html += "		<tr>";
				html += "			<td colspan='3'>Item : "+this.Items[i].itemno+"&nbsp;&nbsp;&nbsp;"+this.Items[i].itemname+"</td>";
				html += "		</tr>";
				html += "		<tr>";
				html += "			<td>Qty : <span id='qty"+i+"'>"+this.Items[i].qty+"</span></td>";
				if (this.ShowPrice){
					html += "			<td>Price : <span id='unitprice"+i+"'>"+this.Items[i].unitprice+"</span></td>";
					html += "			<td>Amount : <span id='amount"+i+"'>"+this.Items[i].amount+"</span></td>";
				} else {
					html += "			<td>&nbsp;</td>";
					html += "			<td>&nbsp;</td>";
				}
				html += "		</tr>";
				html += "		</table>";
			}

			return html;
		},
		
		Clear: function ()
		{
			this.TotalAmount = 0;
			this.TotalQty = 0;
			this.Position = 0;
			this.Items = new Array();

			if (this.Cells) {
				for (var i=0; i<this.Cells.length; i++) {
					this.Cells[i] = null;
				}
			}
			this.Cells = new Array();

			var divL = document.getElementById("leftbox");
			var divR = document.getElementById("rightbox");
			
			divL.innerHTML = "";
			divR.innerHTML = "";
		},
		
		Generate: function ()
		{
			var html = "";
			
			html += "<table border='0' cellpadding='2' cellspacing='2' class='skugrid' width='100%'>";
			html += "<tr><td width='70%'><div id='leftbox'>";

			// SKU Table
			html += this.GenSKUTable();

			html += "</div></td><td width='30%'><div id='rightbox'>";
			// Item Table
			html += this.GenItemTable();
		
			html += "</div></td></tr>";
			html += "</table>";

			document.write(html);
			
			this.Init();
		}
	}
}
