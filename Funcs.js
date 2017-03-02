
function open_close_group(rowID){

//  alert (rowID);
//  alert (document.all(rowID+"Btn").src);

  var Tabrows = document.all(rowID+"Btn");

	if (document.all(rowID+"Btn").src.search("plus") != -1) {
	  open_group(rowID);
	}else{
	  close_group(rowID);
	}

}

function HideDisplay(oItems) {

	oItems.style.display = "none";

}

function open_group(rowID){

  var Tabrows = document.all(rowID+"chlds");
  document.all(rowID+"Btn").src = "images/minus.jpg"

if (document.all(rowID+"chlds")) {
	if (document.all(rowID+"chlds").length) {
		for(var ii=0;ii<document.all(rowID+"chlds").length;ii++) {
	        Tabrows[ii].style.display = "block";
	    }
	}else{
		document.all(rowID+"chlds").style.display = "block";
	}

}
}

function close_group(rowID){

// alert (rowID);

if (document.all(rowID+"chlds")) {

  var Tabrows = document.all(rowID+"chlds");
  document.all(rowID+"Btn").src = "images/plus.jpg"

  if (document.all(rowID+"chlds").length) {

	  for(var ii=0;ii<document.all(rowID+"chlds").length;ii++) {
			close_group(rowID+"-"+(ii+1));
	        Tabrows[ii].style.display = "none";
	  }
	}else{
		document.all(rowID+"chlds").style.display = "none";
	}
}
}


function HideDisplay(oItems) {

	oItems.style.display = "none";

}

function ShowDisplay(oItems) {

	oItems.style.display = "block";

}


function openUpdW(page,Prs,WeekS,Dt,CurrFile) {
    msgWindow = window.open("","UpdWin","resizable=no,scrollbars=yes,Status=yes,width=500,height=500,top=50,left=200");
    msgWindow.location.href = page+'?PresentID='+Prs+'&WeekS='+WeekS+'&FullDate='+Dt+'&CurrFile='+CurrFile;
    msgWindow.creator=self
}


function openFrmW(page,Frm) {
    msgWindow = window.showModalDialog(page+'?Frm='+Frm, 'FrmWin', 'scrollbars=yes,Status=yes,width=600,height=500,top=50,left=200');
}



function openChSW(page,RecID) {
    msgWindow = window.open('', 'UpdWin', 'resizable=no,scrollbars=yes,Status=yes,width=300,height=100,top=50,left=200');
    msgWindow.location.href = page+'?RecID='+RecID;
    msgWindow.creator=self

}

function check_HH(field){
var checkstr = "0123456789";
var HHField = field;
var HHvalue = "";
var HHTemp = "";
var err = 0;
var i;
   err = 0;
   HHValue = HHField.value;
   /* Delete all chars except 0..9 */
   for (i = 0; i < HHValue.length; i++) {
	  if (checkstr.indexOf(HHValue.substr(i,1)) >= 0) {
	     HHTemp = HHTemp + HHValue.substr(i,1);
	  }
   }
   HHValue = HHTemp;

   if (HHValue > 23)  {
      err = 1;
   }

   if (err == 0) {
      HHField.value = HHValue;
   }else{
      alert("��� �� �����");
      HHField.select();
	  HHField.focus();
   }
}

function check_MM(field){
var checkstr = "0123456789";
var MMField = field;
var MMvalue = "";
var MMTemp = "";
var err = 0;
var i;
   err = 0;
   MMValue = MMField.value;
   /* Delete all chars except 0..9 */
   for (i = 0; i < MMValue.length; i++) {
	  if (checkstr.indexOf(MMValue.substr(i,1)) >= 0) {
	     MMTemp = MMTemp + MMValue.substr(i,1);
	  }
   }
   MMValue = MMTemp;

   if (MMValue > 59)  {
      err = 1;
   }

   if (err == 0) {
      MMField.value = MMValue;
   }else{
      alert("���� �� ������");
      MMField.select();
	  MMField.focus();
   }
}

