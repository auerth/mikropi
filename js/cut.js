$("#editTitle").click(function(event) {
    var modal = document.getElementById('modalTitle');
    var titleInput = document.getElementById('newTitle');
    var title = document.getElementById('title');

    titleInput.value = (title.innerHTML);

    var span = document.getElementsByClassName("close")[0];
    modal.style.display = "block";
    span.onclick = function() {
        modal.style.display = "none";
    }

    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }
});
$("#editDescription").click(function(event) {
    var modal = document.getElementById('modalDescription');
    var descriptionInput = document.getElementById('newDescription');
    var description = document.getElementById('description-text');

    descriptionInput.value = (description.innerHTML);

    var span = document.getElementsByClassName("close")[2];
    modal.style.display = "block";
    span.onclick = function() {
        modal.style.display = "none";
    }

    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }
});
$(".overlayAdder li").click(function(event) {
    var id = (event.target.id);
    var name = event.target.innerHTML;
    var textArea = document.getElementById("newDescription");
    insertAtCursor(textArea, '<a onclick="zoomOnOverlay(' + id + ')">' + name + '</a>');
});




function insertAtCursor(myField, myValue) {
    //IE support
    if (document.selection) {
        myField.focus();
        sel = document.selection.createRange();
        sel.text = myValue;
    }
    //MOZILLA and others
    else if (myField.selectionStart || myField.selectionStart == '0') {
        var startPos = myField.selectionStart;
        var endPos = myField.selectionEnd;
        myField.value = myField.value.substring(0, startPos) +
            myValue +
            myField.value.substring(endPos, myField.value.length);
    } else {
        myField.value += myValue;
    }
}




$("#disFilter").click(function(event) {
    $(".filter").toggle();

});

function putFilter(filterId, cutId, hash, checkBoxId) {
    var checkBox = document.getElementById('checkBox-' + checkBoxId);
    checkBox.checked = !checkBox.checked;
    var params = {
        'cutid': cutId,
        'filterid': filterId,
        'hash': hash

    };
    postNoRedirect("", params);
}



$("#zoom0").click(function(event) {
    viewer.viewport.zoomTo(0.4);
});
$("#zoom25").click(function(event) {
    viewer.viewport.zoomTo(1);
});

$("#zoom50").click(function(event) {
    viewer.viewport.zoomTo(4);
});

$("#zoom75").click(function(event) {
    viewer.viewport.zoomTo(8);
});
$("#zoom100").click(function(event) {
    viewer.viewport.zoomTo(20);
});


$(".editOverlayName").click(function(event) {
    var modal = document.getElementById('modalOverlay');
    var overlayId = document.getElementById('overlayId');
    overlayId.value = ($(this).closest('li').attr('id'));

    var span = document.getElementsByClassName("close")[1];
    modal.style.display = "block";
    span.onclick = function() {
        modal.style.display = "none";
    }

    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }
});


$("#editFilter").click(function(event) {
    var modal = document.getElementById('modalFilter');
    var overlayId = document.getElementById('overlayId');
    overlayId.value = ($(this).closest('li').attr('id'));

    var span = document.getElementsByClassName("close")[3];
    modal.style.display = "block";
    span.onclick = function() {
        modal.style.display = "none";
    }

    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }
});
var selected = null;
var selectedItem = null;
$(".overlays ul li").click(function(event) {
    if (selected != null && selectedItem != null) {
        selected.style.outline = "3px solid #3281D6";
        selectedItem.style.outline = "";

    }

    selected = document.getElementById("o-" + event.target.id);
    selectedItem = document.getElementById(event.target.id);
    if (selected != null) {
        selected.style.outline = "3px solid  #CC0000";
        selectedItem.style.outline = "2px solid  #CC0000";
        var overlay = viewer.getOverlayById("o-" + event.target.id);
        viewer.viewport.fitBounds(overlay.getBounds(viewer.viewport));
    }

});
$(".overlaysM ul li").click(function(event) {
    if (selected != null && selectedItem != null) {
        selected.style.outline = "3px solid #3281D6";
        selectedItem.style.outline = "";

    }

    selected = document.getElementById("o-" + event.target.id);
    selectedItem = document.getElementById(event.target.id);
    if (selected != null) {
        selected.style.outline = "3px solid  #CC0000";
        selectedItem.style.outline = "2px solid  #CC0000";
        var overlay = viewer.getOverlayById("o-" + event.target.id);
        viewer.viewport.fitBounds(overlay.getBounds(viewer.viewport));
    }

});


