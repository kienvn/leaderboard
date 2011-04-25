     Ext.BLANK_IMAGE_URL = 'javascript/extjs/resources/themes/images/default/tree/s.gif';
            Ext.require([
                'Ext.form.*',
                'Ext.data.*'
            ]);

            Ext.onReady(function(){
                /********
                 *MODELS*
                 ********/
                Ext.regModel('Students', {
                    fields : [
                        {name : "name", type : "string"},
                        {name : "id", type : "integer"}
                    ]
                });

                Ext.regModel('Types', {
                    fields : [
                        {name : "typeName", type : "string"}
                    ]
                });

                /********
                 *STORES*
                 ********/
                Leaderboard.typesStore = new Ext.create("Ext.data.Store", {
                    model : "Types",
                    data : [{typeName : "question"}, {typeName : "answer"}, {typeName : "homework"}]
                });

                /***********
                 *FUNCTIONS*
                 ***********/
                Leaderboard.ajaxCall = function(parameters, succsess_callback, failure_callback) {
                    Ext.Ajax.request({
                        url : Leaderboard.gateway,
                        success : succsess_callback,
                        failure : failure_callback,
                        params : parameters
                    });
                };

                /***********
                 *APP LOGIC*
                 ***********/
                var students = [];

                var loadSucc = function(response) {
                    students = Ext.decode(response.responseText);
                    console.log(students);
                    Leaderboard.studentsModel = new Ext.create("Ext.data.Store", {
                        model : "Students",
                        data : students
                    });
                    // cant deal with the fuckin stores for now ...
                    afterLoad();
                }

                Leaderboard.ajaxCall({method : "getAllStudents"}, loadSucc);

                var afterLoad = function() {
                    Leaderboard.addPointsForLecture = Ext.create('Ext.form.FormPanel', {
                        frame: true,
                        title: 'Points for lecture',
                        width: 350,
                        bodyPadding: 5,
                        renderTo : "panelsPlaceholder",
                        collapsible : true,
                        fieldDefaults: {
                            labelAlign: 'left',
                            labelWidth: 90,
                            anchor: '100%'
                        },

                        items: [{
                                id : 'autocompNames',
                                store : Leaderboard.studentsModel,
                                xtype: 'combo',
                                name: 'nameInput',
                                fieldLabel: 'Name',
                                queryMode: 'local',
                                displayField: 'name',
                                valueField : 'id'
                            },{
                                id : 'pointsInput',
                                xtype: 'numberfield',
                                name: 'pointsInput',
                                fieldLabel: 'Points',
                                value: 0.5,
                                step : 0.1,
                                minValue: 0,
                                maxValue: 50
                            }, {
                                id : 'typeInput',
                                xtype : 'combo',
                                fieldLabel : 'type',
                                store : Leaderboard.typesStore,
                                queryMode : 'local',
                                displayField : 'typeName',
                                valueField : 'typeName'
                            },
                            {
                                id : 'lectureInput',
                                xtype : 'numberfield',
                                name : 'lectureInput',
                                fieldLabel : 'Lecture',
                                value : 1,
                                step : 1,
                                minValue : 1,
                                maxValue : 20
                            }],
                        buttons : [
                            {
                                text : 'Add points',
                                handler : function() {
                                    var cb = Ext.getCmp("autocompNames");

                                    var studentName = cb.rawValue;
                                    var studentId = cb.value;

                                    /*mark as new student to the database*/
                                    if(studentId === null) {
                                        studentId = -1;
                                    }

                                    var points = Ext.getCmp("pointsInput").value;
                                    var type = Ext.getCmp("typeInput").value;
                                    var lecture = Ext.getCmp("lectureInput").value;

                                    var params = {
                                        "method" : "addPoints",
                                        "studentName" : studentName,
                                        "studentId" : studentId,
                                        "points" : points,
                                        "lecture" : lecture,
                                        "type" : type
                                    };

                                    console.log(params);
                                    Leaderboard.ajaxCall(params, function(resp) {
                                        //console.log("response from adding : ", resp);
                                        var respObj = Ext.decode(resp.responseText);

                                        if(typeof(respObj["studentId"]) !== "undefined") {
                                            console.log("newly added");
                                            console.log(respObj);
                                            studentId = respObj["studentId"];
                                            console.log(studentId);
                                            Ext.getCmp("autocompNames").store.add({name : studentName, id : studentId});
                                        }

                                        Ext.get("scoreAnim").show(true).on("afteranimate", function() {
                                            Ext.get("scoreAnim").show({duration : 1.5 /*seconds*/});
                                        });

                                    });
                                }
                            }
                        ]
                    });
                }

            });