
<?php
include 'includes/checkInvalidUser.php';
?>
<!DOCTYPE html>
<html>
    <head>
        <title >HMS : Manage Drugs</title>
        <?php require_once 'includes/html_main.php'; ?>
        <?php require_once 'includes/admin_init.php'; ?>
        <link href="css/list.css" rel="stylesheet"/>
        <link href="css/prescription.css" rel="stylesheet"/>
        <script src="js/prescription.js"></script>
        <script>
            function listbox_item_move(source,destination){
                var src = document.getElementById(source);
                var dest = document.getElementById(destination);

                for(var count=0; count < src.options.length; count++) {

                    if(src.options[count].selected == true) {
                        var option = src.options[count];

                        var newOption = document.createElement("option");
                        newOption.value = option.value;
                        newOption.text = option.text;
                        newOption.selected = true;
                        try {
                            dest.add(newOption, null); //Standard
                            src.remove(count, null);
                        }catch(error) {
                            dest.add(newOption); // IE only
                            src.remove(count);
                        }
                        count--;

                    }

                }
            }
            $(document).ready(function() {
                var options = {
                    valueNames: ['option','drugname' ]
                };

                var drugs = new List('drugs', options);
                $('[data-toggle="tooltip"]').tooltip({
                    container : 'body'
                });  
            });
        </script>
    </head>
    <body>

        <?php require_once('includes/menu_navbar.php'); ?>

        <div class="toggled-2" id="wrapper">
            <?php require_once('includes/menu_sidebar.php'); ?>
            <!-- Page Content -->
            <div id="page-content-wrapper">
                <div class="container xyz">
                    <div class="row">

                        <div class="col-lg-12 content panel panel-default">
                            <div class="panel-heading heading">
                                <i class="fa fa-file"> Prescription</i>
                            </div>
                            <div class="panel-body">

                                <div class="">
                                    <ul class="nav nav-tabs">
                                        <li class="active"><a href="#prescription"><i class="fa fa-plus"></i> Prescription</a></li>
                                        <li><a href="#prescriptionlist"><i class="fa fa-file"></i> Prescription List</a></li>
                                    </ul>
                                    <div class="row tab-content tab-prescription">
                                        <div id="prescription" class="tab-pane fade in active">
                                            <div class="prescription-add-header">
                                                <h4><span class="fa fa-plus"></span> Add Prescription</h4>
                                            </div>
                                            <div class="prescription-add-content">
                                                <form action="" id="frm-add-prescription">
                                                    <div class="row">
                                                        <div class="col-md-2 detail">Doctor Name</div>
                                                        <div class="col-md-3">
                                                            <select id="doc-id" class="form-control input-group">
                                                                <option value="select doctor">Select Doctor</option>
                                                                <?php
                                                                $sql = "SELECT doctor.*
                                                                        FROM wtfindin_hms.doctor";
                                                                $arRes = $mysqli->query($sql);

                                                                $detail = array();
                                                                while ($row = $arRes->fetch_assoc()) {
                                                                    echo "<option value=" . $row['d_id'];
                                                                    echo ">" . $row['d_name'];
                                                                    echo "</option>";
                                                                }
                                                                ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="row radio-field">
                                                        <div class="col-md-2 detail">Patient Type</div>
                                                        <div class="col-md-1 detail">
                                                            <label class="radio-inline">
                                                                <input type="radio" name="patientType" >InPatient
                                                            </label>
                                                        </div>
                                                        <div class="col-md-1 detail">
                                                            <label class="radio-inline">
                                                                <input type="radio" name="patientType">OutPatient
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-2 detail">Employee ID</div>
                                                        <div class="col-md-3">
                                                            <input class="form-control input-group" type="text" id="emp-id" name='emp-id' autocomplete="off"/>
                                                        </div>
                                                        <div class="col-md-5 detail" id="show-detail">
                                                            <i class="glyphicon glyphicon-ok" id="found"> Name: <label id="employee-name"></label> </i>
                                                            <i class="glyphicon glyphicon-remove" id="notfound"></i>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-md-2 detail">Date</div>
                                                        <div class="col-md-3">
                                                            <input class="form-control input-group" readonly type="text" id="date" name='date' value="<?php echo date('d/m/Y'); ?>" autocomplete="off"/>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <table>
                                                            <tr>
                                                                <td>
                                                                    <div class="col-md-12">
                                                                        <input type="text" id="searchMed" name="searchMed" placeholder="search" class="col-md-12"/>
                                                                        <select style="width: 100%" name="medicine-list[]" id="medicine-list" size="10" multiple="true">

                                                                        </select>
                                                                    </div>
                                                                </td>

                                                                <td width="10%">
                                                                    <div class="col-md-12">
                                                                        <div class="btn-list"><input class="btn btn-default col-md-12" type="button" id="addtolist" onclick="listbox_item_move('medicine-list', 'medicine-selected')" value=">>"/></div>
                                                                    </div>
                                                                    <div class="col-md-12">
                                                                        <div class="btn-list"><input class="btn btn-default col-md-12" type="button" id="removefromlist" onclick="listbox_item_move('medicine-selected', 'medicine-list')" value="<<"/></div>
                                                                    </div>
                                                                </td>

                                                                <td width="45%">
                                                                    <div style="margin:5px;">Prescribed Medicine</div>
                                                                    <select style="width: 100%" name="medicine-selected[]" id="medicine-selected" size="11" multiple="true">

                                                                    </select>
                                                                </td>
                                                            </tr>
                                                        </table>
                                                    </div>
                                            </div>
                                            <div class="row textArea-content">
                                                <div class="col-md-6 textArea">
                                                    <div class="col-md-12">
                                                        <div>Remark</div>
                                                        <textarea class="form-control" type="text" id="date" name='date' autocomplete="off"></textarea>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 textArea">
                                                    <div class="col-md-12">
                                                        <div>Note</div>
                                                        <textarea class="form-control" type="text" id="date" name='date' autocomplete="off"></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row btnPres">
                                                <div class="col-md-2">
                                                    <button type="button" class="btn btn-block btn-primary" name="btn-add-prescription-values" id="btn-add-prescription-values">
                                                        <span class="fa fa-plus"></span> Add Prescription</button>       
                                                </div>
                                            </div>
                                            </form>
                                        </div>

                                    </div> 


                                    <!--second tab-->
                                    <div id="prescriptionlist" class="tab-pane fade">
                                    </div>
                                </div>
                            </div>

                        </div>

                    </div>
                </div>
            </div>
            <!-- /#page-content-wrapper -->
        </div>
        <!-- /#wrapper -->
        <script src="js/profile.js"></script>

        <!--body div-->

    </body>
</html>

