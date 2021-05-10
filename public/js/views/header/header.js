//Dynamically add and remove notifications here
// Create an element to hold all your text and markup

$(function() {
    var animationData = {
        v: "5.5.2",
        fr: 60,
        ip: 53,
        op: 125,
        w: 192,
        h: 192,
        nm: "Comp 1",
        ddd: 0,
        assets: [],
        layers: [{
            ddd: 0,
            ind: 1,
            ty: 4,
            nm: "Bell-ringing Outlines",
            sr: 1,
            ks: {
                o: {
                    a: 0,
                    k: 100,
                    ix: 11
                },
                r: {
                    a: 0,
                    k: 0,
                    ix: 10
                },
                p: {
                    a: 1,
                    k: [{
                        i: {
                            x: .667,
                            y: .892
                        },
                        o: {
                            x: .333,
                            y: 0
                        },
                        t: 53,
                        s: [96, 107, 0],
                        to: [0, -.569, 0],
                        ti: [0, .799, 0]
                    }, {
                        i: {
                            x: .667,
                            y: 1
                        },
                        o: {
                            x: .311,
                            y: 1
                        },
                        t: 59,
                        s: [96, 87.729, 0],
                        to: [0, -1.733, 0],
                        ti: [0, -.653, 0]
                    }, {
                        t: 119,
                        s: [96, 107, 0]
                    }],
                    ix: 2
                },
                a: {
                    a: 0,
                    k: [12, 12, 0],
                    ix: 1
                },
                s: {
                    a: 0,
                    k: [583.333, 583.333, 100],
                    ix: 6
                }
            },
            ao: 0,
            ef: [{
                ty: 5,
                nm: "CC Bend It",
                np: 7,
                mn: "CC Bend It",
                ix: 1,
                en: 1,
                ef: [{
                    ty: 0,
                    nm: "Bend",
                    mn: "CC Bend It-0001",
                    ix: 1,
                    v: {
                        a: 1,
                        k: [{
                            i: {
                                x: [.667],
                                y: [1]
                            },
                            o: {
                                x: [.167],
                                y: [.167]
                            },
                            t: 59,
                            s: [0]
                        }, {
                            i: {
                                x: [.667],
                                y: [1]
                            },
                            o: {
                                x: [.333],
                                y: [0]
                            },
                            t: 70,
                            s: [7]
                        }, {
                            i: {
                                x: [.667],
                                y: [1]
                            },
                            o: {
                                x: [.333],
                                y: [0]
                            },
                            t: 75,
                            s: [-4]
                        }, {
                            i: {
                                x: [.667],
                                y: [1]
                            },
                            o: {
                                x: [.333],
                                y: [0]
                            },
                            t: 85,
                            s: [2]
                        }, {
                            i: {
                                x: [.667],
                                y: [1]
                            },
                            o: {
                                x: [.333],
                                y: [0]
                            },
                            t: 93,
                            s: [-1]
                        }, {
                            t: 98,
                            s: [0]
                        }],
                        ix: 1
                    }
                }, {
                    ty: 3,
                    nm: "Start",
                    mn: "CC Bend It-0002",
                    ix: 2,
                    v: {
                        a: 0,
                        k: [95.5, -19],
                        ix: 2
                    }
                }, {
                    ty: 3,
                    nm: "End",
                    mn: "CC Bend It-0003",
                    ix: 3,
                    v: {
                        a: 0,
                        k: [93, 213],
                        ix: 3
                    }
                }, {
                    ty: 7,
                    nm: "Render Prestart",
                    mn: "CC Bend It-0004",
                    ix: 4,
                    v: {
                        a: 0,
                        k: 3,
                        ix: 4
                    }
                }, {
                    ty: 7,
                    nm: "Distort",
                    mn: "CC Bend It-0005",
                    ix: 5,
                    v: {
                        a: 0,
                        k: 1,
                        ix: 5
                    }
                }]
            }],
            shapes: [{
                ty: "gr",
                it: [{
                    ind: 0,
                    ty: "sh",
                    ix: 1,
                    ks: {
                        a: 0,
                        k: {
                            i: [
                                [0, 0],
                                [.956, .553],
                                [.174, .302]
                            ],
                            o: [
                                [-.555, .955],
                                [-.301, -.175],
                                [0, 0]
                            ],
                            v: [
                                [1.73, -.64],
                                [-1.004, .087],
                                [-1.73, -.64]
                            ],
                            c: !1
                        },
                        ix: 2
                    },
                    nm: "Path 1",
                    mn: "ADBE Vector Shape - Group",
                    hd: !1
                }, {
                    ty: "st",
                    c: {
                        a: 0,
                        k: [0, 0, 0, 1],
                        ix: 3
                    },
                    o: {
                        a: 0,
                        k: 100,
                        ix: 4
                    },
                    w: {
                        a: 0,
                        k: 2,
                        ix: 5
                    },
                    lc: 2,
                    lj: 2,
                    bm: 0,
                    nm: "Stroke 1",
                    mn: "ADBE Vector Graphic - Stroke",
                    hd: !1
                }, {
                    ty: "tr",
                    p: {
                        a: 1,
                        k: [{
                            i: {
                                x: .667,
                                y: 1
                            },
                            o: {
                                x: .333,
                                y: 0
                            },
                            t: 59,
                            s: [12, 21.64],
                            to: [.25, .25],
                            ti: [-.333, -.083]
                        }, {
                            i: {
                                x: .667,
                                y: .629
                            },
                            o: {
                                x: .333,
                                y: 0
                            },
                            t: 68,
                            s: [13.5, 23.14],
                            to: [.217, .054],
                            ti: [-.099, .302]
                        }, {
                            i: {
                                x: .667,
                                y: 1
                            },
                            o: {
                                x: .333,
                                y: .79
                            },
                            t: 73,
                            s: [14.42, 22.2],
                            to: [.053, -.162],
                            ti: [.379, .204]
                        }, {
                            i: {
                                x: .667,
                                y: .211
                            },
                            o: {
                                x: .333,
                                y: 0
                            },
                            t: 77,
                            s: [14, 22.14],
                            to: [-.558, -.301],
                            ti: [2.437, .178]
                        }, {
                            i: {
                                x: .667,
                                y: 1
                            },
                            o: {
                                x: .333,
                                y: .369
                            },
                            t: 83,
                            s: [15.844, 21.405],
                            to: [-2.291, -.167],
                            ti: [-.04, 0]
                        }, {
                            i: {
                                x: .667,
                                y: .579
                            },
                            o: {
                                x: .333,
                                y: 0
                            },
                            t: 91,
                            s: [6, 21.14],
                            to: [.042, 0],
                            ti: [-2.606, -.221]
                        }, {
                            i: {
                                x: .667,
                                y: 1
                            },
                            o: {
                                x: .333,
                                y: .5
                            },
                            t: 95,
                            s: [12.257, 21.552],
                            to: [2.561, .217],
                            ti: [-.124, -.124]
                        }, {
                            i: {
                                x: .667,
                                y: .544
                            },
                            o: {
                                x: .333,
                                y: 0
                            },
                            t: 99,
                            s: [17.5, 21.14],
                            to: [.148, .148],
                            ti: [2.582, -.083]
                        }, {
                            i: {
                                x: .667,
                                y: 1
                            },
                            o: {
                                x: .333,
                                y: .691
                            },
                            t: 103,
                            s: [12.057, 21.961],
                            to: [-1.778, .057],
                            ti: [.204, -.068]
                        }, {
                            i: {
                                x: .667,
                                y: 1
                            },
                            o: {
                                x: .333,
                                y: 0
                            },
                            t: 106,
                            s: [9.5, 21.14],
                            to: [-.5, .167],
                            ti: [-.417, -.083]
                        }, {
                            i: {
                                x: .667,
                                y: 1
                            },
                            o: {
                                x: .333,
                                y: 0
                            },
                            t: 114,
                            s: [13.5, 21.64],
                            to: [.417, .083],
                            ti: [.25, 0]
                        }, {
                            t: 118,
                            s: [12, 21.64]
                        }],
                        ix: 2
                    },
                    a: {
                        a: 0,
                        k: [0, 0],
                        ix: 1
                    },
                    s: {
                        a: 0,
                        k: [100, 100],
                        ix: 3
                    },
                    r: {
                        a: 1,
                        k: [{
                            i: {
                                x: [.833],
                                y: [.833]
                            },
                            o: {
                                x: [.167],
                                y: [.167]
                            },
                            t: 59,
                            s: [0]
                        }, {
                            i: {
                                x: [.833],
                                y: [.833]
                            },
                            o: {
                                x: [.167],
                                y: [.167]
                            },
                            t: 69,
                            s: [29]
                        }, {
                            i: {
                                x: [.833],
                                y: [.833]
                            },
                            o: {
                                x: [.167],
                                y: [.167]
                            },
                            t: 72,
                            s: [11]
                        }, {
                            i: {
                                x: [.833],
                                y: [.833]
                            },
                            o: {
                                x: [.167],
                                y: [.167]
                            },
                            t: 73,
                            s: [-4.575]
                        }, {
                            i: {
                                x: [.833],
                                y: [.833]
                            },
                            o: {
                                x: [.167],
                                y: [.167]
                            },
                            t: 77,
                            s: [-24]
                        }, {
                            i: {
                                x: [.833],
                                y: [.833]
                            },
                            o: {
                                x: [.167],
                                y: [.167]
                            },
                            t: 83,
                            s: [-4]
                        }, {
                            i: {
                                x: [.833],
                                y: [.833]
                            },
                            o: {
                                x: [.167],
                                y: [.167]
                            },
                            t: 86,
                            s: [14]
                        }, {
                            i: {
                                x: [.833],
                                y: [.833]
                            },
                            o: {
                                x: [.167],
                                y: [.167]
                            },
                            t: 91,
                            s: [10]
                        }, {
                            i: {
                                x: [.833],
                                y: [.833]
                            },
                            o: {
                                x: [.167],
                                y: [.167]
                            },
                            t: 95,
                            s: [-5]
                        }, {
                            i: {
                                x: [.833],
                                y: [.833]
                            },
                            o: {
                                x: [.167],
                                y: [.167]
                            },
                            t: 99,
                            s: [-5]
                        }, {
                            i: {
                                x: [.833],
                                y: [.833]
                            },
                            o: {
                                x: [.167],
                                y: [.167]
                            },
                            t: 103,
                            s: [8]
                        }, {
                            t: 106,
                            s: [2]
                        }],
                        ix: 6
                    },
                    o: {
                        a: 0,
                        k: 100,
                        ix: 7
                    },
                    sk: {
                        a: 0,
                        k: 0,
                        ix: 4
                    },
                    sa: {
                        a: 0,
                        k: 0,
                        ix: 5
                    },
                    nm: "Transform"
                }],
                nm: "Group 1",
                np: 2,
                cix: 2,
                bm: 0,
                ix: 1,
                mn: "ADBE Vector Group",
                hd: !1
            }, {
                ty: "gr",
                it: [{
                    ind: 0,
                    ty: "sh",
                    ix: 1,
                    ks: {
                        a: 0,
                        k: {
                            i: [
                                [0, 7],
                                [4.652, -2.631],
                                [-.03, -1.912],
                                [0, 0],
                                [0, 0]
                            ],
                            o: [
                                [0, -4.399],
                                [-1.665, .941],
                                [.114, 7.316],
                                [0, 0],
                                [0, 0]
                            ],
                            v: [
                                [6, -.654],
                                [-3.387, -5.715],
                                [-6.003, -1.044],
                                [-9, 8.346],
                                [9, 8.346]
                            ],
                            c: !0
                        },
                        ix: 2
                    },
                    nm: "Path 1",
                    mn: "ADBE Vector Shape - Group",
                    hd: !1
                }, {
                    ty: "st",
                    c: {
                        a: 0,
                        k: [0, 0, 0, 1],
                        ix: 3
                    },
                    o: {
                        a: 0,
                        k: 100,
                        ix: 4
                    },
                    w: {
                        a: 0,
                        k: 2,
                        ix: 5
                    },
                    lc: 2,
                    lj: 2,
                    bm: 0,
                    nm: "Stroke 1",
                    mn: "ADBE Vector Graphic - Stroke",
                    hd: !1
                }, {
                    ty: "tr",
                    p: {
                        a: 0,
                        k: [12, 8.654],
                        ix: 2
                    },
                    a: {
                        a: 0,
                        k: [0, 0],
                        ix: 1
                    },
                    s: {
                        a: 0,
                        k: [100, 100],
                        ix: 3
                    },
                    r: {
                        a: 1,
                        k: [{
                            i: {
                                x: [.667],
                                y: [.649]
                            },
                            o: {
                                x: [.333],
                                y: [0]
                            },
                            t: 59,
                            s: [0]
                        }, {
                            i: {
                                x: [.667],
                                y: [.534]
                            },
                            o: {
                                x: [.333],
                                y: [.605]
                            },
                            t: 64,
                            s: [16.49]
                        }, {
                            i: {
                                x: [.71],
                                y: [.694]
                            },
                            o: {
                                x: [.302],
                                y: [-.094]
                            },
                            t: 69,
                            s: [26.049]
                        }, {
                            i: {
                                x: [.778],
                                y: [1]
                            },
                            o: {
                                x: [.426],
                                y: [1.157]
                            },
                            t: 74,
                            s: [-16.741]
                        }, {
                            i: {
                                x: [.667],
                                y: [1]
                            },
                            o: {
                                x: [.167],
                                y: [0]
                            },
                            t: 78,
                            s: [-30.021]
                        }, {
                            i: {
                                x: [.667],
                                y: [1]
                            },
                            o: {
                                x: [.167],
                                y: [0]
                            },
                            t: 88,
                            s: [19.021]
                        }, {
                            i: {
                                x: [.79],
                                y: [1]
                            },
                            o: {
                                x: [.299],
                                y: [0]
                            },
                            t: 96,
                            s: [-11]
                        }, {
                            i: {
                                x: [.585],
                                y: [.443]
                            },
                            o: {
                                x: [.178],
                                y: [0]
                            },
                            t: 103,
                            s: [9]
                        }, {
                            i: {
                                x: [.667],
                                y: [1]
                            },
                            o: {
                                x: [.348],
                                y: [1.956]
                            },
                            t: 107,
                            s: [-2.3]
                        }, {
                            i: {
                                x: [.833],
                                y: [1]
                            },
                            o: {
                                x: [.167],
                                y: [0]
                            },
                            t: 111,
                            s: [-5]
                        }, {
                            i: {
                                x: [.833],
                                y: [1]
                            },
                            o: {
                                x: [.167],
                                y: [0]
                            },
                            t: 114,
                            s: [2]
                        }, {
                            t: 118,
                            s: [0]
                        }],
                        ix: 6
                    },
                    o: {
                        a: 0,
                        k: 100,
                        ix: 7
                    },
                    sk: {
                        a: 0,
                        k: 0,
                        ix: 4
                    },
                    sa: {
                        a: 0,
                        k: 0,
                        ix: 5
                    },
                    nm: "Transform"
                }],
                nm: "Group 2",
                np: 2,
                cix: 2,
                bm: 0,
                ix: 2,
                mn: "ADBE Vector Group",
                hd: !1
            }, {
                ty: "gr",
                it: [{
                    ty: "tr",
                    p: {
                        a: 0,
                        k: [12, 21.64],
                        ix: 2
                    },
                    a: {
                        a: 0,
                        k: [0, 0],
                        ix: 1
                    },
                    s: {
                        a: 0,
                        k: [100, 100],
                        ix: 3
                    },
                    r: {
                        a: 0,
                        k: 0,
                        ix: 6
                    },
                    o: {
                        a: 0,
                        k: 100,
                        ix: 7
                    },
                    sk: {
                        a: 0,
                        k: 0,
                        ix: 4
                    },
                    sa: {
                        a: 0,
                        k: 0,
                        ix: 5
                    },
                    nm: "Transform"
                }],
                nm: "Group 3",
                np: 0,
                cix: 2,
                bm: 0,
                ix: 3,
                mn: "ADBE Vector Group",
                hd: !1
            }],
            ip: -1,
            op: 614,
            st: -1,
            bm: 0
        }],
        markers: []
    },
    params = {
        container: document.getElementById("notif-bell"),
        renderer: "svg",
        loop: !0,
        autoplay: !0,
        animationData: animationData
    };
    anim = lottie.loadAnimation(params);
    anim.pause();
    $("#notif-danger-dots").hide();
    $("#notif-li").on('click', function(){
        $(this).toggleClass('iq-show');
        $('#notif-a').toggleClass('active');
    });

    getAlerts = function(){
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            url: "/alerts/unresolved",
            method: 'get',             
            success: function(data){
                console.log("NOTIFICATION BAR");
                console.log(data);
                $('#notif-count').text(data.length);
                $("#notification-card").children('.iq-sub-card').remove();
                for (var i = 0; i < data.length; ++i) {
                    var full_name = data[i].full_name;
                    var date = data[i].occured_at;
                    var rule = "Rule Triggered";
                    var duration = data[i].duration;
                    var location = data[i].reader.location.location_description;

                    function getRandomInt(min, max) {
                        min = Math.ceil(min);
                        max = Math.floor(max);
                        return Math.floor(Math.random() * (max - min) + min); //The maximum is exclusive and the minimum is inclusive
                    }
                    var imageNum = "/0".concat(getRandomInt(1, 9), ".jpg");

                    if (data.length > 5){
                        $("#notif-danger-dots").show();
                        anim.play();
                    }else if(data.length > 0){

                    }else{
                        $("#notif-danger-dots").hide();
                        anim.stop();
                    }
                    var container2 = $('<a/>', {'class': 'iq-sub-card', 'id': 'notif-'.concat(i)}).append(
                            $('<div/>', {'class': 'media align-items-center'}).append(
                                $('<div/>', {class: ''}).append(
                                    $('<img/>', {class: 'avatar-40 rounded', src: imagesUrl + imageNum, alt: ''})
                                )
                            ).append(
                                $('<div/>', {'class': 'media-body ml-3'}).append(
                                    $('<h6/>', {class: 'mb-0', text: full_name})
                                ).append(
                                    $('<small/>', {class: 'float-right font-size-12', text: duration})
                                ).append(
                                    $('<p/>', {class:'mb-0', text: rule})
                                )
                            )
                        )
                        container2.on('click', function(event) {
                            var contentPanelId = $(this).attr("id");
                            alert(contentPanelId);
                    });
                    $("#notification-card").append(container2);

        
                }
            },
            error: function(xhr, request, error){
                console.log('error');
                console.log(xhr.responseText);
            },
        })
        .always(function () {
            setTimeout(getAlerts, 60000);
        });
    }

    getAlerts()
});