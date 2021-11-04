
/* trafficlights/assets/js/INDEX.JS 1.0 (2018/02/19) */

$(function () {

})

function showCondition(itemID, itemSignature) {

    setInterval("$('#s" + itemID + "').load('index.php?x=" + itemSignature + "');", 8000);

    // TO DO
    // d = new date(); +d.getTime()

}