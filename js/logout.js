//定义HTTP连接对象
var xmlHttp;

//实例化HTTP连接对象
function createXmlHttpRequest() {
    if (window.XMLHttpRequest) {
        xmlHttp = new XMLHttpRequest();
    } else if (window.ActiveXObject) {
        xmlHttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
}


//发起登录请求
function logout() {
    createXmlHttpRequest();
    
    var url = "api/logout.php";
    xmlHttp.open("POST", url, true);
    xmlHttp.onreadystatechange = handleResult;
    xmlHttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xmlHttp.send("action=logout");
}


function handleResult() {
    if (xmlHttp.readyState == 4 && xmlHttp.status == 200) 
    {
        var response = xmlHttp.responseText;
        var js = JSON.parse(response);
        alert(js["logoutresult"]);
        window.location.href = 'ticketlist.html';
    }
}