var xmlHttp;
document.write("<script language=javascript src='js/jquery-3.2.1.js'></script>");
document.write("<script language=javascript src='js/jquery-3.2.1.min.js'></script>");
document.write("<script language=javascript src='js/bootstrap.js'></script>");
var posturl1 = "api/ticketlist.php";
var posturl2 = "api/buytickets.php";



//实例化HTTP连接对象
function createXmlHttpRequest() {
    if (window.XMLHttpRequest) {
        xmlHttp = new XMLHttpRequest();
    } else if (window.ActiveXObject) {
        xmlHttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
}

function getTicketList() {
    createXmlHttpRequest();
    xmlHttp.open("POST", posturl1, true);   
    xmlHttp.onreadystatechange = ticketlist;
    xmlHttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    var ticket_num = document.getElementById("ticket_num").value;
    if(ticket_num=="")
    {
        ticket_num=1;
    }
    xmlHttp.send("action=getticketlist&ticket_num="+ticket_num);
}

function search_ticket() {
    createXmlHttpRequest();
    xmlHttp.open("POST", posturl1, true);   
    xmlHttp.onreadystatechange = ticketlist;
    xmlHttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    var ticket_num = document.getElementById("ticket_num").value;
    if(ticket_num=="")
    {
        ticket_num=1;
    }
    xmlHttp.send("action=getticketlist&ticket_num="+ticket_num);
}

function getDist(event) {
    var dist = event.title;
    createXmlHttpRequest();
    xmlHttp.open("POST", posturl1, true);   
    xmlHttp.onreadystatechange = ticketlist;
    xmlHttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    var ticket_num = document.getElementById("ticket_num").value;
    if(ticket_num=="")
    {
        ticket_num=1;
    }
    xmlHttp.send("action=searchbyticket&ticket_num="+ticket_num+"&section="+dist);
}

function ticketlist() {
    if (xmlHttp.readyState == 4 && xmlHttp.status == 200) {
        var response = xmlHttp.responseText;
        var js = JSON.parse(response);
        var tablebody = document.getElementById("ticketlist");
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
            var td5 = tr.appendChild(document.createElement("td"));
            //var secid = "button_"+js[i]["sec_id"];
            td1.innerText = js[i]["sec_id"];
            td2.innerText = js[i]["remain_seats"];
            td3.innerText = js[i]["start_row"];
            td4.innerText = js[i]["price"];
            var a = td5.appendChild(document.createElement("button"));
            a.setAttribute("id", js[i]["sec_id"]);
            a.setAttribute("type", "button");
            a.setAttribute("class", "btn btn-success");
            a.innerText = "Buy";
            a.onclick = function () {
                BuyTickets(this.id);
            };
            //td5.innerHTML = '<input type="button" name="buy" id=btn value=buy onclick="BuyTickets(this.id);" />';
            
            tablebody.append(tr);
        }
       
    }
}

function BookResult()
{
    if (xmlHttp.readyState == 4 && xmlHttp.status == 200) 
    {
        var response = xmlHttp.responseText;
        var js = JSON.parse(response);
        if(js["permission"]==-1)
        {   
            alert("Please sign up or login first!");
            window.location.href = 'login.html';
        }
        else
        {
            $result = "tickets booked: ";
            for (var i = 0; i < js.length; i++) 
            {
                $result=$result+js[i]["sid"]+ " ;";
            }
            if (confirm($result))
            {
                search_ticket();
            }
        } 
    }
}

function BuyTickets(click_id)
{
    //alert("buytickets from section "+click_id);
    createXmlHttpRequest();
    xmlHttp.open("POST", posturl2, true);   
    xmlHttp.onreadystatechange = BookResult;
    xmlHttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    var ticket_num = document.getElementById("ticket_num").value;
    if(ticket_num=="")
    {
        ticket_num=1;
    }
    xmlHttp.send("action=buytickets&ticket_num="+ticket_num+"&sec_id="+click_id);
}