function zoomOnOverlay(id) {
    if (selected != null && selectedItem != null) {
        selected.style.outline = "3px solid #3281D6";
        selectedItem.style.outline = "";

    }
    selected = document.getElementById("o-" + id);
    selectedItem = document.getElementById(id);
    if (selected != null && selectedItem != null) {
        selected.style.outline = "3px solid  #CC0000";
        selectedItem.style.outline = "2px solid  #CC0000";
        var overlay = viewer.getOverlayById("o-" + id);
        var zoom = overlay.getBounds(viewer.viewport);
        viewer.viewport.fitBounds(zoom);
    }

}

$('#hide').click(
    function() {
        if ($('#description').css('display') == 'block' ||
            $('#overlay').css('display') == 'block') {
            $('#description').hide('slow');
            $('#itemDescription').hide('slow');
            $('#itemOverlay').hide('slow');
            $('#overlay').hide('slow');

            $("#hide").attr("src", "../images/right.png");
        } else {
            $('#description').show('slow');
            $('#itemDescription').show('slow');
            $('#itemOverlay').show('slow');
            $("#hide").attr("src", "../images/left.png");

        }
    });

$('#itemDescription').click(function() {
    if ($('#description').css('display') == 'none') {
        $('#description').show();
        $('#overlay').hide();

    } else {

    }
});
$('#itemDescriptionM').click(function() {
    $('.descriptionM').show();
    $('#overlayM').hide();
});
$('#itemOverlay').click(function() {
    if ($('#overlay').css('display') == 'none') {
        $('#description').hide();
        $('#overlay').show();
    }
});
$('#itemOverlayM').click(function() {
    $('.descriptionM').hide();
    $('#overlayM').show();
});
var viewportPoint;

var tracker = new OpenSeadragon.MouseTracker({
    element: viewer.container,
    moveHandler: function(event) {
        var webPoint = event.position;
        viewportPoint = viewer.viewport.pointFromPixel(webPoint);
        if (runtimeelement != null) {
            viewer.removeOverlay(runtimeelement);
            runtimeelement = document.createElement("div");
            runtimeelement.id = "runtime-overlay";
            runtimeelement.className = "runtime-overlay";
            runtimeelement.style.outline = "3px solid #3281D6";
            runtimeelement.style.opacity = "0.9";
            runtimeelement.textContent = "";
            viewer.addOverlay({
                element: runtimeelement,
                id: "runtime-overlay",
                location: new OpenSeadragon.Rect(point1.x, point1.y,
                    (viewportPoint.x - point1.x),
                    (viewportPoint.y - point1.y)),
                rotationMode: OpenSeadragon.OverlayRotationMode.BOUNDING_BOX
            });
        }

    }
});
tracker.setTracking(false);

function drawOverlay() {

    if (viewer.gestureSettingsMouse.clickToZoom) {
        viewer.gestureSettingsMouse.clickToZoom = false;
        tracker.setTracking(true);
        $('#addOverlay').addClass('btn-danger');
        $('#addOverlay').removeClass('btn-primary');

    } else {
        viewer.gestureSettingsMouse.clickToZoom = true;
        tracker.setTracking(false);
        $('#addOverlay').removeClass('btn-danger');
        $('#addOverlay').addClass('btn-primary');
        try {
            viewer.removeOverlay(runtimeelement);

        } catch (e) {

        }

    }
}

