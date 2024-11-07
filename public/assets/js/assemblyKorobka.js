document.getElementById("korobka-add").onclick = () => {
    let initKorobkaList = document.querySelectorAll('.assembly-korobka-row');
    let parentKorobkaNode = initKorobkaList[0].parentNode;
    let clone = initKorobkaList[0].cloneNode(true);
    clone.classList.remove("korobka-item-none");
    clone.classList.add("korobka-item-show");
    parentKorobkaNode.insertBefore(clone, parentKorobkaNode.lastChild.previousElementSibling);
}

document.getElementById("start-assembl").onclick = () => {
    console.log(document.getElementById("start-assembl").dataset.korobkaflag);
    if (document.getElementById("start-assembl").dataset.korobkaflag == "no") {
        console.log("Здесь");
        document.getElementById("korobka-block-item").classList.remove("korobka-item-none");
        document.getElementById("korobka-block-item").classList.add("korobka-item-show");
        document.getElementById("korobka-add-wrap").classList.remove("korobka-item-none");
        document.getElementById("korobka-add-wrap").classList.add("korobka-item-show");
    } else {
//        document.getElementById("korobka-add-wrap").classList.remove("korobka-item-none");
//        document.getElementById("korobka-add-wrap").classList.add("korobka-item-show");
    }
}
