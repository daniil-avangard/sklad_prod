//let data = window.receiptdata;
console.log(window);
window.addEventListener("message", (event) => {
    console.log(event.data);
});