function check_date(field){
var checkstr = "0123456789";
var DateField = field;
var Datevalue = "";
var DateTemp = "";
var seperator = "/";
var day;
var month;
var year;
var leap = 0;
var err = 0;
var i;
   err = 0;
   DateValue = DateField.value;
   /* Delete all chars except 0..9 */
   for (i = 0; i < DateValue.length; i++) {
	  if (checkstr.indexOf(DateValue.substr(i,1)) >= 0) {
	     DateTemp = DateTemp + DateValue.substr(i,1);
	  }
   }
   DateValue = DateTemp;
   /* Always change date to 8 digits - string*/
   /* if ( year is entered as 2-digit / always assume 20xx */
   if (DateValue.length == 6) {
      DateValue = DateValue.substr(0,4) + '20' + DateValue.substr(4,2); }
   if (DateValue.length != 8) {
      err = 19;}
   /* year is wrong if ( year = 0000 */
   year = DateValue.substr(4,4);
   if (year == 0) {
      err = 20;
   }
   /* Validation of month*/
   month = DateValue.substr(2,2);
   if ((month < 1) || (month > 12)) {
      err = 21;
   }
   /* Validation of day*/
   day = DateValue.substr(0,2);
   if (day < 1) {
     err = 22;
   }
   /* Validation leap-year / february / day */
   if ((year % 4 == 0) || (year % 100 == 0) || (year % 400 == 0)) {
      leap = 1;
   }
   if ((month == 2) && (leap == 1) && (day > 29)) {
      err = 23;
   }
   if ((month == 2) && (leap != 1) && (day > 28)) {
      err = 24;
   }
   /* Validation of other months */
   if ((day > 31) && ((month == "01") || (month == "03") || (month == "05") || (month == "07") || (month == "08") || (month == "10") || (month == "12"))) {
      err = 25;
   }
   if ((day > 30) && ((month == "04") || (month == "06") || (month == "09") || (month == "11"))) {
      err = 26;
   }
   /* if ( 00 ist entered, no error, deleting the entry */
   if ((day == 0) && (month == 0) && (year == 00)) {
      err = 0; day = ""; month = ""; year = ""; seperator = "";
   }
   /* if ( no error, write the completed date to Input-Field (e.g. 13.12.2001) */
   if (err == 0) {
      DateField.value = day + seperator + month + seperator + year;
   /* Error-message if ( err != 0 */
   }else{
      alert("����� �� ����");
      DateField.select();
	  DateField.focus();
   }
}
// mendy fridman
function searchQueryVariable(variable) {
    var query = window.location.search.substring(1);
    var vars = query.split("&");
    for (var i=0;i<vars.length;i++) {
		var pair = vars[i].split("=");
		if (pair[0] == variable) {
			return pair[1];
		}
  } 
  alert('Query Variable ' + variable + ' not found');
}
function getQueryVariable(variable, loc) {
    var query = window.location.search.substring(1);
    var vars = query.split("&");
	var pair = vars[loc].split("=");
	if (pair[0] == variable) {
		return pair[1];
    }
	alert('Query Variable ' + variable + ' not found');
}



function WeeksPageRefreshOrCheat(param,formType,EnterDate,fTDprtmnt,fPresentCd,fEnterTime,fExitTime,fPresNumber,fNewPresNumber){
	if(param == 1){
		if(formType == "N"){
			var table = window.opener.document.getElementById(EnterDate);
			var row = table.insertRow(fPresNumber-1);
			// Insert new cells (<td> elements) at the 1st and 2nd position of the "new" <tr> element:
			var cell0 = row.insertCell(0);
			var cell1 = row.insertCell(1);
			var cell2 = row.insertCell(2);
			var cell3 = row.insertCell(3);
			var cell4 = row.insertCell(4);
			var cell5 = row.insertCell(5);
			var cell6 = row.insertCell(6);
			// Add some text to the new cells:
			cell0.innerHTML = fTDprtmnt;
			cell1.innerHTML = fPresentCd;
			cell2.innerHTML = "";
			cell3.innerHTML = fExitTime;
			cell4.innerHTML = fEnterTime;
			cell5.innerHTML = "<input type=button value='�����' disabled style='cursor: pointer;'>";
			cell6.innerHTML = "<input type=button value='���' disabled style='cursor: pointer;'>";
		}else if (formType == "U"){
			var table = window.opener.document.getElementById(EnterDate).rows;
			var row = table[fPresNumber-1].cells;
		    row[0].innerHTML = fTDprtmnt;
			row[1].innerHTML = fPresentCd;
			row[2].innerHTML = "";
			row[3].innerHTML = fExitTime;
			row[4].innerHTML = fEnterTime;
		}else{
			window.opener.document.getElementById(EnterDate).deleteRow(fNewPresNumber-1);
		}
		window.close();
	}
	else{
		window.opener.location.reload();
		window.close();
	}
}
