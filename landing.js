var input = document.getElementById("myInput");
function AddLi(str)
{
    var li = document.createElement('li');
    li.appendChild(document.createTextNode(str))
    li.innerHTML += ' <button class="remove" onclick="this.parentNode.remove()">X</button>';
    document.getElementById("thing-list").appendChild(li);
    input.value = " ";
}

input.addEventListener("keypress", function(event) {

if (event.key === "Enter") {
    event.preventDefault();
    document.getElementById("myBtn").click();
}
});