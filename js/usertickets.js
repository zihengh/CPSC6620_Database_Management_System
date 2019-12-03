var xmlHttp;
document.write("<script language=javascript src='js/jquery-3.2.1.js'></script>");
document.write("<script language=javascript src='js/jquery-3.2.1.min.js'></script>");
document.write("<script language=javascript src='js/bootstrap.js'></script>");
var posturl = "api/usertickets.php";




//实例化HTTP连接对象
function createXmlHttpRequest() {
    if (window.XMLHttpRequest) {
        xmlHttp = new XMLHttpRequest();
    } else if (window.ActiveXObject) {
        xmlHttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
}

function getUserTickets() {
    createXmlHttpRequest();
    xmlHttp.open("POST", posturl, true);   
    xmlHttp.onreadystatechange = usrticketlist;
    xmlHttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xmlHttp.send("action=getUserTickets");
}



function usrticketlist() {
    if (xmlHttp.readyState == 4 && xmlHttp.status == 200) {
        var response = xmlHttp.responseText;
        var js = JSON.parse(response);
        var tablebody = document.getElementById("usertickets");
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
            td1.innerText = js[i]["sid"];
            td2.innerText = js[i]["price"];
            var a = td3.appendChild(document.createElement("button"));
            a.setAttribute("id", js[i]["sid"]);
            a.setAttribute("type", "button");
            a.setAttribute("class", "btn btn-success");
            a.innerText = "Refund";
            a.onclick = function () {
                Refund(this.id);
            };
            //td5.innerHTML = '<input type="button" name="buy" id=btn value=buy onclick="BuyTickets(this.id);" />';
            
            tablebody.append(tr);
        }
       
    }
}

function RefundResult()
{
    if (xmlHttp.readyState == 4 && xmlHttp.status == 200) 
    {
    
        if (confirm("Tickets deleted!"))
        {
            getUserTickets();
        }
        
    }
}

function Refund(click_id)
{
    createXmlHttpRequest();
    xmlHttp.open("POST", posturl, true);   
    xmlHttp.onreadystatechange = RefundResult;
    xmlHttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xmlHttp.send("action=refund&sid="+click_id);
}




