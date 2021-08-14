function saveFormValues(){
    ls = localStorage;

    studiengang = document.getElementsByName("studiengang");

    for(i = 0; i < studiengang.length; i++){
        if(studiengang[i].checked)
           ls.setItem("studiengang", studiengang[i].value);
    }

    abschluss = document.getElementsByName("abschluss");

    for(i = 0; i < abschluss.length; i++){
        if(abschluss[i].checked)
           ls.setItem("abschluss", abschluss[i].value);
    }

    semester = document.getElementsByName("semester");

    for(i = 0; i < semester.length; i++){
        if(semester[i].checked)
           ls.setItem("semester", semester[i].value);
    }

    ls.setItem("mail",document.getElementById("form-mail").value);
    ls.setItem("name",document.getElementById("form-name").value);
    ls.setItem("phone",document.getElementById("form-phone").value);
}

function popFormValues(){
    ls = localStorage;

    studiengang = document.getElementsByName("studiengang");

    for(i = 0; i < studiengang.length; i++){
        if(studiengang[i].value == ls.getItem("studiengang"))
           studiengang[i].checked = true;
    }

    abschluss = document.getElementsByName("abschluss");

    for(i = 0; i < abschluss.length; i++){
        if(abschluss[i].value == ls.getItem("abschluss"))
           abschluss[i].checked = true;
    }

    semester = document.getElementsByName("semester");

    for(i = 0; i < semester.length; i++){
        if(semester[i].value == ls.getItem("semester"))
           semester[i].checked = true;
    }

    document.getElementById("form-mail").value = ls.getItem("mail");
    document.getElementById("form-name").value = ls.getItem("name");
    document.getElementById("form-phone").value = ls.getItem("phone");
}

popFormValues();