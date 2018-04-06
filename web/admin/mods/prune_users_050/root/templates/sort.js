function checkAll(theForm, cName, allNo_stat)
{
	var n=theForm.elements.length;
	for (var i=0;i<n;i++)
	{
		if (theForm.elements[i].className.indexOf(cName) !=-1)
		{
			if (allNo_stat.checked)
			{
				theForm.elements[i].checked = true;
			}
			else 
			{
				theForm.elements[i].checked = false;
			}
		}
	}
}

// credits to http://www.kryogenix.org/code/browser/sorttable/	

addEvent(window, "load", sortables_init);
var SORT_COLUMN_INDEX;

function sortables_init() {
    // Find all tables with class sortable and make them sortable
    if (!document.getElementById) return;
    thisTbl = document.getElementById("table");
	ts_makeSortable(thisTbl);
}

function ts_getInnerText(el) {
	if (typeof el == "string") return el;
	if (typeof el == "undefined") { return el };
	if (el.innerText) return el.innerText;	//Not needed but it is faster
	var str = "";
	
	var cs = el.childNodes;
	var l = cs.length;
	for (var i = 0; i < l; i++) {
		switch (cs[i].nodeType) {
			case 1: //ELEMENT_NODE
				str += ts_getInnerText(cs[i]);
				break;
			case 3:	//TEXT_NODE
				str += cs[i].nodeValue;
				break;
		}
	}
	return str;
}

function ts_resortTable(lnk,clid) {
    // get the span
    var span = document.getElementById('arrow'+clid);

    var spantext = ts_getInnerText(span);
    var td = lnk.parentNode;
    var column = clid || td.cellIndex;
    var table = getParent(td,'TABLE');
    
    // Work out a type for the column
    if (table.rows.length <= 1) return;
    var itm = ts_getInnerText(table.rows[1].cells[column]);
    sortfn = ts_sort_caseinsensitive;
    if (itm.match(/^[\d\.]+$/)) sortfn = ts_sort_numeric;
	// added by kkroo too
	var cell_id = table.rows[1].cells[clid].id;
	if (cell_id && cell_id.match(/^[\d\.]+$/) ) sortfn = ts_sort_date_raw;
	if (cell_id && itm.match("href=") ) sortfn = ts_sort_url;
    SORT_COLUMN_INDEX = column;
    var firstRow = new Array();
    var newRows = new Array();
    for (i=0;i<table.rows[0].length;i++) { firstRow[i] = table.rows[0][i]; }
    for (j=1;j<table.rows.length;j++) { if ( j != ( table.rows.length - 1 ) ) { newRows[j-1] = table.rows[j]; } }

	//added by kkroo, dont laugh, im a novice in javascript
	// get class names
	var row1 = newRows[0].cells[1].className;
	var row2 = newRows[1].cells[1].className;

    newRows.sort(sortfn);

	
    if (span.getAttribute("sortdir") == 'down') {
        ARROW = '&nbsp;&uArr;';
        newRows.reverse();
        span.setAttribute('sortdir','up');
    } else {
        ARROW = '&nbsp;&dArr;';
        span.setAttribute('sortdir','down');
    }
    
    // We appendChild rows that already exist to the tbody, so it moves them rather than creating new ones
    // don't do sortbottom rows
    for (i=0;i<newRows.length;i++) { if (!newRows[i].className || (newRows[i].className && (newRows[i].className.indexOf('sortbottom') == -1))) table.tBodies[0].appendChild(newRows[i]);}
    // do sortbottom rows only
    for (i=0;i<newRows.length;i++) { if (newRows[i].className && (newRows[i].className.indexOf('sortbottom') != -1)) table.tBodies[0].appendChild(newRows[i]);}
    
    // Delete any other arrows there may be showing
    var allspans = document.getElementsByTagName("span");
    for (var ci=0;ci<allspans.length;ci++) {
        if (allspans[ci].className == 'sortarrow') {
            if (getParent(allspans[ci],"table") == getParent(lnk,"table")) { // in the same table as us?
                allspans[ci].innerHTML = '';
            }
        }
    }
        
    span.innerHTML = ARROW;
	//added by kkroo, dont laugh, im a novice in javascript
	
	for (k=0;k<newRows.length;k++)
	{ 
		for (l=0;l<newRows[k].cells.length;l++)
		{
			if( !(k % 2) ) 
			{
			   newRows[k].cells[l].className  = row1;
			}
			else
			{
			   newRows[k].cells[l].className  = row2;
			}   
		}
	}
}

function getParent(el, pTagName) {
	if (el == null) return null;
	else if (el.nodeType == 1 && el.tagName.toLowerCase() == pTagName.toLowerCase())	// Gecko bug, supposed to be uppercase
		return el;
	else
		return getParent(el.parentNode, pTagName);
}

// added by kkroo too
function ts_sort_date_raw(a,b) { 
    aa = parseFloat(a.cells[SORT_COLUMN_INDEX].id);
    if (isNaN(aa)) aa = 0;
    bb = parseFloat(b.cells[SORT_COLUMN_INDEX].id); 
    if (isNaN(bb)) bb = 0;
    return aa-bb;
}

function ts_sort_numeric(a,b) { 
    aa = parseFloat(ts_getInnerText(a.cells[SORT_COLUMN_INDEX]));
    if (isNaN(aa)) aa = 0;
    bb = parseFloat(ts_getInnerText(b.cells[SORT_COLUMN_INDEX])); 
    if (isNaN(bb)) bb = 0;
    return aa-bb;
}

function ts_sort_caseinsensitive(a,b) {
    aa = ts_getInnerText(a.cells[SORT_COLUMN_INDEX]).toLowerCase();
    bb = ts_getInnerText(b.cells[SORT_COLUMN_INDEX]).toLowerCase();
    if (aa==bb) return 0;
    if (aa<bb) return -1;
    return 1;
}

function ts_sort_url(a,b) {
    aa = ts_getInnerText(a.cells[SORT_COLUMN_INDEX].id).toLowerCase();
    bb = ts_getInnerText(b.cells[SORT_COLUMN_INDEX].id).toLowerCase();
    if (aa==bb) return 0;
    if (aa<bb) return -1;
    return 1;
}

function ts_sort_default(a,b) {
    aa = ts_getInnerText(a.cells[SORT_COLUMN_INDEX]);
    bb = ts_getInnerText(b.cells[SORT_COLUMN_INDEX]);
    if (aa==bb) return 0;
    if (aa<bb) return -1;
    return 1;
}


function addEvent(elm, evType, fn, useCapture)
// addEvent and removeEvent
// cross-browser event handling for IE5+,  NS6 and Mozilla
// By Scott Andrew
{
  if (elm.addEventListener){
    elm.addEventListener(evType, fn, useCapture);
    return true;
  } else if (elm.attachEvent){
    var r = elm.attachEvent("on"+evType, fn);
    return r;
  } else {
    alert("Handler could not be removed");
  }
} 
