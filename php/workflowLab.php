<?php

//initiate the session (must be the first statement in the document)
session_start();

// <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
// <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>

            
echo <<<_FixedHTML

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans+Condensed:300" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="../css/jquery-ui.min.css">
    <link rel="stylesheet" type="text/css" href="../css/workflowLab.css">

    
    <script src="../js/jquery.js"></script>
    <script src="../js/jquery-ui.min.js"></script>
    <script src="../js/jquery.tablesorter.js"></script>
    
    
    <title>Work Flow Lab</title>
    
    <script>

    </script>
</head>
<body> 
    <div id='container'>
    <div id='controls'>
        <div id='controlHide' class='point'><img src='../media/hide.png'></div>
        <div id='wfTitle'><img src='../media/logo6.png' class='img1'></div>
        <div id='controlAccordian'>
        </div>
    </div>
    <div id="content">
        <div id='controlShow'  class='point'><img src='../media/show.png'></div> 
        <div id='contentContainer'>
            <div id='contentUpdate'>
                
            </div>
        </div>
    </div>
        
        </div>
    </div>
    <script>

        window.onload = function() {
            updateControls();
        };

        function updateControls(){

            $.ajax({
                type: 'POST',
                url: 'fp/controls_update.php',   
                dataType: 'html',
                success: function (html) {
                    $("#controlAccordian").hide().fadeIn("slow").html(html);
                },
                error: function(XMLHttpRequest, textStatus, errorThrown) { 
                    $("#controlAccordian").hide().fadeIn("slow").html("error loading controls.");
                }
            });
        }

        function hActive(){  
            $("#c1, #c2, #c3, #c4").slideUp();
            $("#activeContent").slideToggle("slow");
            $(".accd_header").css("background", "linear-gradient( #555, #444)");
        }

        function h0(){  
            $("#c1, #c2, #c3, #c4, #activeControl").slideUp();
            $(".selectRadio").css("background", "linear-gradient( #444, #333)");
            $("#currentSelection").val("h0");

            //update content section
            $("#contentUpdate").hide().fadeIn("slow").html("This is home.");

        }

        function h1(){  
            $("#c0, #c2, #c3, #c4, #activeControl").slideUp();
            $("#c1").slideToggle("slow");
            var cur = $("#currentSelection").val();
            
            if (cur != "h1"){
                $(".selectRadio").css("background", "linear-gradient( #444, #333)");
                $("#radio-wf-active").css("background", "#E26600");
                getWorkflowList("Active");
                $("#currentSelection").val("h1");
            }
        }

        function h3(){  
            $("#c0, #c1, #c2, #c4, #activeControl").slideUp();
            $("#c3").slideToggle("slow");
            
            var cur = $("#currentSelection").val();
            
            if (cur != "h3"){
                $(".selectRadio").css("background", "linear-gradient( #444, #333)");
                $("#radio-op-active").css("background", "#E26600");
                getOpList("Active");
                $("#currentSelection").val("h3");
            }
            
        }   

        function h4(){  
            $("#c0, #c1, #c2, #c3, #activeControl").slideUp();
            $("#c4").slideToggle("slow");
            $(".selectRadio").css("background", "linear-gradient( #444, #333)");

            var cur = $("#currentSelection").val();
            
            if (cur != "h4"){
                $("#currentSelection").val("h4");
            }
        }

        $("#controlShow").hide();

        $('body').on('click', '.accd_header', function() {
            $(".accd_header").css("background", "linear-gradient( #555, #444)");  
            $(this).css("background", "#2C5C83");  
        });

        $('body').on('click', '.selectRadio', function() {
            $(".selectRadio").css("background", "linear-gradient( #333, #444)");  
            $(this).css("background", "#E26600");  
        });
    
        $('body').on('click', '#controlHide', function() {
            $("#controls").toggle("slow", function(){
                $("#controlShow").fadeIn();
                $("#controlHide").hide();
            });
        });

        $('body').on('click', '#controlShow', function() {
            $("#controlShow").hide();
            $("#controls").toggle("slow", function(){
                $("#controlHide").fadeIn();
            });
        });
        
        $('body').on('change', '#wfByItem', function() {
            getWorkflowList('Item', this.value);
            $(".selectRadio").css("background", "linear-gradient( #333, #444)");  
            $("#radio-wf-item").css("background", "#E26600");
        })

        $('body').on('change', '#wfByGroup', function() {
            getWorkflowList('Group', this.value);
            $(".selectRadio").css("background", "linear-gradient( #333, #444)");  
            $("#radio-wf-group").css("background", "#E26600");
        })

        $('body').on('focus', '#stringSearchWF', function() {
            $(".selectRadio").css("background", "linear-gradient( #333, #444)");  
            $("#radio-wf-text").css("background", "#E26600");
        })

        function getWorkflowList(searchType, searchTerm){
            
            $.ajax({
                type: 'POST',
                url: 'fp/wf_getList.php',   
                dataType: 'html',
                data: {
                    search_type : searchType,
                    search_term : searchTerm
                },
                success: function (html) {
                    $("#contentUpdate").hide().fadeIn("slow").html(html);
                    $("#wfList").tablesorter();
                    $('#wfSelection').val(searchType);
                },
                error: function(XMLHttpRequest, textStatus, errorThrown) { 
                    $("#contentUpdate").hide().fadeIn("slow").html("error loading new item form.");
                }
            });
        }

        function getWorkflowListString(){
            var searchString = $("#stringSearchWF").val();
            getWorkflowList('String', searchString);
        }

        function updateWfList(){
            
            var wfList = $('#wfSelection').val();

            switch(wfList){
                case "Active":
                    getWorkflowList("Active")
                    break;

                case "Complete":
                    getWorkflowList("Complete")
                    break;

                case "Pending":
                    getWorkflowList("Pending")
                    break;

                case "Template":
                    getWorkflowList("Template")
                    break;

                case "String":
                    getWorkflowListString()
                    break;

                case "All":
                    getOpList("%")
                    break;

                case "Item":
                    var typeVal = $('#wfByItem').val();
                    getOpList('Item', typeVal);
                    break;
                
                case "Group":
                    var typeVal = $('#wfByGroup').val();
                    getOpList('Group', typeVal);
                    break;

                default:
                    getOpList("%")
                    break;
            }

        }

        function openWorkflow(wfl){

            var wfID = wfl.id;

            $.ajax({
                type: 'POST',
                url: 'fp/wf_getWorkflow.php',   
                dataType: 'html',
                data: {
                    wf_id : wfID
                },
                success: function (html) {
                    $("#contentUpdate").hide().fadeIn("slow").html(html);  
                    $(".accd_header").css("background", "linear-gradient( #555, #444)");

                    $.ajax({
                        type: 'POST',
                        url: 'fp/wf_stepIndex.php',
                        dataType: 'html',
                        data: {
                            wf_id : wfID
                        },
                        success: function (html) {

                            var result = $.parseJSON(html);

                            var stepIndex = result[0];

                            $( "#stepAccordian" ).accordion({
                                active: stepIndex,
                                collapsible: true,
                                header: ".stepHeader",
                                heightStyle: "content",
                                animate: 500
                            });

                            $('#stepAccordian .stepHeader').bind('click',function(){
                                var self = this;
                                setTimeout(function() {
                                    theOffset = $(self).position();
                                    theNextOffset = $('#stepAccordian').position();
                                    $('#accordianScroll').animate({ scrollTop: theOffset.top - theNextOffset.top + 1}, 1000);
                                }, 510); // ensure the collapse animation is done
                            });

                            $(function(){
                                var self =  $('#openStep');
                                setTimeout(function() {
                                    theOffset = $(self).position();
                                    theNextOffset = $('#stepAccordian').position();
                                    $('#accordianScroll').animate({ scrollTop: theOffset.top - theNextOffset.top + 1}, 1500);
                                }, 510); // ensure the collapse animation is done
                            });


                            $(function(){
                                $('#progress').progressbar({
                                    value: result[1] / result[2] * 100
                                });
                            });

                            getWFHeader(wfID);
                            $("#currentSelection").val("wf");
                        }
                    });
                },
                error: function(XMLHttpRequest, textStatus, errorThrown) { 
                    $("#contentUpdate").hide().fadeIn("slow").html("error loading new item form.");
                }
            });
        }

        function getWFHeader(wfID){
           
            $.ajax({
                type: 'POST',
                url: 'fp/wf_getHeader.php',   
                dataType: 'html',
                data: {
                    wf_id : wfID
                },
                success: function (html) {
                    $("#c1, #c3, #c4").hide();
                    $("#activeControl").html(html).show( function(){
                        $("#activeContent").slideDown("slow");
                    });
                },
                error: function(XMLHttpRequest, textStatus, errorThrown) { 
                    $("#activeControl").hide().fadeIn("slow").html("error loading new item form.");
                }
            });
        }

        function editWf(wfID){

            getModHeader(wfID);
            getModAvailOps(wfID);
            getModSteps(wfID);
        }

        function getModHeader(wfID){

            $.ajax({
                type: 'POST',
                url: 'fp/wf_mod_header.php',   
                dataType: 'html',
                data: {
                   wfl_id : wfID
                },
                success: function (html) {
                   $("#activeContent").hide().fadeIn("slow").html(html);
                },
                error: function(XMLHttpRequest, textStatus, errorThrown) { 
                    $("#contentUpdate").hide().fadeIn("slow").html("error loading workflow.");
                }
            });
        }

        function getModAvailOps(wfID){
           
            $.ajax({
                type: 'POST',
                url: 'fp/wf_mod_ops.php',   
                dataType: 'html',
                data: {
                   wfl_id : wfID
                },
                success: function (html) {
                   $("#contentUpdate").hide().fadeIn("slow").html(html);
                   opsSortable();
                },
                error: function(XMLHttpRequest, textStatus, errorThrown) { 
                    $("#contentUpdate").hide().fadeIn("slow").html("error loading available ops.");
                }
            });
        }

        function getModSteps(wfID){

            $.ajax({
                type: 'POST',
                url: 'fp/wf_mod_steps.php',   
                dataType: 'html',
                data: {
                   wfl_id : wfID
                },
                success: function (html) {
                   $("#accordianScroll").hide().fadeIn("slow").html(html);
                   modAccordian();
                   $('.stepButtons').hide();
                   $('#wfBtnReset').show();
                },
                error: function(XMLHttpRequest, textStatus, errorThrown) { 
                    $("#contentUpdate").hide().fadeIn("slow").html("error loading available ops.");
                }
            });
        }

        function modAccordian(){

            $( "#stepAccordian" ).accordion({
                active: false,
                collapsible: true,
                header: ".stepHeader",
                heightStyle: "content",
                animate: 500
                })
            .sortable({
                items: '.s_panel',
                forceHelperSize: true,
                forcePlaceHolderSize: false,
                dropOnEmpty: true,
                tolerance: "intersect",
                placeholder: "sortable-placeholder",
                over: function (event, ui) {
                    removeIntent = false;
                    $(ui.item).find(".stepHeader").css("border-bottom", "none");
                },
                out: function (event, ui) {
                    removeIntent = true;    
                    $(ui.item).find(".stepHeader").css("border-bottom", "6px solid red");                              
                },
                beforeStop: function (event, ui) {
                    if(removeIntent == true){
                        ui.item.remove();   
                    }
                },
                stop: function (event, ui) {
                    $(ui.item).find(".stepHeader").css("border-bottom", "none");
                    $('#wfBtnReset').hide();
                    $('#wfBtnSave').show();
                    $('#wfBtnUndo').show();
                },
                change: function( event, ui ) {
                    $('#wfBtnReset').hide();
                    $('#wfBtnSave').show();
                    $('#wfBtnUndo').show();
                }
            });

            $('#wfBtnSave').hide();
            $('#wfBtnUndo').hide();
            
            $('#stepAccordian .stepHeader').bind('click',function(){
                var self = this;
                setTimeout(function() {
                    theOffset = $(self).position();
                    theNextOffset = $('#stepAccordian').position();
                    $('#accordianScroll').animate({ scrollTop: theOffset.top - theNextOffset.top + 1}, 1000);
                }, 510); // ensure the collapse animation is done
            });

            $( function() {
                $( ".datePicker" ).datepicker({
                    dateFormat: "yy-mm-dd"
                });
              } );

        }

        function opsSortable(){
            $( "#sourceOps" ).sortable({
                connectWith: ".connectedSortable",
                forceHelperSize: true,
                forcePlaceHolderSize: true,
                placeholder: "sortable-placeholder",
                scroll : false,
                dropOnEmpty: true,
                tolerance: "intersect",
                remove: function(e,tr) {
                    copyHelper= tr.item.clone().insertAfter(tr.item);
                    $(this).sortable('cancel');
                    return tr.clone();
                }     
            }).disableSelection();

            $("#sourceOps").on("click", ".s_panel", function(){
                $( this ).clone().appendTo( "#stepAccordian" );
                $('#wfBtnReset').hide();
                $('#wfBtnSave').show();
                $('#wfBtnUndo').show();
            });
        }

        $('body').on('change', '#filterOps', function() {
            filterOps(this.value);
        })

        function filterOps(filterString){

            $.ajax({
                type: 'POST',
                url: 'fp/wf_op_filter.php',   
                dataType: 'html',
                data: {
                   filter : filterString
                },
                success: function (html) {
                   $("#sourceOps").hide().fadeIn("slow").html(html);
                   modAccordian();
                },
                error: function(XMLHttpRequest, textStatus, errorThrown) { 
                    $("#contentUpdate").hide().fadeIn("slow").html("error loading available ops.");
                }
            });
        }

        $(document).on("input", "#wfContainer input.textTableInput", function () {
            this.style.backgroundColor = '#FDF19D';
            $('.editH1').show();
            $('.editH2').hide();
        });

        function statusChange(e){
            var thisStatus = e.options[e.selectedIndex].value;
            $('#staStore').val(thisStatus);
            e.style.backgroundColor = '#FDF19D';
            $('.editH1').show();
            $('.editH2').hide();
        }

        function cancelEdit(wfID){

            var g=document.createElement('div');
            g.setAttribute("id", wfID);
            openWorkflow(g);

        }

        function resetWf(){

            var wfID = document.getElementById("wfID").value

            $.ajax({
                type: 'POST',
                url: 'fp/wf_reset.php',
                dataType: 'html',
                data: {
                    wkf_ID: wfID
                },
                success: function (html) {
                    // alert ("Workflow Reset.");
                    // var g=document.createElement('div');
                    // g.setAttribute("id", wfID);
                    editWf(wfID)
                }
            });
        }

        function saveWfHeader(){
            
            var wkfID = $('#wfID').val();
            var wkfNum = $('#wfNum').val();
            var wkfItem = $('#wfItem').val();
            var wkfDesc = $('#wfDesc').val();
            var wkfSta = $('#staStore').val();
            var wkfRef = $('#wfRef').val();
            var wkfGrp = $('#wfGrp').val();
            var wkfNot = $('#wfNot').val();
                        
            $.ajax({
                type: 'POST',
                url: 'fp/wf_save_header.php',
                dataType: 'html',
                data: {
                    wkf_ID: wkfID,
                    wkf_Num: wkfNum,
                    wkf_Item: wkfItem,
                    wkf_Desc: wkfDesc,
                    wkf_Sta: wkfSta,
                    wkf_Ref: wkfRef,
                    wkf_Grp: wkfGrp,
                    wkf_Not: wkfNot
                },
                success: function (html) {
                    if (html == 1){
                        alert("Error updating data.");
                    } else if (html == 2){
                        alert("Error sending data.");
                    } 
                    getModHeader(wkfID);
                }
            }); 
        }

        function saveWf(){

            var wfID = document.getElementById("wfID").value
            
            var listOrder = $('#stepAccordian').sortable('toArray');
            
                $.ajax({
                    type: 'POST',
                    url: 'fp/wf_save_order.php',
                    dataType: 'html',
                    data: {
                        wkf_ID: wfID,
                        stepOrder: listOrder,
                        verify: '1'
                    },
                    success: function (html) {
                        //alert(html);
                        getModSteps(wfID); 
                    },
                    error: function(XMLHttpRequest, textStatus, errorThrown) { 
                        alert("error");
                    }
                }); 
        }

        function deleteWf(){

            if (confirm('PERMANENTLY delete this Workflow?')) {

                if (confirm('Confirm Workflow deletion.')) {
                    
                    var wfID = document.getElementById("wfID").value
                    
                        $.ajax({
                            type: 'POST',
                            url: 'fp/wf_delete_wf.php',
                            dataType: 'html',
                            data: {
                                wkf_ID: wfID
                            },
                            success: function (html) {
                                if (html == 1){
                                    alert("Error deleting workflow.");
                                } else if (html == 2){
                                    alert("Error sending data.");
                                } else if (html == 3){
                                    alert("Ownership Error.");
                                } else {
                                    updateWfList();
                                    h1();
                                    
                                }
                            },
                            error: function(XMLHttpRequest, textStatus, errorThrown) { 
                                alert("error");
                            }
                        });

                } else {
                    
                }
            } else {
                
            }

        }

        function cancelNewWf(){
            updateControls();
        }

        function fromScratch(){

            getNewHeader();
            getNewAvailOps();
            getNewSteps();
    
            var headerContent = "<div id='activeItem' class='accd_header_active' onclick='hActive()'>New Workflow</div><div id='activeContent' class='accd_content' hidden></div>";
    
            $("#activeControl").html(headerContent).slideDown();
            $("#c1, #c2, #c3, #c4").hide();
            $("#activeContent").slideDown("slow");
            $(".accd_header").css("background", "linear-gradient( #555, #444)");
        }

        function fromExistWf(){
            var wfID = $("#newFromExist").val();
            fromExisting(wfID);
        }

        function fromExistTemp(){
            var wfID = $("#newFromTemp").val();
            fromExisting(wfID);
        }

        function fromExisting(wfID){
            existHeader(wfID);
            existSteps(wfID);
        }

        function existHeader(wfID){
            
            $.ajax({
                type: 'POST',
                url: 'fp/wf_new_exist_header.php',   
                dataType: 'html',
                data: {
                    wfl_id : wfID
                },
                success: function (html) {
                    $("#activeContent").hide().fadeIn("slow").html(html);
                },
                error: function(XMLHttpRequest, textStatus, errorThrown) { 
                    $("#contentUpdate").hide().fadeIn("slow").html("error loading workflow.");
                }
            });
        }

        function existSteps(wfID){
            
            $.ajax({
                type: 'POST',
                url: 'fp/wf_new_exist_steps.php',   
                dataType: 'html',
                data: {
                    wf_id : wfID
                },
                success: function (html) {
                    $("#contentUpdate").hide().fadeIn("slow").html(html);  
                    
                        $( "#stepAccordian" ).accordion({
                            active: false,
                            collapsible: true,
                            header: ".stepHeader",
                            heightStyle: "content",
                            animate: 500
                        });

                        $('#stepAccordian .stepHeader').bind('click',function(){
                            var self = this;
                            setTimeout(function() {
                                theOffset = $(self).position();
                                theNextOffset = $('#stepAccordian').position();
                                $('#accordianScroll').animate({ scrollTop: theOffset.top - theNextOffset.top + 5}, 1000);
                            }, 510); // ensure the collapse animation is done
                        });

                       $("#currentSelection").val("wf");
                },
                error: function(XMLHttpRequest, textStatus, errorThrown) { 
                    $("#contentUpdate").hide().fadeIn("slow").html("error loading new item form.");
                }
            });
        }

        function saveNewExisting(){

            saveNewExistHeader();

        }

        function saveNewExistHeader(){
            
            var wkfNum = $('#wfNum').val();
            var wkfItem = $('#wfItem').val();
            var wkfDesc = $('#wfDesc').val();
            var wkfSta = $('#staStore').val();
            var wkfRef = $('#wfRef').val();
            var wkfGrp = $('#wfGrp').val();
            var wkfNot = $('#wfNot').val();
                        
            $.ajax({
                type: 'POST',
                url: 'fp/wf_save_newHeader.php',
                dataType: 'html',
                data: {
                    wkf_Num: wkfNum,
                    wkf_Item: wkfItem,
                    wkf_Desc: wkfDesc,
                    wkf_Sta: wkfSta,
                    wkf_Ref: wkfRef,
                    wkf_Grp: wkfGrp,
                    wkf_Not: wkfNot
                },
                success: function (html) {
                    if (html == 1){
                        alert("Error updating data.");
                    } else {
                        saveExistingSteps(html);
                    } 
                }
            });
        }

        function saveExistingSteps(wfID){
    
            var exID = document.getElementById("wfExID").value

            $.ajax({
                type: 'POST',
                url: 'fp/wf_save_new_exist_steps.php',
                dataType: 'html',
                data: {
                    wkf_ID: wfID,
                    ex_ID: exID
                },
                success: function (html) {
                    var g=document.createElement('div');
                    g.setAttribute("id", wfID);
                    openWorkflow(g);
                },
                error: function(XMLHttpRequest, textStatus, errorThrown) { 
                    alert("error");
                }
            }); 

        }

        function newWorkflow(){
            $.ajax({
                type: 'POST',
                url: 'fp/wf_new_options.php',   
                dataType: 'html',
                success: function (html) {
                    $("#c1, #c2, #c3, #c4").hide();
                   $("#activeControl").html(html).slideDown();
                },
                error: function(XMLHttpRequest, textStatus, errorThrown) { 
                    $("#contentUpdate").hide().fadeIn("slow").html("error loading workflow.");
                }
            });

        }

        function getNewHeader(){
            
            $.ajax({
                type: 'POST',
                url: 'fp/wf_new_header.php',   
                dataType: 'html',
                success: function (html) {
                   $("#activeContent").hide().fadeIn("slow").html(html);
                },
                error: function(XMLHttpRequest, textStatus, errorThrown) { 
                    $("#contentUpdate").hide().fadeIn("slow").html("error loading workflow.");
                }
            });
        }

        function getNewSteps(){
                
            $.ajax({
                type: 'POST',
                url: 'fp/wf_new_steps.php',   
                dataType: 'html',
                success: function (html) {
                    $("#stepsUpdate").hide().fadeIn("slow").html(html);
                    modAccordian();
                },
                error: function(XMLHttpRequest, textStatus, errorThrown) { 
                    $("#contentUpdate").hide().fadeIn("slow").html("error loading available ops.");
                }
            });
        }

        function getNewAvailOps(){
            
             $.ajax({
                 type: 'POST',
                 url: 'fp/wf_new_ops.php',   
                 dataType: 'html',
                 success: function (html) {
                    $("#contentUpdate").hide().fadeIn("slow").html(html);
                    opsSortable();
                 },
                 error: function(XMLHttpRequest, textStatus, errorThrown) { 
                     $("#contentUpdate").hide().fadeIn("slow").html("error loading available ops.");
                 }
             });
        }

        function saveNewWf(){

            saveNewHeader();
        }

        function saveNewHeader(){

            var wkfNum = $('#wfNum').val();
            var wkfItem = $('#wfItem').val();
            var wkfDesc = $('#wfDesc').val();
            var wkfSta = $('#staStore').val();
            var wkfRef = $('#wfRef').val();
            var wkfGrp = $('#wfGrp').val();
            var wkfNot = $('#wfNot').val();
                        
            $.ajax({
                type: 'POST',
                url: 'fp/wf_save_newHeader.php',
                dataType: 'html',
                data: {
                    wkf_Num: wkfNum,
                    wkf_Item: wkfItem,
                    wkf_Desc: wkfDesc,
                    wkf_Sta: wkfSta,
                    wkf_Ref: wkfRef,
                    wkf_Grp: wkfGrp,
                    wkf_Not: wkfNot
                },
                success: function (html) {
                    if (html == 1){
                        alert("Error updating data.");
                    } else {
                        saveNewWfSteps(html);
                    } 
                }
            });
        }

        function saveNewWfSteps(wfID){
            
            var listOrder = $('#stepAccordian').sortable('toArray');
            
                $.ajax({
                    type: 'POST',
                    url: 'fp/wf_save_order.php',
                    dataType: 'html',
                    data: {
                        wkf_ID: wfID,
                        stepOrder: listOrder,
                        verify: 1
                    },
                    success: function (html) {
                        var g=document.createElement('div');
                        g.setAttribute("id", wfID);
                        openWorkflow(g);
                    },
                    error: function(XMLHttpRequest, textStatus, errorThrown) { 
                        alert("error");
                    }
                }); 
        }
            
        $(document).on("input", "#accordionHolder input.stepInput", function () {
            this.style.backgroundColor = '#FDF19D';
            var buttonDiv = '#stepEditButtons' + $(this).closest('.s_panel').attr('id');
            $(buttonDiv).show();
        });

        $(document).on("input change", "#accordionHolder input.datePicker", function () {
            this.style.backgroundColor = '#FDF19D';
            var buttonDiv = '#stepEditButtons' + $(this).closest('.s_panel').attr('id');
            $(buttonDiv).show();
        });

        function userChange(e){
            var thisUser = e.options[e.selectedIndex].value;
            var thisStep = e.id;

            document.getElementById('userStore' + thisStep).value = thisUser;
            e.style.backgroundColor = '#FDF19D';
            var buttonDiv = '#stepEditButtons' + $(e).closest('.s_panel').attr('id');
            $(buttonDiv).show();
        }

        function completeStep(stepID){
            
            var stepNoteID = stepID + "note";
            var stepNote =  document.getElementById(stepNoteID).value;

            var wfID = document.getElementById("wfIDHolder").value
            
            $.ajax({
                type: 'POST',
                url: 'fp/wf_compStep.php',
                dataType: 'html',
                data: {
                    stp_ID: stepID,
                    stp_note: stepNote
                },
                success: function (html) {
                    if (html == "1"){
                        alert("Workflow Complete.");
                    }

                    var g=document.createElement('div');
                    g.setAttribute("id", wfID);
                    openWorkflow(g);
                },
                error: function(XMLHttpRequest, textStatus, errorThrown) { 
                    alert("error");
                }
            });
            
        }

        function saveEditedStep(stpID){

            var stpUser = $('#userStore' + stpID).val();
            var stpNotes = $('#stpNotes' + stpID).val();   
            var stpTitle = $('#stpTitle' + stpID).val();
            var stpDesc = $('#stpDesc' + stpID).val();
            var stpDetail = $('#stpDetail' + stpID).val();
            var stpDate = $('#stpDate' + stpID).val();

            var wfID = $('#wfID').val();

            $.ajax({
                type: 'POST',
                url: 'fp/wf_save_step_change.php',   
                dataType: 'html',
                data: {
                    stp_ID : stpID,
                    stp_title : stpTitle,
                    stp_desc : stpDesc,
                    stp_detail : stpDetail,
                    stp_notes : stpNotes,
                    stp_user : stpUser,
                    stp_date : stpDate
                },
                success: function (html) {
                    if (html == 1){
                        alert("Error updating data.");
                    } else if (html == 2){
                        alert("Error sending data.");
                    } 
                    getModSteps(wfID);
                }

            });
        }

        function getOpList(searchType, searchTerm){
            
            $.ajax({
                type: 'POST',
                url: 'fp/op_getList.php',   
                dataType: 'html',
                data: {
                    search_type : searchType,
                    search_term : searchTerm
                },
                success: function (html) {
                    $("#contentUpdate").hide().fadeIn("slow").html(html);
                    $("#opList").tablesorter();
                    $('#stepSelection').val(searchType);
                },
                error: function(XMLHttpRequest, textStatus, errorThrown) { 
                    $("#contentUpdate").hide().fadeIn("slow").html("error loading new item form.");
                }
            });
        }

        function getOpListString(){
            var searchString = $("#stringSearchOp").val();
            getOpList('String', searchString);
            $('#stepSelection').val('String');
        }

        $('body').on('change', '#opByType', function() {
            getOpList('Type', this.value);
            $(".selectRadio").css("background", "linear-gradient( #333, #444)");  
            $("#radio-op-item").css("background", "#E26600");
            $('#stepSelection').val('String');
        })

        $('body').on('focus', '#stringSearchOp', function() {
            $(".selectRadio").css("background", "linear-gradient( #333, #444)");  
            $("#radio-op-text").css("background", "#E26600");
        })

        function newOp(){

            $.ajax({
                type: 'POST',
                url: 'fp/op_new.php',   
                dataType: 'html',
                success: function (html) {
                    $("#activeControl").show().html(html);
                    hActive();
                },
                error: function(XMLHttpRequest, textStatus, errorThrown) { 
                    $("#contentUpdate").hide().fadeIn("slow").html("error loading step details.");
                }
            });
        }

        function editOp(opID){

            $.ajax({
                type: 'POST',
                url: 'fp/op_mod_detail.php',   
                dataType: 'html',
                data: {
                   op_id : opID
                },
                success: function (html) {
                    //$("#activeControl").hide().slideToggle("slow").html(html);
                    $("#activeControl").show().html(html);
                    hActive();
                },
                error: function(XMLHttpRequest, textStatus, errorThrown) { 
                    $("#contentUpdate").hide().fadeIn("slow").html("error loading step details.");
                }
            });
        }

        $(document).on("input", "#opContainer input.textTableInput", function () {
            this.style.backgroundColor = '#FDF19D';
            $('.editH1').show();
            $('.editH2').hide();
        });

        function opStatusChange(e){
            var thisStatus = e.options[e.selectedIndex].value;
            $('#opStaStore').val(thisStatus);
            e.style.backgroundColor = '#FDF19D';
            $('.editH1').show();
            $('.editH2').hide();
        }

        function saveOpDetail(opID){

            var opTitle = $('#opTitle').val();
            var opDesc = $('#opDesc').val();
            var opDetail = $('#opDetail').val();
            var opType = $('#opType').val();
            var opSta = $('#opStaStore').val();

            $.ajax({
                type: 'POST',
                url: 'fp/op_save_detail.php',   
                dataType: 'html',
                data: {
                   op_id : opID,
                   op_title : opTitle,
                   op_desc : opDesc,
                   op_detail : opDetail,
                   op_type : opType,
                   op_sta : opSta
                },
                success: function (html) {
                    if (html == 1){
                        alert("Error updating data.");
                    } else if (html == 2){
                        alert("Error sending data.");
                    }
                    editOp(opID);
                    updateOpList();
                },
                error: function(XMLHttpRequest, textStatus, errorThrown) { 
                    $("#contentUpdate").hide().fadeIn("slow").html("error loading step details.");
                }
            });
        }

        function updateOpList(){
            
            var opList = $('#stepSelection').val();

            switch(opList){
                case "Active":
                    getOpList("Active")
                    break;

                case "Inactive":
                    getOpList("Inactive")
                    break;

                case "String":
                    getOpListString()
                    break;

                case "All":
                    getOpList("%")
                    break;

                case "Type":
                    var typeVal = $('#opByType').val();
                    getOpList('Type', typeVal);
                    break;
                
                default:
                    getOpList("%")
                    break;
            }

        }

        function saveNewOp(){
            
            var opTitle = $('#opTitle').val();
            var opDesc = $('#opDesc').val();
            var opDetail = $('#opDetail').val();
            var opType = $('#opType').val();
            var opSta = $('#opStaStore').val();

            $.ajax({
                type: 'POST',
                url: 'fp/op_save_new.php',   
                dataType: 'html',
                data: {
                   op_title : opTitle,
                   op_desc : opDesc,
                   op_detail : opDetail,
                   op_type : opType,
                   op_sta : opSta
                },
                success: function (html) {
                    if (html == 1){
                        alert("Error updating data.");
                    } else if (html == 2){
                        alert("Error sending data.");
                    } else {
                        $('#stepSelection').val(opSta);
                        editOp(html);
                        updateOpList();
                    }
                },
                error: function(XMLHttpRequest, textStatus, errorThrown) { 
                    $("#contentUpdate").hide().fadeIn("slow").html("error loading step details.");
                }
            });
        }

        function deleteOp(){

            if (confirm('PERMANENTLY delete this Step?')) {
                
                                if (confirm('Confirm Step deletion.')) {
                                    
                                    var opID = document.getElementById("opID").value
                                    
                                        $.ajax({
                                            type: 'POST',
                                            url: 'fp/op_delete.php',
                                            dataType: 'html',
                                            data: {
                                                op_ID: opID
                                            },
                                            success: function (html) {
                                                if (html == 1){
                                                    alert("Error deleting workflow.");
                                                } else if (html == 2){
                                                    alert("Error sending data.");
                                                } else if (html == 3){
                                                    alert("Ownership Error.");
                                                } else {
                                                    updateOpList(); 
                                                    h3();                                                  
                                                }
                                            },
                                            error: function(XMLHttpRequest, textStatus, errorThrown) { 
                                                alert("error");
                                            }
                                        });
                
                                } else {
                                    
                                }
                            } else {
                                
                            }
        }

    </script>
</body>
</html>

_FixedHTML;

?>