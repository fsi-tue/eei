function saveMultiValue(elementName, localStorage) {
    const list = document.getElementsByName(elementName);

    if (list) {
        for (let i = 0; i < list.length; i++) {
            if (list[i].checked) {
                localStorage.setItem(elementName, list[i].value);
            }
        }
    }
}

function saveFormValues() {
    const ls = localStorage;
    saveMultiValue("studiengang", ls);
    saveMultiValue("abschluss", ls);
    saveMultiValue("semester", ls);
    saveMultiValue("essen", ls);

    ls.setItem("mail", document.getElementById("form-mail").value);
    ls.setItem("name", document.getElementById("form-name").value);
}

function popMultiValue(elementName, localStorage) {
    const item = localStorage.getItem(elementName);

    // localStorage does not contain the element
    if (!item) {
        return;
    }

    const list = document.getElementsByName(elementName);
    for (let i = 0; i < list.length; i++) {
        if (list[i].value === item) {
            list[i].checked = true;
        }
    }
}

function popFormValues() {
    const ls = localStorage;

    popMultiValue("studiengang", ls);
    popMultiValue("abschluss", ls);
    popMultiValue("semester", ls);
    popMultiValue("essen", ls);

    document.getElementById("form-mail").value = ls.getItem("mail");
    document.getElementById("form-name").value = ls.getItem("name");
}

popFormValues();