var point1 = 0;
var point2 = 0;

$("#openseadragon1").mousemove(function(event) {
    var msg = "Handler for .mousemove() called at ";
    msg += event.pageX + ", " + event.pageY;
    $("#log").append("<div>" + msg + "</div>");
});
var runtimeelement = null;


function deleteCut(id) {
    if (confirm('Sicher das du diesen Präperat löschen willst? (Das kann nicht rückgängig gemacht werden)')) {
        var params = {
            'cutId': id,
            'deleteCut': ""
        };
        post("index.php?cuts=" + id, params, "POST");
    } else {
        // Do nothing!
    }

}

function annos(id) {
    post('index.php?cuts=' + id, null, null);
}

function noAnnos(id) {
    post('index.php?cuts=' + id + '&noOverlay', null, null);
}
$("#openseadragon1")
    .click(
        function(event) {
            if (!viewer.gestureSettingsMouse.clickToZoom) {
                if (point1 == 0) {
                    point1 = viewportPoint;
                    if (point1 != 0 && runtimeelement == null) {
                        runtimeelement = document.createElement("div");
                        runtimeelement.id = "runtime-overlay";
                        runtimeelement.className = "runtime-overlay";
                        runtimeelement.style.outline = "3px solid #3281D6";
                        runtimeelement.style.opacity = "0.9";
                        runtimeelement.textContent = "";
                        viewer
                            .addOverlay({
                                element: runtimeelement,
                                id: "runtime-overlay",
                                location: new OpenSeadragon.Rect(
                                    point1.x,
                                    point1.y,
                                    (viewportPoint.x - point1.x),
                                    (viewportPoint.y - point1.y)),
                                rotationMode: OpenSeadragon.OverlayRotationMode.BOUNDING_BOX
                            });
                    }
                } else {
                    point2 = viewportPoint;
                    addOverlay(point1, point2);
                    point1 = 0;
                    point2 = 0;
                    viewer.gestureSettingsMouse.clickToZoom = true;
                    tracker.setTracking(false);

                }

            }

        });

function addOverlay(first, second) {
    var elt = document.createElement("div");
    elt.className = "fixed-overlay";
    elt.style.outline = "3px solid #3281D6";
    elt.style.opacity = "0.9";
    elt.textContent = "";
    viewer.addOverlay({
        element: elt,
        location: new OpenSeadragon.Rect(point1.x, point1.y,
            (point2.x - point1.x), (point2.y - point1.y)),
        rotationMode: OpenSeadragon.OverlayRotationMode.BOUNDING_BOX
    });

    var cookies = document.cookie;

    var params = {
        'location': point1.x + "," + point1.y,
        'size': (point2.x - point1.x) + "," + (point2.y - point1.y),
        'title': "NEW OVERLAY"
    };
    post("", params, "POST");

}

function deleteOverlay(id) {
    var params = {
        'overlayId': id,
    };
    post("", params, "POST");
}

function post(path, params, method) {
    method = method || "post"; // Set method to post by default if not
    // specified.

    // The rest of this code assumes you are not using a library.
    // It can be made less wordy if you use one.
    var form = document.createElement("form");
    form.setAttribute("method", method);
    form.setAttribute("action", path);

    for (var key in params) {
        if (params.hasOwnProperty(key)) {
            var hiddenField = document.createElement("input");
            hiddenField.setAttribute("type", "hidden");
            hiddenField.setAttribute("name", key);
            hiddenField.setAttribute("value", params[key]);

            form.appendChild(hiddenField);
        }
    }
    document.body.appendChild(form);
    form.submit();
}

function postNoRedirect(url, params) {
    $.ajax({
        type: "POST",
        url: url,
        data: params,
        success: function(msg) {
            console.log(msg);
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
            console.log(errorThrown);
        }

    });
}