var xmlHttp;
document.write("<script language=javascript src='js/jquery-3.2.1.js'></script>");
document.write("<script language=javascript src='js/jquery-3.2.1.min.js'></script>");
document.write("<script language=javascript src='js/bootstrap.js'></script>");
var posturl1 = "api/manager.php";
var posturl2 = "api/dbbackup.php";



//实例化HTTP连接对象
function createXmlHttpRequest() {
    if (window.XMLHttpRequest) {
        xmlHttp = new XMLHttpRequest();
    } else if (window.ActiveXObject) {
        xmlHttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
}

function getdatabaseinfo() {
    createXmlHttpRequest();
    xmlHttp.open("POST", posturl1, true);   
    xmlHttp.onreadystatechange = infolist;
    xmlHttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    //var ticket_num = document.getElementById("ticket_num").value|1;
    xmlHttp.send("action=getdatabaseinfo");
}



function infolist() {
    if (xmlHttp.readyState == 4 && xmlHttp.status == 200) {
        var response = xmlHttp.responseText;
        var js = JSON.parse(response);
        if(js["permission"]<0)
        {
            alert("Please login first!");
            window.location.href = 'manager_login.html';
        }
        else
        {
            var response = xmlHttp.responseText;
            var js = JSON.parse(response);
            var tablebody = document.getElementById("secprice");
            //tablebody.innerText = "";
            while(tablebody.hasChildNodes())
            {
                tablebody.removeChild(tablebody.firstChild);
            }
            for (var i = 0; i < js.length; i++) 
            {
                var tr = document.createElement("tr");
                var td1 = tr.appendChild(document.createElement("td"));
                var td2 = tr.appendChild(document.createElement("td"));
                var td3 = tr.appendChild(document.createElement("td"));
                var td4 = tr.appendChild(document.createElement("td"));
                //var secid = "button_"+js[i]["sec_id"];
                td1.innerText = js[i]["sec_id"];
                td2.innerText = js[i]["remain_seats"];
                td3.innerText = js[i]["start_row"];
                var a = td4.appendChild(document.createElement("input"));
                a.setAttribute("id", js[i]["sec_id"]);
                a.setAttribute("type", "number");
                a.setAttribute("value", js[i]["price"]);            
                tablebody.append(tr);
            }
        }
       
    }
}
/*
function gettableinfo() {
    createXmlHttpRequest();
    xmlHttp.open("POST", posturl1, true);   
    xmlHttp.onreadystatechange = tablelist;
    xmlHttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    //var ticket_num = document.getElementById("ticket_num").value|1;
    xmlHttp.send("action=gettableinfo");
}

function tablelist() {
    if (xmlHttp.readyState == 4 && xmlHttp.status == 200) {
        var response = xmlHttp.responseText;
        var js = JSON.parse(response);
        if(js["permission"]<0)
        {
            alert("Please login first!");
            window.location.href = 'manager_login.html';
        }
        else
        {
            var response = xmlHttp.responseText;
            var js = JSON.parse(response);

            var table2body = document.getElementById("tablelist");
            //tablebody.innerText = "";
            while(table2body.hasChildNodes())
            {
                table2body.removeChild(table2body.firstChild);
            }
            for (var i = 0; i < js.length; i++) 
            {
                var tr = document.createElement("tr");
                var td1 = tr.appendChild(document.createElement("td"));
                var td2 = tr.appendChild(document.createElement("td"));
                var td3 = tr.appendChild(document.createElement("td"));
                //var secid = "button_"+js[i]["sec_id"];
                td1.innerText = js[i]["Tables_in_Ticket_management"];
                var b = td2.appendChild(document.createElement("button"));  
                b.setAttribute("id", js[i]["Tables_in_Ticket_management"]);
                b.setAttribute("type", "button");
                b.setAttribute("class", "btn btn-success");
                b.innerText = "BackUp";
                var c = td3.appendChild(document.createElement("button"));  
                c.setAttribute("id", js[i]["Tables_in_Ticket_management"]);
                c.setAttribute("type", "button");
                c.setAttribute("class", "btn btn-success");
                c.innerText = "Restore";
                b.onclick = function () {
                    BackUp(this.id);
                };
                c.onclick = function () {
                    Recover(this.id);
                };

                table2body.append(tr);
            }
        }
       
    }
}*/

function SaveResult()
{
    if (xmlHttp.readyState == 4 && xmlHttp.status == 200) 
    {
        var response = xmlHttp.responseText;
        var js = JSON.parse(response);
        //$result = "Price Saves!";
        if(js["permission"]<1)
        {
            alert("Please login first!");
            window.location.href = 'manager_login.html';
        }
        else if(confirm("price saved"))
        {
            getdatabaseinfo();
        }
        
    }
}

function save()
{
    //alert("buytickets from section "+click_id);
    createXmlHttpRequest();
    xmlHttp.open("POST", posturl1, true);   
    xmlHttp.onreadystatechange = SaveResult;
    xmlHttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    
    $updateinfo = "";
    var table = document.getElementById("secprice");
    for (var i = 0; i < document.getElementById("secprice").rows.length; i++) 
    {
    
        $secid=table.rows[i].cells.item(0).innerText;
        $price = document.getElementById($secid).value;
        //$updateinfo = $updateinfo+table.rows[i].cells.item(0).innerText+":"+sprintf("%f",table.rows[i].cells.item(3).value)+";";
        $updateinfo = $updateinfo+$secid+":"+$price+";";
    }
    alert($updateinfo);
    xmlHttp.send("action=Save&updateinfo="+$updateinfo);
    //getTicketList();
}

function BackUpResult()
{
    if (xmlHttp.readyState == 4 && xmlHttp.status == 200) 
    {
        /*var response = xmlHttp.responseText;
        var js = JSON.parse(response);
        //$result = "Price Saves!";
        if(js["result"])
        {
            alert("Back Up Success!");
        }
        else 
        {
            alert("Back Up Failed!");
        }*/
        
    }
}

function BackUp()
{
    //alert("buytickets from section "+click_id);
    createXmlHttpRequest();
    xmlHttp.open("POST", "api/dbbackup.php", true);   
    xmlHttp.onreadystatechange = BackUpResult;
    xmlHttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xmlHttp.send("action=backuptable");
    //getTicketList();
}


function RecoverResult()
{
    if (xmlHttp.readyState == 4 && xmlHttp.status == 200) 
    {
        /*var response = xmlHttp.responseText;
        var js = JSON.parse(response);*/
        //$result = "Price Saves!";
        
        
    }
}

function Recover()
{
    //alert("buytickets from section "+click_id);
    createXmlHttpRequest();
    xmlHttp.open("POST", posturl2, true);   
    xmlHttp.onreadystatechange = RecoverResult;
    xmlHttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    alert("restore");
    //alert($updateinfo);
    xmlHttp.send("action=restoretable");
    //getTicketList();
}