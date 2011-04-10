$(document).ready(function() {
    $("#hidden").hide();
    $("#submitScore").click(function(){
	
        // maybe some validation in the future
        var name = $("#nameInput").val().trim();
        var type = $("#scoreTypeInput").val().trim();

        var lecture = $("#lectureNumber").val();
        var postObject = {
            "name" : name,
            "type" : type,
            "lecture" : lecture
        };
        console.log(postObject);

        $.post(Leaderboard.addScriptUrl,
            postObject,
            function(response) {
                console.log("Response from script : ", response);
                $("#nameInput").val("");
                $("#scoreTypeInput").val("");
                //$("#lectureNumber").val("");
                $("#hidden").show("slow").hide("slow");
            },
            "json"
            );
    });

    $("#submitHomework").click(function(){
        var names = $("#csvHomework").val().split(",").map(function(el) {
            return el.trim();
        });
        var homeworkNumber = $("#homeworkNumber").val();
        console.log(names);

        var postObject = {
            "names" : names,
            "type" : "homework",
            "lecture" : homeworkNumber
        };

        $.post(Leaderboard.addScriptUrl,
            postObject,
            function(response) {
                console.log("Response from script : ", response);
            },
            "json"
            );

    });
});
