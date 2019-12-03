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
    var url = "api/login.php";
    xmlHttp.open("POST", url, true);
    xmlHttp.onreadystatechange = handleResult;
    xmlHttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xmlHttp.send("action=login&name=" + name + "&password=" + password);
}

//处理服务器返回的结果/更新页面
function handleResult() {
    if (xmlHttp.readyState == 4 && xmlHttp.status == 200) {
        var response = xmlHttp.responseText;
        var json = JSON.parse(response);
        if (json['login_result']) 
        {
            alert("Log In Success！");
            document.cookie = "logintoken=" + json['login_token'];

            //页面跳转
            if (json['permission'] != null && json['permission'].indexOf("admin") !== -1) {
                window.location.href = 'admin.html';
            }
            else {
                //alert("switching to ticketlist.html!");
                window.location.href = 'ticketlist.html';
            }
        } 
        else 
        {
            innerHtml("username/password error!");
        }
    }
}


function TurnToLogin()
{
    window.location.href='login.html';
}

function TurnToSignUp()
{
    window.location.href='signup.html';
}

//发起登记请求
function signup() {
    createXmlHttpRequest();
    var name = document.getElementById("username").value;
    var password = document.getElementById("password").value;
    var repassword = document.getElementById("password1").value;
    //var firstname = document.getElementById("firstname").value;
    //var lastname = document.getElementById("lastname").value;
    var email = document.getElementById("email").value;
    var myreg = /^([a-zA-Z0-9]+[_|\_|\.]?)*[a-zA-Z0-9]+@([a-zA-Z0-9]+[_|\_|\.]?)*[a-zA-Z0-9]+\.[a-zA-Z]{2,3}$/;
    if (name == null || name == "") {
        innerHtml("Please input username");
        return;
    }
    if (password == null || password == "") {
        innerHtml("Please input password");
        return;
    }
    if (repassword == null || repassword == "") {
        innerHtml("Please repeat password");
        return;
    }
    if (repassword != password) {
        innerHtml("You entered two different passwords. Please try again.");
        return;
    }
    /*if (firstname == null || firstname == "") {
        innerHtml("Please input firstname");
        return;
    }
    if (lastname == null || lastname == "") {
        innerHtml("Please input lastname");
        return;
    }*/
    if (email == null || email == "") {
        innerHtml("Please input email");
        return;
    }
    if (!myreg.test(email)) {
        innerHtml("Mailbox format error, please re-enter");
        return;
    }

    var url = "api/signup.php";
    xmlHttp.open("POST", url, true);
    xmlHttp.onreadystatechange = handleResultSign;
    xmlHttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xmlHttp.send("action=signup&name=" + name + "&password=" + password + "&email=" + email);
}

function handleResultSign() {
    if (xmlHttp.readyState == 4 && xmlHttp.status == 200) {
        var response = xmlHttp.responseText;
        var json = JSON.parse(response);
        if (json["signup_result"].length < 2) {
            alert("Sign up Success！");
            document.cookie = "logintoken=" + json['login_token'];
            //页面跳转
            setTimeout("window.location.href='ticketlist.html'", 1000);
        } else {
            innerHtml(json['signup_result']);
        }
    }
}

//插入提示语
function innerHtml(message) {
    document.getElementById("tip").innerHTML = "<span style='font-size:15px; color:red;'>" + message + "</span>";
}

