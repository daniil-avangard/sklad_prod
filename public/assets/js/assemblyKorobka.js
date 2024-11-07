let buttons = document.querySelectorAll('.delete-korobka');
buttons.forEach((item, index) => {
    let parent = document.querySelectorAll('.assembly-korobka-row')[index];
    item.onclick = () => {parent.remove();}
});

const createKorobkaElement = () => {
    let initKorobkaList = document.querySelectorAll('.assembly-korobka-row');
    let parentKorobkaNode = initKorobkaList[0].parentNode;
    let counter = initKorobkaList.length + 1;
    let clone = initKorobkaList[0].cloneNode(true);
    clone.classList.remove("korobka-item-none");
    clone.classList.add("korobka-item-show");
    let textElement = clone.getElementsByTagName("TR")[0].firstChild;
    
    clone.getElementsByTagName("TR")[0].firstElementChild.innerHTML = "Коробка " + counter;
    clone.getElementsByTagName("INPUT")[0].value = "";
    clone.querySelectorAll('.delete-korobka')[0].onclick = () => {deleteKorobka(clone);};
    
    parentKorobkaNode.insertBefore(clone, parentKorobkaNode.lastChild.previousElementSibling);
}

document.getElementById("korobka-add").onclick = createKorobkaElement;

document.getElementById("start-assembl").onclick = () => {
    console.log(document.getElementById("start-assembl").dataset.korobkaflag);
    if (document.getElementById("start-assembl").dataset.korobkaflag == "no") {
        createKorobkaElement();
        document.getElementById("korobka-add-wrap").classList.remove("korobka-item-none");
        document.getElementById("korobka-add-wrap").classList.add("korobka-item-show");
    }
}

const deleteKorobka = (parent) => {
    parent.remove();
}

