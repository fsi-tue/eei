function saveFormValuesSommerfest(){
    ls = localStorage;

    essen = document.getElementsByName("essen");

    for(i = 0; i < essen.length; i++){
        if(essen[i].checked)
           ls.setItem("essen", essen[i].value);
    }

    ls.setItem("mail",document.getElementById("form-mail").value);
    ls.setItem("name",document.getElementById("form-name").value);
    ls.setItem("phone",document.getElementById("form-phone").value);
}

function popFormValuesSommerfest(){
    ls = localStorage;

    essen = document.getElementsByName("essen");

    for(i = 0; i < essen.length; i++){
        if(essen[i].value == ls.getItem("essen"))
           essen[i].checked = true;
    }

    document.getElementById("form-mail").value = ls.getItem("mail");
    document.getElementById("form-name").value = ls.getItem("name");
    document.getElementById("form-phone").value = ls.getItem("phone");
}

popFormValuesSommerfest();
