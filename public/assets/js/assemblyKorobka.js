document.getElementById("korobka-add").onclick = () => {
    let initKorobkaList = document.querySelectorAll('.assembly-korobka-row');
    let parentKorobkaNode = initKorobkaList[0].parentNode;
    let clone = initKorobkaList[0].cloneNode(true);
    parentKorobkaNode.insertBefore(clone, parentKorobkaNode.lastChild.previousElementSibling);
}
