/*
document.getElementById("name").value = getSavedValue("name");
document.getElementById("title").value = getSavedValue("title");
document.getElementById("content").value = getSavedValue("content");
document.getElementById("photo1").value = getSavedValue("photo1");
document.getElementById("photo2").value = getSavedValue("photo2");
document.getElementById("photo3").value = getSavedValue("photo3");
const boxes = document.querySelectorAll("input[type='checkbox']");
//save inputs to localstorage
function saveValue(e) {
    var id = e.id;
    var val = e.value;
    localStorage.setItem(id, val);
}

function getSavedValue(v) {
    if (!localStorage.getItem(v)) {
        return "";
    }
    return localStorage.getItem(v);
}

//save checkbox in localstorage
for (var i = 0; i < boxes.length; i++) {
    var box = boxes[i];
    if (box.hasAttribute("store")) {
        setupBox(box);
    }
}

function setupBox(box) {
    var storageId = box.getAttribute("store");
    var oldVal = localStorage.getItem(storageId);
    box.checked = oldVal === "true" ? true : false;

    box.addEventListener("change", function () {
        localStorage.setItem(storageId, this.checked);
    });
}
*/