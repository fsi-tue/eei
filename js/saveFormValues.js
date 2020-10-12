
function saveFormValues(){
    ls = localStorage;
    ls.setItem("mail",document.getElementById("form-mail").value);
    ls.setItem("name",document.getElementById("form-name").value);
    ls.setItem("phone",document.getElementById("form-phone").value);
}

function popFormValues(){
    ls = localStorage;
    document.getElementById("form-mail").value = ls.getItem("mail");
    document.getElementById("form-name").value = ls.getItem("name");
    document.getElementById("form-phone").value= ls.getItem("phone");
}

popFormValues();