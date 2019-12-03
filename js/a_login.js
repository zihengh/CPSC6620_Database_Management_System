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
function login() {
    createXmlHttpRequest();
    var name = document.getElementById("username").value;
    var password = document.getElementById("password").value;
    if (name == null || name == "") {
        innerHtml("Please input username");
        return;
    }
    if (password == null || password == "") {
        innerHtml("Please input password");
        return;
    }
    var url = "api/a_login.php";
    xmlHttp.open("POST", url, true);
    xmlHttp.onreadystatechange = handleResult;
    xmlHttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xmlHttp.send("action=alogin&name=" + name + "&password=" + password);
}

//处理服务器返回的结果/更新页面
function handleResult() {
    if (xmlHttp.readyState == 4 && xmlHttp.status == 200) {
        var response = xmlHttp.responseText;
        var json = JSON.parse(response);
        if (json['permission']>0) 
        {
            alert("Log In Success！");
            //document.cookie = "logintoken=" + json['login_token'];

            //页面跳转
            if (json['permission'] != null && json['permission']==2) {
                window.location.href = 'admin.html';
            }
            else {
                //alert("switching to ticketlist.html!");
                window.location.href = 'manager.html';
            }
        } 
        else 
        {
            innerHtml("username/password error!");
        }
    }
}


//插入提示语
function innerHtml(message) {
    document.getElementById("tip").innerHTML = "<span style='font-size:15px; color:red;'>" + message + "</span>";
}
