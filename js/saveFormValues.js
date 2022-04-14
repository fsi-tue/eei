function saveFormValues(){
    ls = localStorage;

    studiengang = document.getElementsByName("studiengang");
    
    if(studiengang){
        for(i = 0; i < studiengang.length; i++){
            if(studiengang[i].checked)
               ls.setItem("studiengang", studiengang[i].value);
        }
    }
    abschluss = document.getElementsByName("abschluss");

    if(abschluss){
        for(i = 0; i < abschluss.length; i++){
            if(abschluss[i].checked)
               ls.setItem("abschluss", abschluss[i].value);
        }
    }

    semester = document.getElementsByName("semester");

    if(semester){
        for(i = 0; i < semester.length; i++){
            if(semester[i].checked)
               ls.setItem("semester", semester[i].value);
        }
    }
    essen = document.getElementsByName("essen");

    if(essen){
        for(i = 0; i < essen.length; i++){
            if(essen[i].checked)
                ls.setItem("essen", essen[i].value);
        }
    }

    ls.setItem("mail",document.getElementById("form-mail").value);
    ls.setItem("name",document.getElementById("form-name").value);
}

function popFormValues(){
    ls = localStorage;


    if(ls.getItem("studiengang")){
        for(i = 0; i < studiengang.length; i++){
            if(studiengang[i].value == ls.getItem("studiengang"))
               studiengang[i].checked = true;
        }
    }

    abschluss = document.getElementsByName("abschluss");

    if(abschluss){
        for(i = 0; i < abschluss.length; i++){
            if(abschluss[i].value == ls.getItem("abschluss"))
               abschluss[i].checked = true;
        }
    }

    semester = document.getElementsByName("semester");

    if(semester){
        for(i = 0; i < semester.length; i++){
            if(semester[i].value == ls.getItem("semester"))
               semester[i].checked = true;
        }
    }

    document.getElementById("form-mail").value = ls.getItem("mail");
    document.getElementById("form-name").value = ls.getItem("name");
}

popFormValues();
