function deleteDashItem(dashId) {
    var form = $('<form action="index.php" method="post">' +
        '<input type="text" name="dashId" value="' + dashId + '" />' +
        '</form>');
    $('body').append(form);
    form.submit();
}
document.getElementById("overlay").style.display = "block";

function overlayOff() {
    document.getElementById("overlay").style.display = "none";
}